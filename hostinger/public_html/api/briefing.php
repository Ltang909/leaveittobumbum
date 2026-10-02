<?php
declare(strict_types=1);

/**
 * Mission-control briefing triage: mark one briefing item done.
 *
 * - kind=followup: clears the follow-up date on the job application
 *   (the nudge is handled).
 * - kind=invoice: marks the invoice paid.
 *
 * Free: this is inbox triage, not tool use. Signed-in users only, rows
 * strictly scoped to the user.
 *
 *   POST /api/briefing.php  {kind, id, csrf}
 *   -> {ok:true} | {error}
 */

require __DIR__ . '/_bootstrap.php';

requirePost();
$input = body();
requireCsrf($input);
$user = currentUser();
if (!$user) jsonResponse(['error' => 'Sign in first.'], 401);

$kind = (string) ($input['kind'] ?? '');
$id = (int) ($input['id'] ?? 0);
if ($id <= 0) jsonResponse(['error' => 'Missing item.'], 422);
$uid = (int) $user['id'];

try {
    if ($kind === 'followup') {
        $stmt = db()->prepare('UPDATE jobtrack_contacts SET follow_up_date = NULL WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $uid]);
    } elseif ($kind === 'invoice') {
        $stmt = db()->prepare("UPDATE chaser_invoices SET status = 'paid', paid_at = NOW() WHERE id = ? AND user_id = ? AND status = 'open'");
        $stmt->execute([$id, $uid]);
    } else {
        jsonResponse(['error' => 'Unknown item.'], 422);
    }
} catch (Throwable $e) {
    jsonResponse(['error' => 'Could not update that.'], 500);
}
if ($stmt->rowCount() === 0) jsonResponse(['error' => 'Already handled.'], 404);
jsonResponse(['ok' => true]);
