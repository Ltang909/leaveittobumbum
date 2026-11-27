import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../../components/chrome";

export const metadata: Metadata = {
  alternates: { canonical: "/blog/solopreneur-stack/" },
  openGraph: { url: "/blog/solopreneur-stack/" },
  title: "The Solopreneur Stack: A Working Setup Built on Free Plans | Leave It to Bum Bum",
  description: "A working one-person business setup built on free plans: tools for getting clients, doing the work, getting paid, and staying organized, with each free plan's real limits stated plainly. Prices checked November 23, 2026.",
};

const postingLd = {
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "The Solopreneur Stack: A Working Setup Built on Free Plans",
  "description": "A working one-person business setup built on free plans: tools for getting clients, doing the work, getting paid, and staying organized, with each free plan's real limits stated plainly. Prices checked November 23, 2026.",
  "datePublished": "2026-11-27",
  "dateModified": "2026-11-27",
  "author": {
    "@type": "Organization",
    "name": "LeaveItToBumBum",
    "url": "https://leaveittobumbum.com/"
  },
  "publisher": {
    "@type": "Organization",
    "name": "LeaveItToBumBum",
    "url": "https://leaveittobumbum.com/",
    "logo": {
      "@type": "ImageObject",
      "url": "https://leaveittobumbum.com/bum/favicon-cat.png"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://leaveittobumbum.com/blog/solopreneur-stack/"
  }
};

const breadcrumbLd = {
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://leaveittobumbum.com/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Blog",
      "item": "https://leaveittobumbum.com/blog/"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "The Solopreneur Stack: A Working Setup Built on Free Plans",
      "item": "https://leaveittobumbum.com/blog/solopreneur-stack/"
    }
  ]
};

const itemListLd = {
  "@context": "https://schema.org",
  "@type": "ItemList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "HubSpot CRM", "url": "https://www.hubspot.com/" },
    { "@type": "ListItem", "position": 2, "name": "Mailchimp", "url": "https://mailchimp.com/" },
    { "@type": "ListItem", "position": 3, "name": "Calendly", "url": "https://calendly.com/" },
    { "@type": "ListItem", "position": 4, "name": "Carrd", "url": "https://carrd.co/" },
    { "@type": "ListItem", "position": 5, "name": "Purrsuit", "url": "https://leaveittobumbum.com/tools/purrsuit/" },
    { "@type": "ListItem", "position": 6, "name": "Notion", "url": "https://www.notion.com/" },
    { "@type": "ListItem", "position": 7, "name": "Canva", "url": "https://www.canva.com/" },
    { "@type": "ListItem", "position": 8, "name": "Trello", "url": "https://trello.com/" },
    { "@type": "ListItem", "position": 9, "name": "Toggl Track", "url": "https://toggl.com/" },
    { "@type": "ListItem", "position": 10, "name": "Loom", "url": "https://www.loom.com/" },
    { "@type": "ListItem", "position": 11, "name": "Wave", "url": "https://www.waveapps.com/" },
    { "@type": "ListItem", "position": 12, "name": "Stripe", "url": "https://stripe.com/" },
    { "@type": "ListItem", "position": 13, "name": "Square", "url": "https://squareup.com/" },
    { "@type": "ListItem", "position": 14, "name": "Zoho Invoice", "url": "https://www.zoho.com/invoice/" },
    { "@type": "ListItem", "position": 15, "name": "Invoice Chaser", "url": "https://leaveittobumbum.com/tools/invoice-chaser/" },
    { "@type": "ListItem", "position": 16, "name": "Todoist", "url": "https://todoist.com/" },
    { "@type": "ListItem", "position": 17, "name": "Slack", "url": "https://slack.com/" },
    { "@type": "ListItem", "position": 18, "name": "Dropbox", "url": "https://www.dropbox.com/" },
    { "@type": "ListItem", "position": 19, "name": "Zapier", "url": "https://zapier.com/" },
    { "@type": "ListItem", "position": 20, "name": "Airtable", "url": "https://airtable.com/" }
  ]
};

export default function SolopreneurStack() {
  return (
    <main>
      <SiteHeader />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(postingLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbLd) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(itemListLd) }} />
      <section className="page-head shell blog-head">
        <p className="kicker">Blog</p>
        <h1>The Solopreneur Stack: A Working Setup Built on Free Plans</h1>
        <p className="lede">Published November 27, 2026 · Prices and limits checked November 23, 2026 · By the LeaveItToBumBum team</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <p>Running a one-person business means doing four jobs at once. You find clients. You do the work. You chase the money. You keep the whole thing organized. Most software lists sort tools by category, which is not how a Tuesday actually feels.</p>
          <p>This stack is sorted by job instead. Every product below has a free plan that a working solopreneur can genuinely use, and every entry names the free plan's real limits, because the limit is the part that decides whether a tool stays free for you or quietly becomes a bill.</p>
          <p>Prices and limits were checked on November 23, 2026. Vendors change both without much warning, so treat each number as a snapshot and confirm it on the vendor's pricing page before you commit.</p>
          <h2>How this stack was picked</h2>
          <p>Each product had to pass three tests. It has a free plan, and a free trial alone does not count. The free plan covers real work for one person, with the useful parts left in. And the free plan's limits are published on the vendor's own pricing page, so you can see the wall before you hit it.</p>
          <p>Every price and limit in this article comes from the vendor's official pricing page, read on November 23, 2026. Where a page would not render its numbers, the figure was cross-checked against recent reads of that same vendor page and is marked in our research notes.</p>
          <p>Two tools on this list are ours. <a href="/tools/purrsuit/">Purrsuit</a> and <a href="/tools/invoice-chaser/">Invoice Chaser</a> are built by Leave It To Bum Bum, the site you are reading. They are here because they do jobs this stack needs, they sit next to paid competitors, and their limits are stated as plainly as everyone else's. Judge them by the same standard.</p>
          <h2>Getting clients</h2>
          <h3>HubSpot CRM</h3>
          <p><a href="https://www.hubspot.com/" rel="noopener">HubSpot CRM</a> is a free CRM that tracks contacts, deals, and emails in one place. It is the heavyweight option here, and for one person it is more CRM than most solo businesses need, which is a fine problem to have at zero dollars.</p>
          <p><strong>Price:</strong> Free. Starter starts at $20 per seat per month.</p>
          <p><strong>Free plan limits:</strong> 1,000 contacts, up to 2 users, 2,000 marketing email sends a month, HubSpot branding on forms and emails, and 1 meeting scheduling link.</p>
          <p><strong>Suits:</strong> Solopreneurs whose client list is outgrowing a spreadsheet and who want email tracking from day one.</p>
          <h3>Mailchimp</h3>
          <p><a href="https://mailchimp.com/" rel="noopener">Mailchimp</a> is email marketing with a free tier that still sends real campaigns. The free plan covers a small list well. It stops covering you the moment your list starts to grow.</p>
          <p><strong>Price:</strong> Free. Essentials starts at $13 per month for 500 contacts.</p>
          <p><strong>Free plan limits:</strong> 250 contacts, up to 500 email sends a month with a 250 send daily cap, 1 seat, 1 audience, no automation flows, and Mailchimp branding stays on your emails. Email support lasts only the first 30 days.</p>
          <p><strong>Suits:</strong> A solo business sending a simple newsletter to a small, loyal list.</p>
          <h3>Calendly</h3>
          <p><a href="https://calendly.com/" rel="noopener">Calendly</a> gives you booking links that end the back-and-forth scheduling email chain. Clients pick a time, it lands on your calendar, everyone moves on.</p>
          <p><strong>Price:</strong> Free. Standard is $10 per seat per month billed annually, or $12 monthly.</p>
          <p><strong>Free plan limits:</strong> 1 event type and 1 connected calendar. One-on-one meetings are unlimited, and Calendly branding appears on your booking page.</p>
          <p><strong>Suits:</strong> Anyone who books calls with clients and only needs one kind of meeting. The wall arrives when you need a second meeting type, like a 15 minute intro and a 60 minute working session.</p>
          <h3>Carrd</h3>
          <p><a href="https://carrd.co/" rel="noopener">Carrd</a> builds one-page websites for a landing page, a waitlist, or a simple service page. If your whole web presence is one strong page, Carrd is hard to beat on price.</p>
          <p><strong>Price:</strong> Free. Pro Lite is $9 per year, and Pro Standard is $19 per year.</p>
          <p><strong>Free plan limits:</strong> 3 sites on carrd.co addresses, with the Carrd badge shown. No custom domain, no forms, and no widgets on the free plan.</p>
          <p><strong>Suits:</strong> A first landing page or link page. You will want Pro Standard the day you need your own domain and a contact form, and at $19 a year that is a gentle wall.</p>
          <h3>Purrsuit</h3>
          <p>Our <a href="/tools/purrsuit/">client follow-up tracker</a>. It keeps a simple list of who you owe a reply, a proposal, or a check-in, and nudges you before a warm lead goes cold. Full disclosure: we built it, and it is in this stack because follow-up is the cheapest client-getting work there is.</p>
          <p><strong>Price:</strong> Free, with no usage limits.</p>
          <p><strong>Free plan limits:</strong> None. Every Bum Bum tool is free with no limits, and the only paid offer on the site is a one-time $49 custom tool request.</p>
          <p><strong>Suits:</strong> Solo operators who lose deals in the gap between "great call" and actually following up.</p>
          <h2>Doing the work</h2>
          <h3>Notion</h3>
          <p><a href="https://www.notion.com/" rel="noopener">Notion</a> holds notes, docs, wikis, and light project tracking in one workspace. For a one-person business it often becomes the company memory.</p>
          <p><strong>Price:</strong> Free. Plus is $10 per user per month billed annually, or $12 monthly.</p>
          <p><strong>Free plan limits:</strong> File uploads capped at 5 MB, page history lasts 7 days, up to 10 guests, and a block limit applies once a workspace has more than one member.</p>
          <p><strong>Suits:</strong> Solo operators who think in documents. A single user can run a whole business inside the free plan for a long time.</p>
          <h3>Canva</h3>
          <p><a href="https://www.canva.com/" rel="noopener">Canva</a> is design for people who are not designers. Social posts, proposals, invoices with a logo, simple brand assets.</p>
          <p><strong>Price:</strong> Free. Pro is $144 per year per person, or $18 per month.</p>
          <p><strong>Free plan limits:</strong> 5 GB of storage and 1 brand kit with 3 colors. The free template and asset library is large, though premium elements are paywalled inside it.</p>
          <p><strong>Suits:</strong> Anyone making their own marketing material. The wall is usually storage and brand consistency, not features.</p>
          <h3>Trello</h3>
          <p><a href="https://trello.com/" rel="noopener">Trello</a> uses boards, lists, and cards for tracking work visually. A board per client or per project, cards moving left to right.</p>
          <p><strong>Price:</strong> Free. Standard is $5 per user per month billed annually, or $6 monthly.</p>
          <p><strong>Free plan limits:</strong> Up to 10 boards per Workspace and up to 10 collaborators per Workspace, unlimited cards, 250 automation runs a month, and file attachments up to 10 MB each.</p>
          <p><strong>Suits:</strong> Visual thinkers managing a handful of active projects. Ten boards sounds like plenty until every client wants their own.</p>
          <h3>Toggl Track</h3>
          <p><a href="https://toggl.com/" rel="noopener">Toggl Track</a> is time tracking with a timer you will actually remember to start. Useful for billing by the hour and for finding out where your week went.</p>
          <p><strong>Price:</strong> Free. Starter is $9 per user per month billed annually, or $12 monthly.</p>
          <p><strong>Free plan limits:</strong> Up to 5 users, with unlimited time tracking and unlimited projects. The free plan has no billable rates, so it tracks hours without turning them into money.</p>
          <p><strong>Suits:</strong> Freelancers and consultants who bill time, or who want honest data about their own workload before they price a package.</p>
          <h3>Loom</h3>
          <p><a href="https://www.loom.com/" rel="noopener">Loom</a> is short recorded videos instead of meetings and long emails. Record your screen, send the link, skip the call.</p>
          <p><strong>Price:</strong> Free on the Starter plan. Business is $18 per user per month.</p>
          <p><strong>Free plan limits:</strong> 25 videos per person, each up to 5 minutes, recorded at up to 720p, for workspaces of up to 50 members.</p>
          <p><strong>Suits:</strong> Anyone explaining work to clients across time zones. Five minutes is the wall most people feel first, and it quietly teaches you to be brief.</p>
          <h2>Getting paid</h2>
          <h3>Wave</h3>
          <p><a href="https://www.waveapps.com/" rel="noopener">Wave</a> is invoicing and bookkeeping built for very small businesses, mostly in North America. The accounting side being free is the draw.</p>
          <p><strong>Price:</strong> The Starter plan is free. Pro is $19 USD per month per business.</p>
          <p><strong>Free plan limits:</strong> Unlimited invoices, estimates, and bookkeeping records. Card payments cost 2.9% plus $0.60 per transaction, or 3.4% plus $0.60 for Amex. Automatic bank imports, automated late payment reminders, and receipt capture sit on the paid Pro plan.</p>
          <p><strong>Suits:</strong> Solo businesses that want invoices and books in one free place and can live without automatic bank feeds.</p>
          <h3>Stripe</h3>
          <p><a href="https://stripe.com/" rel="noopener">Stripe</a> is the payment plumbing behind a large share of the internet. You use it through your website, your invoicing tool, or a payment link.</p>
          <p><strong>Price:</strong> No setup or monthly fee. You pay 2.9% plus $0.30 per successful domestic online card charge in the US.</p>
          <p><strong>Free plan limits:</strong> There is no free plan in the usual sense. There is also no subscription. The cost is the per-transaction fee, and it applies from your first sale.</p>
          <p><strong>Suits:</strong> Anyone selling online who wants payment links and checkout that just work, and who is comfortable with a developer-friendly tool.</p>
          <h3>Square</h3>
          <p><a href="https://squareup.com/" rel="noopener">Square</a> puts payments, invoicing, a point of sale, and a simple online site under one account. Built for people who sell in person as well as online.</p>
          <p><strong>Price:</strong> Free at $0 per month per location. Plus is $49 per month per location.</p>
          <p><strong>Free plan limits:</strong> Invoicing, point of sale, an online site, and booking are included. Processing costs 2.6% plus 15 cents in person and 3.3% plus 30 cents online.</p>
          <p><strong>Suits:</strong> Makers, market sellers, and service providers who take cards face to face and want invoices from the same account.</p>
          <h3>Zoho Invoice</h3>
          <p><a href="https://www.zoho.com/invoice/" rel="noopener">Zoho Invoice</a> is invoicing that is completely free, with no paid tier at all. That pricing model is rare enough to be the headline.</p>
          <p><strong>Price:</strong> Free. There is no paid plan.</p>
          <p><strong>Free plan limits:</strong> Up to 2 users, up to 3 projects, and up to 500 invoices a year, with "Powered by Zoho Invoice" branding on your invoices. Your data is deleted after more than 180 days of inactivity.</p>
          <p><strong>Suits:</strong> Low-volume invoicers who send a few invoices a week and want zero software cost. Five hundred invoices a year is generous for one person, and the inactivity rule is the one to respect.</p>
          <h3>Invoice Chaser</h3>
          <p>Our <a href="/tools/invoice-chaser/">overdue invoice reminder tool</a>. You add the invoice details, and it drafts and sends polite, firm payment reminders so the awkward email is not sitting in your drafts for a week. Full disclosure again: this one is ours.</p>
          <p><strong>Price:</strong> Free, with no usage limits.</p>
          <p><strong>Free plan limits:</strong> None. Same deal as every Bum Bum tool, free with no limits, and no subscription exists to upgrade into.</p>
          <p><strong>Suits:</strong> Anyone whose invoices get paid late because the follow-up email never got sent. It pairs with whatever invoicing tool you already use.</p>
          <h2>Staying organized</h2>
          <h3>Todoist</h3>
          <p><a href="https://todoist.com/" rel="noopener">Todoist</a> is a to-do list that stays fast when your list gets long. Capture a task in a sentence, give it a date, move on.</p>
          <p><strong>Price:</strong> Free on the Beginner plan. Pro is $5 per month billed yearly.</p>
          <p><strong>Free plan limits:</strong> 5 personal projects, 3 filter views, 1 week of activity history, and file uploads up to 5 MB.</p>
          <p><strong>Suits:</strong> One person juggling client work and admin. Five projects is the wall, and most solo operators sort work by client, so it arrives sooner than expected.</p>
          <h3>Slack</h3>
          <p><a href="https://slack.com/" rel="noopener">Slack</a> is messaging for when your business involves other people's workspaces, communities, or a contractor or two. Many solopreneurs live in Slack as a guest in client workspaces rather than running their own.</p>
          <p><strong>Price:</strong> Free. Pro is $7.25 per user per month billed annually, or $8.75 monthly.</p>
          <p><strong>Free plan limits:</strong> Message history lasts 90 days, up to 10 app integrations, and huddles are one-on-one only. Data in a free workspace is deleted after 1 year.</p>
          <p><strong>Suits:</strong> Solo operators coordinating with clients and collaborators in chat. The 90 day history is the wall, because the decision you need is always in a message from four months ago.</p>
          <h3>Dropbox</h3>
          <p><a href="https://www.dropbox.com/" rel="noopener">Dropbox</a> is file storage and sharing that clients already know how to open. The free tier is a starting point, not a filing cabinet.</p>
          <p><strong>Price:</strong> The Basic plan is free. Plus is $9.99 per month.</p>
          <p><strong>Free plan limits:</strong> 2 GB of storage.</p>
          <p><strong>Suits:</strong> Sharing a few deliverables at a time. Two gigabytes disappears fast once video files and client assets pile up, and this is often the first free plan a growing solo business outgrows.</p>
          <h3>Zapier</h3>
          <p><a href="https://zapier.com/" rel="noopener">Zapier</a> is automation that connects your other tools. A new form entry becomes a spreadsheet row, a new client becomes a CRM contact, without you copying anything by hand.</p>
          <p><strong>Price:</strong> Free. Professional starts at $19.99 per month.</p>
          <p><strong>Free plan limits:</strong> 100 tasks a month, Zaps can only have two steps, and the plan covers a single user.</p>
          <p><strong>Suits:</strong> Anyone with one or two repetitive hand-offs between tools. A hundred tasks sounds like a lot until one busy form eats them in a week.</p>
          <h3>Airtable</h3>
          <p><a href="https://airtable.com/" rel="noopener">Airtable</a> is a spreadsheet that behaves like a database. Handy for content calendars, client pipelines, inventory, and any list that needs filtering and views.</p>
          <p><strong>Price:</strong> Free. Team is $20 per user per month billed annually.</p>
          <p><strong>Free plan limits:</strong> 1,000 records per base, 1 GB of attachment storage per base, 100 automation runs a month, and up to 5 editors per workspace.</p>
          <p><strong>Suits:</strong> List-heavy businesses that outgrow a plain spreadsheet. One thousand records per base is workable for a pipeline and tight for an archive.</p>
          <h2>What the free stack cannot do</h2>
          <p>A free stack runs a real one-person business. It also has edges, and it helps to know them before you lean on it.</p>
          <p>Free plans do not grow with a team. The moment a second or third person needs a seat, per-user pricing starts and the free math changes completely. Several limits above are per workspace or per base, and they bind faster with every collaborator you add.</p>
          <p>Free plans keep your history on a leash. Ninety days of messages in Slack, seven days of page history in Notion, data deleted after a year of quiet in one tool and after 180 days of inactivity in another. If your records matter for taxes or disputes, export them on a schedule.</p>
          <p>Free plans rarely include the automation that saves the most time. Bank feeds, late payment reminders, multi-step automations, and billable rates tend to sit one tier up. The pattern is consistent: the free plan does the job, the paid plan removes the chores around the job.</p>
          <p>Free plans offer thin support. One product above gives free users email support for 30 days and then stops. When something breaks at a bad moment, you are mostly on your own with a help center.</p>
          <p>And a free plan lasts only as long as the vendor keeps offering it. Vendors shrink free plans, add caps, and retire tiers. Build your stack so any single tool can be swapped without taking the whole business down with it.</p>
          <p>The practical move is simple. Start free, on purpose, with the limits written down. Pay for the first tool whose wall you hit every week, not the one with the loudest upgrade banner. That is how a stack stays cheap and keeps working.</p>
          <p>Working through one category at a time instead? We compared <a href="/blog/best-free-crm-small-business/">free CRM software</a>, <a href="/blog/best-free-client-follow-up-tracker/">client follow-up trackers</a>, <a href="/blog/best-small-business-software/">small business software</a> and <a href="/blog/best-business-account-software/">business account software</a> in separate guides.</p>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
