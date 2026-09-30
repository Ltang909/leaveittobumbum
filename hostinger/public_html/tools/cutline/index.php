<?php require dirname(__DIR__, 2) . '/api/_bootstrap.php'; $subject = pageSubject(); $user = $subject['kind'] === 'user' ? $subject['user'] : null; $isGuest = $subject['kind'] === 'guest'; $guestId = $isGuest ? $subject['guest_id'] : null; $usage = $subject['kind'] === 'none' ? null : subjectUsage($subject); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="format-detection" content="telephone=no"><title>Cutline | Leave It to Bum Bum</title>
<meta name="description" content="Every subscription you forgot about, in one place. Get nudged before each renewal so free trials stop billing you. Free to try, no account needed.">
<meta property="og:title" content="Cutline | Leave It to Bum Bum">
<meta property="og:description" content="Every subscription you forgot about, in one place. Get nudged before each renewal so free trials stop billing you. Free to try, no account needed.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://leaveittobumbum.com/tools/cutline/">
<meta property="og:image" content="https://leaveittobumbum.com/bum/favicon-cat.png">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Cutline | Leave It to Bum Bum">
<meta name="twitter:description" content="Every subscription you forgot about, in one place. Get nudged before each renewal so free trials stop billing you. Free to try, no account needed.">
<link rel="canonical" href="https://leaveittobumbum.com/tools/cutline/">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Cutline",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  },
  "description": "Every subscription you forgot about, in one place. Get nudged before each renewal so free trials stop billing you. Free to try, no account needed.",
  "url": "https://leaveittobumbum.com/tools/cutline/"
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Is Cutline free?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You get 15 free actions with no signup. Create a free account and you get 75 actions every month. See pricing: https://leaveittobumbum.com/pricing/. Adding a subscription uses one action."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need an account?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Start as a guest. A free account gets 75 actions every month and keeps your list saved across devices. See pricing: https://leaveittobumbum.com/pricing/."
      }
    },
    {
      "@type": "Question",
      "name": "How do renewal reminders work?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Add a subscription with its renewal date and Cutline nudges you before each one renews, so the free trial trap stops working on you."
      }
    },
    {
      "@type": "Question",
      "name": "Can Cutline cancel subscriptions for me?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Cutline shows you everything in one place and reminds you before renewals, but you cancel with the provider directly."
      }
    },
    {
      "@type": "Question",
      "name": "What counts as an action?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Adding a subscription uses one action. Viewing your list and getting nudges are free."
      }
    }
  ]
}
</script><link rel="stylesheet" href="/app.css?v=6"><link rel="icon" href="/bum/favicon-cat.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..900&family=Nunito+Sans:wght@400;700;800;900&display=swap" rel="stylesheet"><style>h1{font-family:Fraunces,Georgia,serif;font-weight:650;line-height:.98;letter-spacing:-.045em;margin:0 0 20px;font-size:clamp(2rem,5.2vw,4.5rem);max-width:none}.lede{max-width:none}details{border-top:1px solid var(--line);padding:10px 0}details:last-child{border-bottom:1px solid var(--line)}summary{font-weight:800;cursor:pointer;list-style:none}summary::-webkit-details-marker{display:none}summary::before{content:"+ ";color:var(--accent,#b3541e)}details[open] summary::before{content:"- "}</style><?php require dirname(__DIR__, 2) . '/includes/analytics.php'; ?></head><body><?php $showMeter = true; require dirname(__DIR__, 2) . '/includes/site-header.php'; ?><main class="shell"><?php $crumbTrail=[["label"=>"Toolbox","url"=>"/tools/"],["label"=>"Cutline"]]; require dirname(__DIR__,2)."/includes/breadcrumbs.php"; ?><p class="eyebrow">Bum Bum&rsquo;s toolbox</p><img class="tool-mascot-page" src="/bum/cat-bowtie.png" alt="Bum Bum judging your subscriptions"><h1>Cut the subscriptions you forgot about.</h1><p class="lede">Every subscription you forgot about, in one place. Bum Bum will even nudge you before each one renews. Adding one uses one action.</p>
<?php if (!$user && !$isGuest): ?><section class="panel"><h2>Sign in to use Cutline</h2><a class="button" href="/account/?next=<?= urlencode('/tools/cutline/') ?>">Sign in or create an account</a></section><?php elseif ($isGuest && $usage && (int) $usage['remaining'] <= 0): ?>
<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) $usage['limit'] ?> free actions. <a href="/account/?next=<?= urlencode('/tools/cutline/') ?>">Create a free account</a> to keep going.</p><p><a class="button" href="/account/?next=<?= urlencode('/tools/cutline/') ?>">Create a free account</a></p></div>
<?php else: ?>
<?php if ($isGuest && $usage): ?><div class="nudge">No account needed. You have <strong><?= (int) $usage['remaining'] ?> of <?= (int) $usage['limit'] ?></strong> free actions. <a href="/account/?next=<?= urlencode('/tools/cutline/') ?>">Create a free account</a> to keep going.</div><?php endif; ?>
<?php $low = !$isGuest && $usage && $usage['remaining'] > 0 && $usage['remaining'] <= (int) ceil($usage['limit'] * 0.2); ?>
<?php if ($low): ?><div class="nudge">Heads up: only <?= (int) $usage['remaining'] ?> free actions left this month. <a href="/account/#upgrade">Get more actions</a> before they run out.</div><?php endif; ?>
<section class="stat" id="total-card"><span>Estimated monthly spend</span><b id="total-month">Loading...</b><p id="total-sub"></p></section>
<section class="panel" id="add-panel"><h2 style="margin-top:0">Add a subscription</h2><form id="add-form"><label>Name</label><input name="custom_name" type="text" maxlength="191" required placeholder="Netflix"><div class="grid"><div><label>Price (per billing period)</label><input name="price" type="number" min="0" step="0.01" required placeholder="15.49"></div><div><label>Billing cadence</label><select name="cadence" style="width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff"><option value="weekly">Weekly</option><option value="monthly" selected>Monthly</option><option value="quarterly">Quarterly</option><option value="semiannual">Every 6 months</option><option value="annual">Yearly</option></select></div></div><div class="grid"><div><label>Category</label><select name="category" style="width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff"><option value="streaming">Streaming</option><option value="music">Music</option><option value="software">Software &amp; SaaS</option><option value="cloud_storage">Cloud storage</option><option value="fitness">Fitness</option><option value="reading">News &amp; reading</option><option value="gaming">Gaming</option><option value="food_delivery">Food delivery</option><option value="other" selected>Other</option></select></div><div><label>Currency</label><select name="currency" style="width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff"><option value="USD" selected>USD</option><option value="CAD">CAD</option></select></div></div><label>Next renewal date</label><input name="next_renewal_on" type="date" required><label style="display:flex;align-items:center;gap:10px;font-weight:800"><input name="is_free_trial" type="checkbox" value="1" style="width:auto"> This is a free trial</label><div id="trial-row" class="hidden"><label>Trial ends</label><input name="trial_ends_on" type="date"></div><button>Add it</button><p id="add-error" class="error"></p></form></section>
<h2>Your subscriptions</h2><div id="sub-list"><p class="lede">Loading...</p></div>
<section class="panel"><h2 style="margin-top:0">Reminders</h2><p class="lede">Bum Bum emails you before things renew so you can keep the good ones and cut the rest.</p><form id="prefs-form"><label>Remind me this many days before each renewal</label><div style="display:flex;gap:12px;align-items:end"><input name="notify_days_before" type="number" min="0" max="30" step="1" value="3" style="max-width:140px"><button type="submit" style="margin-top:0">Save</button></div><p id="prefs-note" class="lede"></p></form></section>
<div id="upgrade-slot"></div>
<script>
const TOOL_KEY='cutline';
const PERIOD=<?= json_encode($usage['period'] ?? '') ?>;
if(<?= $low ? 'true' : 'false' ?>){const seen='bb_m80_'+PERIOD;if(!localStorage.getItem(seen)){localStorage.setItem(seen,'1');bbTrack('usage_milestone_80',{tool:TOOL_KEY,used:<?= (int) ($usage['used'] ?? 0) ?>,limit:<?= (int) ($usage['limit'] ?? 0) ?>})}}
function upgradeCard(){return `<div class="upgrade-card"><h2>Out of free actions.</h2><p class="lede">You used all <?= (int) ($usage['limit'] ?? 75) ?> free actions this month. Helper gives you 1,500 actions for $12/month. Operator gives you 6,000 actions plus a custom tool built for you in 36 hours for $49/month.</p><p><a class="button" data-plan="helper" href="/checkout/?plan=helper">Get Helper, $12/mo</a> <a class="button secondary" data-plan="operator" href="/checkout/?plan=operator">Get Operator, $49/mo</a></p><p><a href="/account/">See your usage</a></p></div>`}
const CATEGORY_LABELS={streaming:'Streaming',music:'Music',software:'Software & SaaS',cloud_storage:'Cloud storage',fitness:'Fitness',reading:'News & reading',gaming:'Gaming',food_delivery:'Food delivery',other:'Other'};
const CADENCE_LABELS={weekly:'week',monthly:'month',quarterly:'quarter',semiannual:'6 months',annual:'year'};
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
function money(cents,currency){try{return new Intl.NumberFormat(undefined,{style:'currency',currency:currency||'USD'}).format(cents/100)}catch(e){return (cents/100).toFixed(2)+' '+(currency||'USD')}}
function pill(d){if(d<0)return'Overdue';if(d===0)return'Renews today';if(d===1)return'Renews tomorrow';return'In '+d+' days'}
async function callApi(payload){const session=await fetch('/api/session.php').then(r=>r.json());const response=await fetch('/api/tools/cutline.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...payload,csrf:session.csrf})});let data={};try{data=await response.json()}catch(e){}return{response,data}}
function fieldRow(sub,prefix){const t=sub.trial_ends_on||'';return `<label>Name</label><input name="custom_name" type="text" maxlength="191" required value="${esc(sub.custom_name)}"><div class="grid"><div><label>Price (per billing period)</label><input name="price" type="number" min="0" step="0.01" required value="${(sub.price_cents/100).toFixed(2)}"></div><div><label>Billing cadence</label><select name="cadence" style="width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff">${Object.keys(CADENCE_LABELS).map(c=>`<option value="${c}"${c===sub.cadence?' selected':''}>${c==='weekly'?'Weekly':c==='monthly'?'Monthly':c==='quarterly'?'Quarterly':c==='semiannual'?'Every 6 months':'Yearly'}</option>`).join('')}</select></div></div><div class="grid"><div><label>Category</label><select name="category" style="width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff">${Object.keys(CATEGORY_LABELS).map(c=>`<option value="${c}"${c===sub.category?' selected':''}>${esc(CATEGORY_LABELS[c])}</option>`).join('')}</select></div><div><label>Currency</label><select name="currency" style="width:100%;padding:14px;border:2px solid var(--line);border-radius:10px;font:inherit;background:#fff"><option value="USD"${sub.currency==='USD'?' selected':''}>USD</option><option value="CAD"${sub.currency==='CAD'?' selected':''}>CAD</option></select></div></div><label>Next renewal date</label><input name="next_renewal_on" type="date" required value="${esc(sub.next_renewal_on)}"><label style="display:flex;align-items:center;gap:10px;font-weight:800"><input name="is_free_trial" type="checkbox" value="1"${sub.is_free_trial?' checked':''} style="width:auto"> This is a free trial</label><div class="trial-row${sub.is_free_trial?'':' hidden'}"><label>Trial ends</label><input name="trial_ends_on" type="date" value="${esc(t)}"></div>`}
function rowHtml(sub){const trial=sub.is_free_trial?` <span style="border:2px solid var(--line);border-radius:999px;padding:2px 10px;font-size:13px;font-weight:800;background:#fff">Free trial</span>`:'';return `<div class="panel" style="margin-top:16px" data-id="${sub.id}"><div style="display:flex;justify-content:space-between;gap:12px;align-items:start;flex-wrap:wrap"><div><div style="font-weight:900;font-size:20px">${esc(sub.custom_name)}${trial}</div><div style="color:var(--muted);margin-top:6px">${esc(CATEGORY_LABELS[sub.category]||sub.category)} &middot; ${money(sub.price_cents,sub.currency)} / ${esc(CADENCE_LABELS[sub.cadence]||sub.cadence)} &middot; renews ${esc(sub.next_renewal_on)}</div></div><div style="font-weight:800;white-space:nowrap">${esc(pill(sub.days_until))}</div></div><p style="margin:12px 0 0"><button class="secondary" data-edit style="margin-top:0">Edit</button> <button class="secondary" data-delete style="margin-top:0">Delete</button></p><form class="edit-form hidden" style="margin-top:16px;border-top:2px solid var(--line);padding-top:16px">${fieldRow(sub)}<p><button type="submit" style="margin-top:8px">Save changes</button> <button type="button" class="secondary" data-cancel style="margin-top:8px">Cancel</button></p><p class="error"></p></form></div>`}
function render(data){const subs=data.subscriptions||[];document.querySelector('#total-month').textContent=money(data.monthly_total_cents||0,'USD');document.querySelector('#total-sub').textContent=subs.length===1?'across 1 subscription':'across '+subs.length+' subscriptions';const list=document.querySelector('#sub-list');if(!subs.length){list.innerHTML='<div class="panel"><p class="lede" style="margin:0">Nothing tracked yet. Add your first subscription above and Bum Bum will keep an eye on it.</p></div>'}else{list.innerHTML=subs.map(rowHtml).join('')}const daysInput=document.querySelector('#prefs-form input[name=notify_days_before]');if(daysInput&&data.prefs)daysInput.value=data.prefs.notify_days_before}
async function load(){const{response,data}=await callApi({action:'list'});if(!response.ok){document.querySelector('#sub-list').innerHTML='<p class="error">'+esc(data.error||'Could not load your subscriptions.')+'</p>';return}render(data)}
function formFields(form){const fd=new FormData(form);const price=parseFloat(fd.get('price'));return{custom_name:String(fd.get('custom_name')||'').trim(),category:String(fd.get('category')||'other'),tier_name:'',price_cents:Number.isFinite(price)?Math.round(price*100):NaN,currency:String(fd.get('currency')||'USD'),cadence:String(fd.get('cadence')||'monthly'),started_on:new Date().toISOString().slice(0,10),next_renewal_on:String(fd.get('next_renewal_on')||''),is_free_trial:fd.get('is_free_trial')?'1':'',trial_ends_on:String(fd.get('trial_ends_on')||'')}}
let attempt=crypto.randomUUID();
document.querySelector('#add-form').addEventListener('submit',async event=>{event.preventDefault();const form=event.target;const button=form.querySelector('button[type=submit],button:not([type])')||form.querySelector('button');button.disabled=true;document.querySelector('#add-error').textContent='';document.querySelector('#upgrade-slot').innerHTML='';const{response,data}=await callApi({action:'add',...formFields(form),idempotencyKey:attempt});button.disabled=false;if(!response.ok){if(response.status===402){bbTrack('limit_reached',{tool:TOOL_KEY});bbTrack('upgrade_prompt_shown',{tool:TOOL_KEY,context:'limit'});document.querySelector('#upgrade-slot').innerHTML=upgradeCard();document.querySelectorAll('#upgrade-slot [data-plan]').forEach(a=>a.addEventListener('click',()=>bbTrack('upgrade_clicked',{plan:a.dataset.plan,context:'limit',tool:TOOL_KEY})))}else{document.querySelector('#add-error').textContent=data.error||'Something went wrong.'}return}attempt=crypto.randomUUID();bbTrack('action_completed',{tool:TOOL_KEY,used:data.usage.used,limit:data.usage.limit,remaining:data.usage.remaining});form.reset();document.querySelector('#trial-row').classList.add('hidden');load()});
document.querySelector('#add-form input[name=is_free_trial]').addEventListener('change',e=>{document.querySelector('#trial-row').classList.toggle('hidden',!e.target.checked)});
document.querySelector('#sub-list').addEventListener('click',async event=>{const btn=event.target.closest('[data-edit],[data-delete],[data-cancel]');if(!btn)return;const card=event.target.closest('[data-id]');const id=card.dataset.id;const editForm=card.querySelector('.edit-form');if(btn.hasAttribute('data-edit')){editForm.classList.toggle('hidden')}else if(btn.hasAttribute('data-cancel')){editForm.classList.add('hidden')}else if(btn.hasAttribute('data-delete')){const name=card.querySelector('div[style*="font-weight:900"]')?.textContent||'this subscription';if(!confirm('Delete "'+name.trim()+'"? This cannot be undone.'))return;const{response,data}=await callApi({action:'delete',id});if(!response.ok){alert(data.error||'Could not delete.');return}load()}});
document.querySelector('#sub-list').addEventListener('change',event=>{if(event.target.name==='is_free_trial'){const row=event.target.closest('.edit-form')?.querySelector('.trial-row');if(row)row.classList.toggle('hidden',!event.target.checked)}});
document.querySelector('#sub-list').addEventListener('submit',async event=>{const form=event.target;if(!form.classList.contains('edit-form'))return;event.preventDefault();const card=form.closest('[data-id]');const id=card.dataset.id;const button=form.querySelector('button[type=submit]');button.disabled=true;form.querySelector('.error').textContent='';const{response,data}=await callApi({action:'update',id,...formFields(form)});button.disabled=false;if(!response.ok){form.querySelector('.error').textContent=data.error||'Could not save.';return}load()});
document.querySelector('#prefs-form').addEventListener('submit',async event=>{event.preventDefault();const note=document.querySelector('#prefs-note');note.textContent='Saving...';const days=parseInt(event.target.notify_days_before.value,10);const{response,data}=await callApi({action:'prefs',notify_days_before:days});if(!response.ok){note.textContent=data.error||'Could not save.';return}note.textContent='Saved. Bum Bum will email you '+data.prefs.notify_days_before+' days before each renewal.'});
load();
</script><?php endif; ?><section class="panel" id="faq" aria-label="Frequently asked questions">
<h2 style="margin-top:0">Questions, answered</h2>
<details>
<summary>Is Cutline free?</summary>
<p>You get 15 free actions with no signup. Create a free account and you get 75 actions every month. <a href="/pricing/">See pricing</a>. Adding a subscription uses one action.</p>
</details>
<details>
<summary>Do I need an account?</summary>
<p>No. Start as a guest. A free account gets 75 actions every month and keeps your list saved across devices. <a href="/pricing/">See pricing</a>.</p>
</details>
<details>
<summary>How do renewal reminders work?</summary>
<p>Add a subscription with its renewal date and Cutline nudges you before each one renews, so the free trial trap stops working on you.</p>
</details>
<details>
<summary>Can Cutline cancel subscriptions for me?</summary>
<p>No. Cutline shows you everything in one place and reminds you before renewals, but you cancel with the provider directly.</p>
</details>
<details>
<summary>What counts as an action?</summary>
<p>Adding a subscription uses one action. Viewing your list and getting nudges are free.</p>
</details>
</section>
</main><?php require dirname(__DIR__, 2)."/includes/site-footer.php"; ?></body></html>
