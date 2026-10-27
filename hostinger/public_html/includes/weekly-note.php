<?php
// The Monday note callout, shown on the four tracker tools whose data
// feeds the weekly recap email (Subscription Tracker, Invoice Chaser,
// Client Follow-Up Tracker, Job Application Tracker). Expects the tool
// page's $user / $isGuest from pageSubject(). Signed-in users get the
// manage link; guests get the free-account pitch; anyone else (no
// subject yet) sees nothing.
if (!empty($user)): ?>
<div style="border:2px solid var(--line);border-radius:16px;padding:16px 20px;background:#fdfaf1;margin:24px 0">&#9993;&#65038; <strong>The Monday note:</strong> anything coming due here also lands in your weekly email, one roundup of every tracker, every Monday morning. <a href="/account/#weekly-note" style="font-weight:900;color:inherit">Manage it in your account</a></div>
<?php elseif (!empty($isGuest)): $wnPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/'; ?>
<div style="border:2px solid var(--line);border-radius:16px;padding:16px 20px;background:#fdfaf1;margin:24px 0">&#9993;&#65038; <strong>The Monday note:</strong> free accounts get one weekly email with everything coming due across Bum Bum's trackers, this one included. <a href="/account/?next=<?= urlencode($wnPath) ?>" style="font-weight:900;color:inherit">Create a free account</a></div>
<?php endif; ?>
