<?php
// Shared PostHog snippet. Emits nothing unless a posthog key is configured.
// Requires api/_bootstrap.php (for config()).
$__ph = config()['posthog'] ?? [];
$__phKey = (string) ($__ph['key'] ?? '');
$__phHost = rtrim((string) ($__ph['host'] ?? 'https://us.i.posthog.com'), '/');
if ($__phKey !== '' && $__phKey !== 'phc_replace_me') :
?>
<script>
!function(t,e){var o,n,p,r;e.__SV||(window.posthog=e,e._i=[],e.init=function(i,s,a){function g(t,e){var o=e.split(".");2==o.length&&(t=t[o[0]],e=o[1]),t[o]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}}(p=t.createElement("script")).type="text/javascript",p.crossOrigin="anonymous",p.async=!0,p.src=s.api_host.replace(".i.posthog.com","-assets.i.posthog.com")+"/static/array.js",(r=t.getElementsByTagName("script")[0]).parentNode.insertBefore(p,r);var u=e;for(void 0!==a?u=e[a]=[]:a="posthog",u.people=u.people||[],u.toString=function(t){var e="posthog";return"posthog"!==a&&(e+="."+a),t||(e+=" (stub)"),e},u.people.toString=function(){return u.toString(1)+".people"},o="init capture register register_once register_for_session unregister unregister_for_session getFeatureFlag getFeatureFlags hasFeatureFlag getFeatureFlagPayload isFeatureEnabled reloadFeatureFlags updateEarlyAccessFeatureEnrollment getEarlyAccessFeatures on onFeatureFlags onSurveysLoaded onSessionId getSurveys getActiveMatchingSurveys renderSurvey canRenderSurvey identify setPersonProperties setPersonPropertiesForFlags resetGroups setGroup getGroup getGroups resetGroup addGroup removeGroup trackPageView trackLinks trackFormSubmissions getProperty".split(" "),n=0;n<o.length;n++)g(u,o[n]);e._i.push([i,s,a])},e.__SV=1)}(document,window.posthog||[]);
posthog.init(<?= json_encode($__phKey) ?>,{api_host:<?= json_encode($__phHost) ?>});
posthog.register({env:<?= json_encode(isStagingHost() ? 'staging' : 'production') ?>});
function bbTrack(event,props){try{if(window.posthog&&posthog.capture)posthog.capture(event,props||{})}catch(e){}}
function bbIdentify(id){try{if(window.posthog&&posthog.identify)posthog.identify(id)}catch(e){}}
</script>
<?php else: ?>
<script>
function bbTrack(){}
function bbIdentify(){}
</script>
<?php endif; unset($__ph, $__phKey, $__phHost); ?>
