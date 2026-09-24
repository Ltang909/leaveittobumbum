<?php require dirname(__DIR__) . '/api/_bootstrap.php'; $user = currentUser(); $bill = $user ? billingUser($user) : null; $usage = $bill ? usageFor($bill) : null; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Your account | Leave It to Bum Bum</title><link rel="stylesheet" href="/app.css?v=5"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700;9..144,900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><?php require __DIR__ . '/../includes/analytics.php'; ?><style>
#toolbox{margin-top:28px}
.toolbox-head{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}
.toolbox-head h2{font-size:32px;margin:0}
.tool-cards{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:20px}
.tool-card{border:2px solid var(--line);border-radius:22px;padding:24px;box-shadow:8px 8px 0 var(--line);text-decoration:none;color:inherit;display:flex;flex-direction:column;transition:transform .12s ease,box-shadow .12s ease}
.tool-card:hover{transform:translate(-2px,-2px);box-shadow:10px 10px 0 var(--line)}
.tool-card-top{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:14px}
.tool-icon{width:46px;height:46px;border:2px solid var(--line);border-radius:50%;display:flex;align-items:center;justify-content:center;background:#fff;font-size:20px;font-weight:900}
.tool-tag{font-size:12px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}
.tool-card h3{margin:0 0 8px;font-size:30px;line-height:1.05}
.tool-card p{margin:0 0 18px;line-height:1.5}
.tool-cta{margin-top:auto;border-top:2px solid var(--line);padding-top:14px;font-weight:900;display:flex;justify-content:space-between;align-items:center}
.tc-yellow{background:#ffd84d}.tc-pink{background:#ffb3d1}.tc-mint{background:#b9f2cf}.tc-blue{background:#b7d6ff}.tc-cream{background:#fffdf4}.tc-orange{background:#ffc59b}.tc-lavender{background:#d6c9f5}.tc-teal{background:#b2e8dc}.tc-rose{background:#ffb3b3}
#tool-picker{margin-top:20px;border:2px dashed var(--line);border-radius:16px;padding:20px;background:#fff}
.tool-pick{display:flex;gap:10px;flex-wrap:wrap;margin:14px 0 4px}
.tool-pick label{display:inline-flex;align-items:center;gap:8px;font-weight:800;border:2px solid var(--line);border-radius:999px;padding:10px 16px;background:var(--cream);cursor:pointer;margin:0}
.tool-pick input{width:auto;margin:0}
#toolMsg{margin-left:8px}
.activity{list-style:none;padding:0;margin:14px 0 0}
.activity li{padding:10px 0;border-top:2px solid var(--line)}
.activity li span:first-child{font-weight:800}
#activity ul.activity{max-height:280px;overflow-y:auto;padding-right:10px}
main .grid{margin-top:28px}
main .grid .panel{margin-top:0}
@media(max-width:700px){.tool-cards{grid-template-columns:1fr}}
</style></head><body>
<?php $showMeter = (bool) $user; require __DIR__ . '/../includes/site-header.php'; ?><main class="shell">
<?php if (!$user): ?><p class="eyebrow">Your workspace</p><h1>First, tell Bum Bum who you are.</h1><div class="grid"><section class="panel tc-blue"><h2>Sign in</h2><form data-action="login"><label>Work email</label><input name="email" type="email" required><label>Password</label><input name="password" type="password" minlength="10" required><button>Sign in</button><p class="error"></p></form></section><section class="panel tc-yellow"><h2>Create an account</h2><p>Your free workspace includes 75 completed actions each month.</p><form data-action="register"><label>Work email</label><input name="email" type="email" required><label>Password</label><input name="password" type="password" minlength="10" required><button>Create free account</button><p class="error"></p></form></section></div>
<script>
document.querySelectorAll('form').forEach(form=>form.addEventListener('submit',async event=>{event.preventDefault();const button=form.querySelector('button');button.disabled=true;const session=await fetch('/api/session.php').then(r=>r.json());const fields=Object.fromEntries(new FormData(form));const response=await fetch('/api/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...fields,action:form.dataset.action,csrf:session.csrf})});const data=await response.json();if(response.ok){bbIdentify(fields.email);bbTrack(form.dataset.action==='register'?'signed_up':'signed_in',{});const next=new URLSearchParams(location.search).get('next');location.href=next||'/account/'}else{form.querySelector('.error').textContent=data.error;button.disabled=false}}));
</script>
<?php else: $percent = min(100, (int) round($usage['used'] / max(1, $usage['limit']) * 100));
$isMember = ($bill['team_role'] ?? 'owner') === 'member';
$isOperator = $bill['plan'] === 'operator' && in_array($bill['subscription_status'], ['active', 'trialing', 'past_due'], true);
$lowUsage = $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2);
$hour = (int) (new DateTime('now', new DateTimeZone('America/Toronto')))->format('G');
$greet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
$DASHBOARD_TOOLS = [
  'clips' => ['name' => 'Bum Bum Clips', 'tag' => 'VIDEO', 'icon' => '&#9679;', 'desc' => 'Record your screen right in your browser. Download the clip, keep it forever.', 'url' => '/tools/clips/', 'cta' => 'Record a clip'],
  'notes' => ['name' => 'Bum Bum Notes', 'tag' => 'VOICE', 'icon' => '&#9834;', 'desc' => 'Talk it out and get a live transcript you can copy or download.', 'url' => '/tools/notes/', 'cta' => 'Record a note'],
  'cutline' => ['name' => 'Cutline', 'tag' => 'MONEY', 'icon' => '&#9986;', 'desc' => 'Every subscription you forgot about, in one place, with renewal nudges.', 'url' => '/tools/cutline/', 'cta' => 'Cut subscriptions'],
  'purrsuit' => ['name' => 'Purrsuit', 'tag' => 'CLIENTS', 'icon' => '&#128100;', 'desc' => 'A tiny CRM that tells you who to follow up with today and what to say.', 'url' => '/tools/purrsuit/', 'cta' => 'Track every lead'],
  'corporate-bum-bum' => ['name' => 'Corporate Bum Bum', 'tag' => 'CAREER', 'icon' => '&#128188;', 'desc' => 'A job application tracker that tells you who to follow up with today and what to say.', 'url' => '/tools/corporate-bum-bum/', 'cta' => 'Track applications'],
  'doodle' => ['name' => 'Doodle', 'tag' => 'DRAW', 'icon' => '&#9999;&#65039;', 'desc' => 'A pocket sketchpad for signatures, diagrams, and masterpieces. Export as PNG or SVG.', 'url' => '/tools/doodle/', 'cta' => 'Start doodling'],
];
$DASHBOARD_ORDER = array_keys($DASHBOARD_TOOLS);
$selectedKeys = $DASHBOARD_ORDER;
try {
    db()->exec("CREATE TABLE IF NOT EXISTS user_tool_prefs (user_id BIGINT UNSIGNED PRIMARY KEY, tool_keys TEXT NOT NULL, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, CONSTRAINT tool_prefs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $prefStmt = db()->prepare('SELECT tool_keys FROM user_tool_prefs WHERE user_id = ?');
    $prefStmt->execute([(int) $user['id']]);
    $prefRow = $prefStmt->fetch();
    if ($prefRow) {
        $saved = json_decode((string) $prefRow['tool_keys'], true);
        if (is_array($saved)) {
            $clean = array_values(array_intersect($DASHBOARD_ORDER, array_map('strval', $saved)));
            if ($clean) $selectedKeys = $clean;
        }
    }
} catch (Throwable $e) { $selectedKeys = $DASHBOARD_ORDER; }
$activityRows = [];
try {
    $actStmt = db()->prepare("SELECT tool_key, action_count, created_at FROM action_ledger WHERE user_id = ? AND status = 'completed' ORDER BY id DESC LIMIT 25");
    $actStmt->execute([(int) $bill['id']]);
    $activityRows = $actStmt->fetchAll();
} catch (Throwable $e) { $activityRows = []; }
$toronto = new DateTimeZone('America/Toronto'); ?><p class="eyebrow">Your workspace</p><h1><?= $greet ?>. <?= ucfirst(htmlspecialchars($bill['plan'])) ?> is handling it.</h1><p class="lede"><?= htmlspecialchars($user['email']) ?> · Subscription <?= htmlspecialchars($user['subscription_status']) ?></p>
<section id="toolbox"><div class="toolbox-head"><h2>Your toolbox</h2><button id="customizeBtn" class="button secondary" style="margin-top:0">Customize</button></div>
<div id="tool-picker" class="hidden"><p class="lede" style="margin:0">Pick the tools that show up here.</p><div class="tool-pick">
<?php foreach ($DASHBOARD_ORDER as $key): $t = $DASHBOARD_TOOLS[$key]; ?>
<label><input type="checkbox" value="<?= htmlspecialchars($key) ?>"<?= in_array($key, $selectedKeys, true) ? ' checked' : '' ?>> <?= htmlspecialchars($t['name']) ?></label>
<?php endforeach; ?>
</div><button id="toolSave">Save</button><span id="toolMsg" class="error"></span></div>
<div class="tool-cards">
<?php $ci = 0; foreach ($selectedKeys as $key): $t = $DASHBOARD_TOOLS[$key]; $cc = 'tc-' . ['yellow', 'pink', 'mint', 'blue', 'cream', 'orange', 'lavender', 'teal', 'rose'][$ci % 9]; $ci++; ?>
<a class="tool-card <?= $cc ?>" href="<?= htmlspecialchars($t['url']) ?>" data-tool="<?= htmlspecialchars($key) ?>">
<div class="tool-card-top"><span class="tool-icon"><?= $t['icon'] ?></span><span class="tool-tag"><?= htmlspecialchars($t['tag']) ?></span></div>
<h3><?= htmlspecialchars($t['name']) ?></h3>
<p><?= htmlspecialchars($t['desc']) ?></p>
<div class="tool-cta"><span><?= htmlspecialchars($t['cta']) ?></span><span aria-hidden="true">&#8599;</span></div>
</a>
<?php endforeach; ?>
</div>
</section>
<div class="grid"><section class="stat"><span><?= ($bill['team_role'] ?? 'owner') === 'member' ? 'Team actions used' : 'Actions used' ?></span><b><?= $usage['used'] ?> / <?= $usage['limit'] ?></b><div class="meter"><span style="width:<?= $percent ?>%"></span></div><p><?= $usage['remaining'] ?> actions left in this period.<?= ($bill['team_role'] ?? 'owner') === 'member' ? ' Shared with ' . htmlspecialchars((string) $bill['team_owner_email']) . '\'s team.' : '' ?></p></section><section class="panel"><h2><?= $isMember ? 'Your billing' : 'Billing' ?></h2><p><?= $isMember ? 'This manages your own subscription only. The team plan is billed to the team owner.' : 'Update your payment method, download invoices, or change your subscription securely through Stripe.' ?></p><button id="billing">Manage billing</button><button id="logout" class="button secondary">Sign out</button><p id="message" class="error"></p><p><img src="/bum/cat-butt-v2.png" alt="Bum Bum walking away" class="sticker" style="width:104px;transform:rotate(5deg)"> <span class="lede">BRB…</span></p></section></div>
<section class="panel" id="team"><h2 style="margin-top:0">Team</h2><div id="team-body"><p class="lede">Waking Bum Bum up…</p></div></section>
<?php if ($isMember): ?>
<section class="panel" id="upgrade"><h2>On a team</h2><p class="lede">You are drawing from <?= htmlspecialchars((string) $bill['team_owner_email']) ?>'s shared action bucket. Upgrades and plan changes happen on their account, not yours.</p></section>
<?php elseif ($user['plan'] === 'free'): ?>
<section class="panel" id="upgrade"><h2><?= $lowUsage ? 'Almost out of free actions.' : 'Need more than 75 actions a month?' ?></h2><p class="lede"><?= $lowUsage ? 'You are getting real work done. Keep the momentum with more actions every month.' : 'Your free workspace resets every month. Paid plans give you room to grow.' ?></p><div class="plan-cards"><div class="panel tc-mint"><h2>Helper</h2><p><b>$12</b>/month</p><ul><li>1,500 actions every month</li><li>Every tool in the toolbox</li><li>Cancel anytime</li></ul><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper</a></div><div class="panel tc-yellow"><h2>Operator</h2><p><b>$49</b>/month</p><ul><li>6,000 actions every month</li><li>One custom tool built for you each month</li><li>Delivered in 36 hours or your next month is free</li></ul><a class="button" data-plan="operator" href="/checkout/?plan=operator">Get Operator</a></div></div></section>
<?php elseif ($user['plan'] === 'helper'): ?>
<section class="panel" id="upgrade-helper"><h2>Want a tool built just for you?</h2><p class="lede">Operator adds 6,000 actions a month plus one custom tool request. Describe the annoying task, get a working tool in 36 hours, or your next month is free.</p><a class="button" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></section>
<?php endif; ?>
<section class="panel" id="requests"><h2>Custom tool requests</h2>
<?php if ($isOperator): ?><p class="lede">One request per month. Delivered within 36 hours or your next month is free.</p><div id="req-list"></div><form id="req-form"><label>Give it a name</label><input name="title" maxlength="180" required placeholder="e.g. Invoice chaser"><label>What annoying task should it handle? What does done look like?</label><textarea name="details" maxlength="5000" required></textarea><button>Send request</button><p id="req-error" class="error"></p></form>
<?php else: ?><p class="lede">Operator includes one custom tool request every month. Describe the annoying task, get a working tool in 36 hours, or your next month is free.</p><a class="button" data-plan="operator" data-context="requests" href="/checkout/?plan=operator">Get Operator, $49/mo</a> <a class="button secondary" href="/requests/">Add to the free community queue</a><p class="lede" style="font-size:15px;margin-top:14px">Not in a rush? <a href="/requests/">See what&rsquo;s already requested</a> and upvote the tools you want built.</p><?php endif; ?>
</section>
<?php $TOOL_KEY_ALIASES = ['rolodex' => 'purrsuit', 'jobtrack' => 'corporate-bum-bum']; ?>
<section class="panel" id="activity"><h2 style="margin-top:0">Recent activity</h2>
<?php if (!$activityRows): ?><p class="lede" style="margin:0"><img src="/bum/cat-sleepy-v2.png" alt="Bum Bum napping" class="sticker" style="width:88px;vertical-align:middle;margin-right:10px">Quiet so far. Use any tool and your actions will line up here.</p>
<?php else: ?><ul class="activity">
<?php foreach ($activityRows as $row): $rk = (string) $row['tool_key']; if (isset($TOOL_KEY_ALIASES[$rk])) $rk = $TOOL_KEY_ALIASES[$rk]; $rn = isset($DASHBOARD_TOOLS[$rk]) ? $DASHBOARD_TOOLS[$rk]['name'] : ucfirst($rk); $rc = (int) $row['action_count']; try { $rdt = new DateTime((string) $row['created_at'], new DateTimeZone('UTC')); $rdt->setTimezone($toronto); $rdate = $rdt->format('M j, g:i A'); } catch (Throwable $e) { $rdate = (string) $row['created_at']; } ?>
<li><span><?= htmlspecialchars($rdate) ?></span> · <span><?= htmlspecialchars($rn) ?></span> · <span><?= $rc ?> action<?= $rc === 1 ? '' : 's' ?></span></li>
<?php endforeach; ?></ul><?php endif; ?>
</section>
<script>
async function session(){return fetch('/api/session.php').then(r=>r.json())}
document.querySelector('#billing').onclick=async()=>{const s=await session();const response=await fetch('/api/portal.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({csrf:s.csrf})});const data=await response.json();if(response.ok)location.href=data.url;else document.querySelector('#message').textContent=data.error};
document.querySelector('#logout').onclick=async()=>{const s=await session();await fetch('/api/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'logout',csrf:s.csrf})});location.reload()};
async function loadTeam(){const box=document.querySelector('#team-body');if(!box)return;try{const s=await session();const res=await fetch('/api/team.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'list',csrf:s.csrf})});const t=await res.json();if(!res.ok)throw new Error(t.error||'Could not load team.');const esc=x=>String(x).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
if(t.role==='member'){box.innerHTML=`<p class="lede" style="margin:0"><img src="/bum/cat-yellow-hoodie.png" alt="Bum Bum in a hoodie" class="sticker" style="width:88px;vertical-align:middle;margin-right:10px">You're on <b>${esc(t.owner_email)}</b>'s team. Every action you complete draws from the shared bucket above.</p><p style="margin-top:14px"><button id="leaveTeam" class="button secondary">Leave team</button></p>`;const lv=box.querySelector('#leaveTeam');if(lv)lv.onclick=async()=>{if(lv.dataset.armed!=='1'){lv.dataset.armed='1';lv.dataset.orig=lv.textContent;lv.textContent='Click again to leave';setTimeout(()=>{if(lv.isConnected&&lv.dataset.armed==='1'){lv.dataset.armed='';lv.textContent=lv.dataset.orig}},8000);return}lv.disabled=true;const s2=await session();const r=await fetch('/api/team.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'leave',csrf:s2.csrf})});if(!r.ok){const d=await r.json().catch(()=>({error:'Could not leave the team.'}));lv.disabled=false;lv.dataset.armed='';lv.textContent=lv.dataset.orig||'Leave team';const p=document.createElement('p');p.className='error';p.textContent=d.error||'Could not leave the team.';lv.after(p);return}location.reload()};return}
const seats=t.seats;let html=`<p class="lede" style="margin-top:0">${seats.limit===0?`Your free plan is a solo act. <a href="/pricing/">Helper</a> adds 3 team members, Operator adds 10.`:`<b>${seats.used} of ${seats.limit}</b> seats filled. Everyone shares your action bucket.`}</p>`;
if(seats.limit>0){html+=`<div style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;margin:14px 0"><div style="flex:1;min-width:220px"><label>Teammate's email</label><input id="inviteEmail" type="email" placeholder="them@theirbusiness.com"></div><button id="inviteBtn" style="margin-top:0">Create invite link</button></div><p id="inviteMsg" class="error"></p><p class="lede">Send them the link yourself, by text, DM, or email. It works for anyone who clicks it.</p>`}
if(t.members&&t.members.length){html+='<ul class="activity">'+t.members.map(m=>{const who=esc(m.status==='active'?(m.member_email||m.email):m.email);const tag=m.status==='active'?'on the team':'invited';const link=m.invite_link?` <button class="button secondary" data-copy="${esc(m.invite_link)}" style="margin:6px 0 0">Copy invite link</button>`:'';return `<li><span>${who}</span> · ${tag}${link} <button class="button secondary" data-remove="${m.id}" style="margin:6px 0 0 8px">Remove</button></li>`}).join('')+'</ul>'}else if(seats.limit>0){html+='<p class="lede">No teammates yet. Bum Bum works best with company.</p>'}
html+='<p id="teamMsg" class="error"></p>';
box.innerHTML=html;
const inviteBtn=box.querySelector('#inviteBtn');
if(inviteBtn)inviteBtn.onclick=async()=>{const em=box.querySelector('#inviteEmail').value.trim();const msg=box.querySelector('#inviteMsg');msg.textContent='';if(!em){msg.textContent='Enter an email first.';return}inviteBtn.disabled=true;const s2=await session();const r=await fetch('/api/team.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'invite',email:em,csrf:s2.csrf})});const d=await r.json();inviteBtn.disabled=false;if(!r.ok){msg.textContent=d.error;return}loadTeam();try{await navigator.clipboard.writeText(d.invite_link);msg.textContent='Invite link copied. Send it to '+em+'.';msg.className='';}catch(e){msg.textContent='Invite created. Copy the link from the list below.';msg.className=''}};
box.querySelectorAll('[data-copy]').forEach(b=>b.onclick=async()=>{try{await navigator.clipboard.writeText(b.dataset.copy);b.textContent='Copied!'}catch(e){prompt('Copy this invite link:',b.dataset.copy)}});
box.querySelectorAll('[data-remove]').forEach(b=>b.onclick=async()=>{if(b.dataset.armed!=='1'){b.dataset.armed='1';b.dataset.orig=b.textContent;b.textContent='Click again to remove';setTimeout(()=>{if(b.isConnected&&b.dataset.armed==='1'){b.dataset.armed='';b.textContent=b.dataset.orig}},8000);return}b.disabled=true;const s2=await session();const r=await fetch('/api/team.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'remove',id:parseInt(b.dataset.remove,10),csrf:s2.csrf})});const d=await r.json().catch(()=>({}));if(!r.ok){b.disabled=false;b.dataset.armed='';b.textContent=b.dataset.orig||'Remove';const msg=box.querySelector('#teamMsg');if(msg)msg.textContent=d.error||'Could not remove this teammate.';return}loadTeam()});
}catch(e){box.innerHTML='<p class="lede">Bum Bum tripped over the team roster. Refresh to try again.</p>'}}
loadTeam();
document.querySelectorAll('[data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:a.dataset.context||'account'})));
<?php if ($isOperator): ?>
function countdown(iso){const ms=new Date(iso).getTime()-Date.now();if(ms<=0)return 'past due';const h=Math.floor(ms/36e5),m=Math.floor(ms%36e5/6e4);return h>0?('in '+h+'h '+m+'m'):('in '+m+'m')}
async function loadRequests(){try{const list=await fetch('/api/requests.php').then(r=>r.json());const box=document.querySelector('#req-list');if(!Array.isArray(list)||!list.length){box.innerHTML='<p class="lede">No requests yet. Your first one is on the house this month.</p>';return}
box.innerHTML=list.map(q=>{const status=q.status==='delivered'?'Delivered':(q.status==='overdue_credited'?'Missed the deadline, free month credited':('In progress, due '+countdown(q.deadline_at)));return `<div class="req"><b>${q.title.replace(/</g,'&lt;')}</b><br><span class="due">${status}</span> · requested ${new Date(q.requested_at).toLocaleDateString()}</div>`}).join('');
const open=list.some(q=>q.status==='open');if(open)document.querySelector('#req-form').innerHTML='<p class="lede">Your request for this month is in progress. Bum Bum is on it.</p>'}catch(e){}}
loadRequests();
const reqForm=document.querySelector('#req-form');if(reqForm)reqForm.addEventListener('submit',async event=>{event.preventDefault();const button=reqForm.querySelector('button');button.disabled=true;document.querySelector('#req-error').textContent='';const s=await session();const fields=Object.fromEntries(new FormData(reqForm));const response=await fetch('/api/requests.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...fields,csrf:s.csrf})});const data=await response.json();if(response.ok){bbTrack('request_submitted',{request_id:data.request.id});location.reload()}else{document.querySelector('#req-error').textContent=data.error;button.disabled=false}});
document.querySelectorAll('.tool-card').forEach(a=>a.addEventListener('click',()=>bbTrack('dashboard_tool_opened',{tool:a.dataset.tool})));
const customizeBtn=document.querySelector('#customizeBtn');const toolPicker=document.querySelector('#tool-picker');
if(customizeBtn&&toolPicker)customizeBtn.addEventListener('click',()=>{const hidden=toolPicker.classList.toggle('hidden');customizeBtn.textContent=hidden?'Customize':'Hide';});
const toolSave=document.querySelector('#toolSave');
if(toolSave)toolSave.addEventListener('click',async()=>{const msg=document.querySelector('#toolMsg');msg.textContent='';const keys=[...document.querySelectorAll('#tool-picker input[type="checkbox"]:checked')].map(c=>c.value);if(!keys.length){msg.textContent='Pick at least one tool.';return}toolSave.disabled=true;const s=await session();const response=await fetch('/api/account/tools.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'save',keys:keys,csrf:s.csrf})});const data=await response.json();toolSave.disabled=false;if(!response.ok){msg.textContent=data.error||'Could not save.';return}bbTrack('dashboard_customized',{count:keys.length});location.reload();});
<?php endif; ?>
</script><?php endif; ?></main><?php require dirname(__DIR__)."/includes/site-footer.php"; ?></body></html>
