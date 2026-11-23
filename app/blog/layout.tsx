// Blog-wide PostHog loader. The PHP tool pages get PostHog from
// hostinger/public_html/includes/analytics.php; the static blog pages have
// no PHP, so before this layout existed they shipped with no PostHog at all
// and recorded zero pageviews. This layout emits the same snippet for
// /blog/ routes only: the same loader stub, the same public project key
// and api_host (the key is the project's public token; it is printed into
// every served tool page), the same init with default $pageview capture,
// the same env register (the PHP side checks isStagingHost(); here the
// hostname decides, exactly like app/lib/track.ts), and the same
// bbTrack/bbIdentify wrappers.

const posthogSnippet = `
!function(t,e){var o,n,p,r;e.__SV||(window.posthog=e,e._i=[],e.init=function(i,s,a){function g(t,e){var o=e.split(".");2==o.length&&(t=t[o[0]],e=o[1]),t[o]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}}(p=t.createElement("script")).type="text/javascript",p.crossOrigin="anonymous",p.async=!0,p.src=s.api_host.replace(".i.posthog.com","-assets.i.posthog.com")+"/static/array.js",(r=t.getElementsByTagName("script")[0]).parentNode.insertBefore(p,r);var u=e;for(void 0!==a?u=e[a]=[]:a="posthog",u.people=u.people||[],u.toString=function(t){var e="posthog";return"posthog"!==a&&(e+="."+a),t||(e+=" (stub)"),e},u.people.toString=function(){return u.toString(1)+".people"},o="init capture register register_once register_for_session unregister unregister_for_session getFeatureFlag getFeatureFlags hasFeatureFlag getFeatureFlagPayload isFeatureEnabled reloadFeatureFlags updateEarlyAccessFeatureEnrollment getEarlyAccessFeatures on onFeatureFlags onSurveysLoaded onSessionId getSurveys getActiveMatchingSurveys renderSurvey canRenderSurvey identify setPersonProperties setPersonPropertiesForFlags resetGroups setGroup getGroup getGroups resetGroup addGroup removeGroup trackPageView trackLinks trackFormSubmissions getProperty".split(" "),n=0;n<o.length;n++)g(u,o[n]);e._i.push([i,s,a])},e.__SV=1)}(document,window.posthog||[]);
posthog.init("phc_sKd9PxBNJ2WvbEzjkErxoSaaoBCikwiSHFm4xSyjnF9r",{api_host:"https://us.i.posthog.com"});
posthog.register({env:window.location.hostname.startsWith("staging.")?"staging":"production"});
function bbTrack(event,props){try{if(window.posthog&&posthog.capture)posthog.capture(event,props||{})}catch(e){}}
function bbIdentify(id){try{if(window.posthog&&posthog.identify)posthog.identify(id)}catch(e){}}
`;

export default function BlogLayout({
  children,
}: Readonly<{ children: React.ReactNode }>) {
  return (
    <>
      <script dangerouslySetInnerHTML={{ __html: posthogSnippet }} />
      {children}
    </>
  );
}
