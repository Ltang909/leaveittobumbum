<?php
// Team management: invite / list / remove / leave / accept.
// Owners manage seats; members share the owner's action bucket.
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
requirePost();
$input = body();
requireCsrf($input);
$user = requireUser();
$action = (string) ($input['action'] ?? '');

function inviteLink(string $token): string {
    $scheme = 'https://';
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'leaveittobumbum.com'));
    return $scheme . $host . '/join/?token=' . $token;
}

function teamOwner(array $user): array {
    $bill = billingUser($user);
    if (($bill['team_role'] ?? 'owner') === 'member') jsonResponse(['error' => 'Only the team owner can manage seats.'], 403);
    return $bill;
}

// Lock the owner's user row so concurrent invite/accept/remove requests for
// the same team serialize on seat counts. Uncommitted transactions are rolled
// back automatically when jsonResponse() exits the script.
function lockOwner(int $ownerId): void {
    db()->prepare('SELECT id FROM users WHERE id = ? FOR UPDATE')->execute([$ownerId]);
}

if ($action === 'list') {
    $bill = billingUser($user);
    $isOwner = ($bill['team_role'] ?? 'owner') === 'owner';
    $members = [];
    if ($isOwner) {
        try {
            $stmt = db()->prepare("SELECT tm.id, tm.email, tm.status, tm.invite_token, tm.created_at, tm.accepted_at, u.email AS member_email FROM team_members tm LEFT JOIN users u ON u.id = tm.member_user_id WHERE tm.owner_user_id = ? AND tm.status IN ('invited','active') ORDER BY tm.created_at DESC");
            $stmt->execute([(int) $user['id']]);
            $members = $stmt->fetchAll();
        } catch (Throwable $e) {
            $members = [];
        }
        foreach ($members as &$m) {
            $m['invite_link'] = $m['status'] === 'invited' ? inviteLink($m['invite_token']) : null;
            unset($m['invite_token']);
        }
        unset($m);
    }
    $ownerId = $isOwner ? (int) $user['id'] : (int) $bill['team_owner_id'];
    $ownerPlan = $isOwner ? (string) $user['plan'] : (string) $bill['plan'];
    jsonResponse([
        'role' => $isOwner ? 'owner' : 'member',
        'owner_email' => $isOwner ? (string) $user['email'] : (string) $bill['team_owner_email'],
        'seats' => teamSeats($ownerId, $ownerPlan),
        'members' => $members,
        'usage' => usageFor($bill),
    ]);
}

if ($action === 'invite') {
    teamOwner($user);
    $email = strtolower(trim((string) ($input['email'] ?? '')));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResponse(['error' => 'Enter a valid email address.'], 422);
    if ($email === strtolower((string) $user['email'])) jsonResponse(['error' => 'That is your own email. You already have the best seat.'], 422);
    $pdo = db();
    $pdo->beginTransaction();
    try {
        lockOwner((int) $user['id']);
        $seats = teamSeats((int) $user['id'], (string) $user['plan']);
        if ($seats['remaining'] <= 0) jsonResponse(['error' => 'No seats left on your plan. Upgrade for more team members.'], 402);
        $existing = $pdo->prepare("SELECT id, status, invite_token FROM team_members WHERE owner_user_id = ? AND email = ? AND status IN ('invited','active','removed') LIMIT 1 FOR UPDATE");
        $existing->execute([(int) $user['id'], $email]);
        $row = $existing->fetch();
        if ($row) {
            if ($row['status'] === 'active') jsonResponse(['error' => 'They are already on your team.'], 409);
            if ($row['status'] === 'invited') {
                $pdo->commit();
                jsonResponse(['ok' => true, 'invite_link' => inviteLink($row['invite_token']), 'resent' => true]);
            }
            // Previously removed: reuse the row with a fresh token instead of
            // colliding with the UNIQUE(owner_user_id, email) key.
            $token = bin2hex(random_bytes(32));
            $pdo->prepare("UPDATE team_members SET member_user_id = NULL, invite_token = ?, status = 'invited', accepted_at = NULL WHERE id = ?")->execute([$token, (int) $row['id']]);
            $pdo->commit();
            posthogCapture('team_invite_sent', (string) $user['email'], []);
            jsonResponse(['ok' => true, 'invite_link' => inviteLink($token)]);
        }
        // Do not invite someone who is already an active member of another team.
        $target = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $target->execute([$email]);
        if ($targetUser = $target->fetch()) {
            if (teamMembership((int) $targetUser['id'])) jsonResponse(['error' => 'They are already on another team.'], 409);
        }
        $token = bin2hex(random_bytes(32));
        $pdo->prepare("INSERT INTO team_members (owner_user_id, email, invite_token, status) VALUES (?, ?, ?, 'invited')")->execute([(int) $user['id'], $email, $token]);
        $pdo->commit();
        posthogCapture('team_invite_sent', (string) $user['email'], []);
        jsonResponse(['ok' => true, 'invite_link' => inviteLink($token)]);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        if ($e->getCode() === '23000') jsonResponse(['error' => 'An invite for that email is already in flight.'], 409);
        error_log('Team invite failed: ' . $e->getMessage());
        jsonResponse(['error' => 'Could not create that invite. Try again.'], 500);
    }
}

if ($action === 'remove' || $action === 'revoke') {
    teamOwner($user);
    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0) jsonResponse(['error' => 'Missing member.'], 422);
    db()->prepare("UPDATE team_members SET status = 'removed', member_user_id = NULL WHERE id = ? AND owner_user_id = ? AND status IN ('invited','active')")->execute([$id, (int) $user['id']]);
    jsonResponse(['ok' => true]);
}

if ($action === 'leave') {
    $stmt = db()->prepare("UPDATE team_members SET status = 'removed', member_user_id = NULL WHERE member_user_id = ? AND status = 'active'");
    $stmt->execute([(int) $user['id']]);
    jsonResponse(['ok' => true, 'left' => $stmt->rowCount() > 0]);
}

if ($action === 'accept') {
    $token = (string) ($input['token'] ?? '');
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) jsonResponse(['error' => 'That invite link is not valid.'], 422);
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("SELECT tm.*, u.plan AS owner_plan FROM team_members tm JOIN users u ON u.id = tm.owner_user_id WHERE tm.invite_token = ? AND tm.status = 'invited' LIMIT 1 FOR UPDATE");
        $stmt->execute([$token]);
        $invite = $stmt->fetch();
        if (!$invite) jsonResponse(['error' => 'This invite is expired or already used. Ask the owner for a fresh link.'], 410);
        lockOwner((int) $invite['owner_user_id']);
        if ((int) $invite['owner_user_id'] === (int) $user['id']) jsonResponse(['error' => 'You cannot join your own team.'], 422);
        if (teamMembership((int) $user['id'])) jsonResponse(['error' => 'You are already on a team. Leave it first to join another.'], 409);
        // A team owner with teammates cannot join someone else's team while
        // their own seats are occupied.
        $owns = $pdo->prepare("SELECT COUNT(*) FROM team_members WHERE owner_user_id = ? AND status IN ('invited','active')");
        $owns->execute([(int) $user['id']]);
        if ((int) $owns->fetchColumn() > 0) jsonResponse(['error' => 'You run your own team. Remove your teammates first, then join theirs.'], 409);
        // This invite already holds a seat reservation, so the seat check
        // excludes it: accepting is valid whenever used <= limit.
        $seats = teamSeats((int) $invite['owner_user_id'], (string) $invite['owner_plan']);
        if ($seats['used'] - 1 >= $seats['limit']) jsonResponse(['error' => 'That team is full. Ask the owner to upgrade for more seats.'], 402);
        $upd = $pdo->prepare("UPDATE team_members SET member_user_id = ?, email = ?, status = 'active', accepted_at = UTC_TIMESTAMP() WHERE id = ? AND status = 'invited'");
        $upd->execute([(int) $user['id'], strtolower((string) $user['email']), (int) $invite['id']]);
        $pdo->commit();
        posthogCapture('team_invite_accepted', (string) $user['email'], []);
        $dormant = in_array($user['subscription_status'], ['active', 'trialing', 'past_due'], true) && $user['plan'] !== 'free';
        jsonResponse(['ok' => true, 'dormant_plan' => $dormant]);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        // UNIQUE(member_user_id) backstop: someone beat this request to the seat.
        if ($e->getCode() === '23000') jsonResponse(['error' => 'You are already on a team. Leave it first to join another.'], 409);
        error_log('Team accept failed: ' . $e->getMessage());
        jsonResponse(['error' => 'Something went wrong accepting that invite.'], 500);
    }
}

jsonResponse(['error' => 'Unknown action.'], 400);
