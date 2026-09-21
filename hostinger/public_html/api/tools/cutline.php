<?php
// Cutline: subscription tracker API. Bum Bum port of Subscription Cut.
// Shared schema + renewal-math helpers live here; the reminder cron endpoint
// (cutline-reminders.php) reuses them via require. Deposits, catalog seed and
// passwordless login from the original are intentionally not ported.
require dirname(__DIR__) . '/_bootstrap.php';

const CUTLINE_CADENCES = ['weekly', 'monthly', 'quarterly', 'semiannual', 'annual'];
const CUTLINE_CATEGORIES = ['streaming', 'music', 'software', 'cloud_storage', 'fitness', 'reading', 'gaming', 'food_delivery', 'other'];

function ensureCutlineSchema(): void {
    $pdo = db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS cutline_subscriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        custom_name VARCHAR(191),
        category VARCHAR(64) NOT NULL,
        tier_name VARCHAR(191),
        price_cents INT NOT NULL,
        currency CHAR(3) DEFAULT 'USD',
        cadence VARCHAR(16) NOT NULL,
        started_on DATE NOT NULL,
        next_renewal_on DATE NOT NULL,
        status VARCHAR(16) DEFAULT 'active',
        is_free_trial TINYINT DEFAULT 0,
        trial_ends_on DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_user (user_id),
        KEY idx_renewal (next_renewal_on)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS cutline_reminder_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subscription_id INT NOT NULL,
        renewal_date_at_send DATE NOT NULL,
        sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_sub_renewal (subscription_id, renewal_date_at_send)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS cutline_prefs (
        user_id INT PRIMARY KEY,
        notify_days_before INT DEFAULT 3
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

// Adds one cadence step. Monthly and longer steps clamp to the last day of the
// target month so renewal dates stay stable (Jan 31 -> Feb 28, not Mar 3).
function cutline_add_cadence(DateTime $date, string $cadence): void {
    if ($cadence === 'weekly') {
        $date->add(new DateInterval('P1W'));
        return;
    }
    $months = ['monthly' => 1, 'quarterly' => 3, 'semiannual' => 6, 'annual' => 12][$cadence] ?? 1;
    $day = (int) $date->format('d');
    $date->modify('first day of +' . $months . ' month');
    $lastDay = (int) $date->format('t');
    $date->setDate((int) $date->format('Y'), (int) $date->format('m'), min($day, $lastDay));
}

// Rolls a renewal date forward until it is today or later. Never infinite:
// 1200 steps covers about 23 years of weekly renewals.
function cutline_roll_forward(string $date, string $cadence): string {
    $d = new DateTime($date);
    $today = new DateTime('today');
    $guard = 0;
    while ($d < $today && $guard++ < 1200) {
        cutline_add_cadence($d, $cadence);
    }
    return $d->format('Y-m-d');
}

function cutline_monthly_cents(int $priceCents, string $cadence): float {
    return match ($cadence) {
        'weekly' => $priceCents * 4.33,
        'quarterly' => $priceCents / 3,
        'semiannual' => $priceCents / 6,
        'annual' => $priceCents / 12,
        default => $priceCents,
    };
}

function cutline_valid_date(mixed $value): bool {
    if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) return false;
    $d = DateTime::createFromFormat('Y-m-d', $value);
    return $d && $d->format('Y-m-d') === $value;
}

// Validates add/update input. Returns [errors, clean] where clean holds
// DB-ready values (past renewal dates are rolled forward to stay sane).
function cutline_validate(array $input): array {
    $errors = [];
    $name = trim((string) ($input['custom_name'] ?? ''));
    if ($name === '') $errors[] = 'Give the subscription a name.';
    elseif (mb_strlen($name) > 191) $errors[] = 'Keep the name under 191 characters.';
    $category = (string) ($input['category'] ?? '');
    if (!in_array($category, CUTLINE_CATEGORIES, true)) $errors[] = 'Pick a valid category.';
    $tier = trim((string) ($input['tier_name'] ?? ''));
    if (mb_strlen($tier) > 191) $errors[] = 'Keep the plan name under 191 characters.';
    $cents = filter_var($input['price_cents'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($cents === false) $errors[] = 'Enter a valid price in cents (zero or more).';
    $currency = strtoupper(trim((string) ($input['currency'] ?? 'USD')));
    if (!preg_match('/^[A-Z]{3}$/', $currency)) $errors[] = 'Use a 3-letter currency code.';
    $cadence = (string) ($input['cadence'] ?? '');
    if (!in_array($cadence, CUTLINE_CADENCES, true)) $errors[] = 'Pick a valid billing cadence.';
    $started = (string) ($input['started_on'] ?? '');
    if (!cutline_valid_date($started)) $errors[] = 'Enter a valid start date.';
    $renewal = (string) ($input['next_renewal_on'] ?? '');
    if (!cutline_valid_date($renewal)) $errors[] = 'Enter a valid next renewal date.';
    $trialEnds = $input['trial_ends_on'] ?? null;
    if ($trialEnds !== null && $trialEnds !== '' && !cutline_valid_date((string) $trialEnds)) {
        $errors[] = 'Enter a valid trial end date.';
    }
    if ($errors) return [$errors, []];
    return [[], [
        'custom_name' => $name,
        'category' => $category,
        'tier_name' => $tier !== '' ? $tier : null,
        'price_cents' => $cents,
        'currency' => $currency,
        'cadence' => $cadence,
        'started_on' => $started,
        'next_renewal_on' => cutline_roll_forward($renewal, $cadence),
        'is_free_trial' => !empty($input['is_free_trial']) ? 1 : 0,
        'trial_ends_on' => ($trialEnds !== null && $trialEnds !== '') ? (string) $trialEnds : null,
    ]];
}

function cutline_public_row(array $row): array {
    $today = new DateTime('today');
    $d = new DateTime($row['next_renewal_on']);
    return [
        'id' => (int) $row['id'],
        'custom_name' => $row['custom_name'],
        'category' => $row['category'],
        'tier_name' => $row['tier_name'],
        'price_cents' => (int) $row['price_cents'],
        'currency' => $row['currency'],
        'cadence' => $row['cadence'],
        'started_on' => $row['started_on'],
        'next_renewal_on' => $row['next_renewal_on'],
        'status' => $row['status'],
        'is_free_trial' => (int) $row['is_free_trial'],
        'trial_ends_on' => $row['trial_ends_on'],
        'days_until' => (int) $today->diff($d)->format('%r%a'),
    ];
}

// The dispatch below only runs when this file is hit directly over HTTP.
// cutline-reminders.php requires this file for the helpers and skips it.
if (basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === 'cutline.php') {
    requirePost();
    $input = body();
    requireCsrf($input);
    $user = requireUser();
$bill = billingUser($user);
    $userId = (int) $user['id'];
    ensureCutlineSchema();
    $pdo = db();
    $action = (string) ($input['action'] ?? '');

    if ($action === 'list') {
        $pdo->prepare('INSERT IGNORE INTO cutline_prefs (user_id) VALUES (?)')->execute([$userId]);
        $stmt = $pdo->prepare("SELECT * FROM cutline_subscriptions WHERE user_id = ? AND status = 'active' ORDER BY next_renewal_on ASC");
        $stmt->execute([$userId]);
        $subs = [];
        $total = 0.0;
        foreach ($stmt->fetchAll() as $row) {
            $total += cutline_monthly_cents((int) $row['price_cents'], $row['cadence']);
            $subs[] = cutline_public_row($row);
        }
        $prefStmt = $pdo->prepare('SELECT notify_days_before FROM cutline_prefs WHERE user_id = ?');
        $prefStmt->execute([$userId]);
        $prefVal = $prefStmt->fetchColumn();
        $notify = ($prefVal === false || $prefVal === null) ? 3 : (int) $prefVal;
        jsonResponse([
            'subscriptions' => $subs,
            'prefs' => ['notify_days_before' => $notify],
            'monthly_total_cents' => round($total),
            'count' => count($subs),
        ]);
    }

    if ($action === 'add') {
        $idempotency = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['idempotencyKey'] ?? ''));
        if (strlen($idempotency) < 16 || strlen($idempotency) > 128) {
            jsonResponse(['error' => 'Invalid request identifier.'], 422);
        }
        [$errors, $clean] = cutline_validate($input);
        if ($errors) jsonResponse(['error' => $errors[0]], 422);
        $count = consumeAction((int) $bill['id'], (string) $bill['plan'], periodKey($bill), 'cutline', $idempotency);
        if (!empty($count['limit_reached'])) {
            jsonResponse(['error' => 'You have used all actions for this month.', 'usage' => $count], 402);
        }
        if (empty($count['duplicate'])) {
            $stmt = $pdo->prepare('INSERT INTO cutline_subscriptions
                (user_id, custom_name, category, tier_name, price_cents, currency, cadence, started_on, next_renewal_on, is_free_trial, trial_ends_on)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $userId, $clean['custom_name'], $clean['category'], $clean['tier_name'],
                $clean['price_cents'], $clean['currency'], $clean['cadence'],
                $clean['started_on'], $clean['next_renewal_on'],
                $clean['is_free_trial'], $clean['trial_ends_on'],
            ]);
            $newId = (int) $pdo->lastInsertId();
        } else {
            $newId = 0;
        }
        $row = null;
        if ($newId) {
            $stmt = $pdo->prepare('SELECT * FROM cutline_subscriptions WHERE id = ? AND user_id = ?');
            $stmt->execute([$newId, $userId]);
            $row = $stmt->fetch();
        }
        jsonResponse([
            'subscription' => $row ? cutline_public_row($row) : null,
            'usage' => usageFor($bill),
            'duplicate' => (bool) ($count['duplicate'] ?? false),
        ]);
    }

    if ($action === 'update') {
        $id = (int) ($input['id'] ?? 0);
        if ($id <= 0) jsonResponse(['error' => 'Missing subscription id.'], 422);
        [$errors, $clean] = cutline_validate($input);
        if ($errors) jsonResponse(['error' => $errors[0]], 422);
        $stmt = $pdo->prepare('UPDATE cutline_subscriptions SET
                custom_name = ?, category = ?, tier_name = ?, price_cents = ?,
                currency = ?, cadence = ?, started_on = ?, next_renewal_on = ?,
                is_free_trial = ?, trial_ends_on = ?
            WHERE id = ? AND user_id = ?');
        $stmt->execute([
            $clean['custom_name'], $clean['category'], $clean['tier_name'],
            $clean['price_cents'], $clean['currency'], $clean['cadence'],
            $clean['started_on'], $clean['next_renewal_on'],
            $clean['is_free_trial'], $clean['trial_ends_on'],
            $id, $userId,
        ]);
        if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Subscription not found.'], 404);
        $stmt = $pdo->prepare('SELECT * FROM cutline_subscriptions WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        jsonResponse(['subscription' => cutline_public_row($stmt->fetch())]);
    }

    if ($action === 'delete') {
        $id = (int) ($input['id'] ?? 0);
        if ($id <= 0) jsonResponse(['error' => 'Missing subscription id.'], 422);
        $stmt = $pdo->prepare('DELETE FROM cutline_subscriptions WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Subscription not found.'], 404);
        jsonResponse(['ok' => true, 'id' => $id]);
    }

    if ($action === 'prefs') {
        if (array_key_exists('notify_days_before', $input)) {
            $days = filter_var($input['notify_days_before'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 30]]);
            if ($days === false) jsonResponse(['error' => 'Pick 0 to 30 days.'], 422);
            $pdo->prepare('INSERT INTO cutline_prefs (user_id, notify_days_before) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE notify_days_before = VALUES(notify_days_before)')
                ->execute([$userId, $days]);
        }
        $stmt = $pdo->prepare('SELECT notify_days_before FROM cutline_prefs WHERE user_id = ?');
        $stmt->execute([$userId]);
        $prefVal = $stmt->fetchColumn();
        jsonResponse(['prefs' => ['notify_days_before' => ($prefVal === false || $prefVal === null) ? 3 : (int) $prefVal]]);
    }

    jsonResponse(['error' => 'Unknown action.'], 400);
}
