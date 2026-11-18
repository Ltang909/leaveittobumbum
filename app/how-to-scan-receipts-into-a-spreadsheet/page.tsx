import type { Metadata } from "next";
import { SiteHeader, SiteFooter } from "../components/chrome";
import ToolCallout from "../components/tool-callout";

export const metadata: Metadata = {
  alternates: { canonical: "/how-to-scan-receipts-into-a-spreadsheet/" },
  openGraph: { url: "/how-to-scan-receipts-into-a-spreadsheet/" },
  title: "How to scan receipts into a spreadsheet | Leave It to Bum Bum",
  description: "Turn a shoebox of receipts into a clean spreadsheet: the fields worth capturing, photo tips for clean scans, and how to get the CSV into Excel or Google Sheets.",
};

export default function ScanReceiptsGuide() {
  return (
    <main>
      <SiteHeader />
      <section className="page-head shell blog-head">
        <p className="kicker">Guide</p>
        <h1>How to scan receipts into a spreadsheet</h1>
        <p className="lede">Receipts fade, get lost, and pile up in shoeboxes. Scanned into a spreadsheet, they turn into records your accountant will actually thank you for.</p>
      </section>
      <section className="shell">
        <div className="prose blog-wide">
          <ToolCallout
            variant="band"
            name="Receipt Scanner"
            href="/tools/receipt-reader/"
            heading="Just want your receipts in a spreadsheet?"
            blurb="Receipt Scanner reads your receipt photos and pulls the vendor, date, line items, tax, and total into a clean table. Free, with no limits."
            cta="Open Receipt Scanner"
          />

          <h2>What to do</h2>

          <h3>Capture the fields that matter</h3>
          <ul>
          <li><strong>Vendor.</strong> The shop or supplier name, spelled the way it appears on the receipt.</li>
          <li><strong>Date.</strong> The purchase date, not the day you finally scanned it.</li>
          <li><strong>Line items.</strong> What you actually bought. "Office supplies" is weak. "Printer paper, ink cartridge" holds up when someone asks.</li>
          <li><strong>Tax.</strong> The tax amount on its own line of data. You may be able to claim it back, and you can only claim what you can point to.</li>
          <li><strong>Total.</strong> The full amount paid, matching the receipt to the cent.</li>
          <li><strong>Optional but smart:</strong> a category (fuel, meals, materials) and the client or job the expense belongs to. Future you says thanks.</li>
          </ul>

          <h3>Take photos that scan cleanly</h3>
          <ul>
          <li><strong>Flatten the receipt.</strong> Smooth out folds and curls. Hold the corners down or weigh one edge with your mug.</li>
          <li><strong>Use plain light.</strong> Bright, even light works best. Harsh shadows across the numbers confuse any reader, human or software.</li>
          <li><strong>Fill the frame.</strong> Get close enough that the receipt takes up most of the photo, and keep the edges visible.</li>
          <li><strong>Shoot straight down.</strong> Angled shots stretch the text and blur the far end.</li>
          <li><strong>One receipt per photo.</strong> A pile shot saves ten seconds now and costs ten minutes later.</li>
          <li><strong>Scan it the same day.</strong> Thermal paper fades fast, and a receipt in a hot car can go blank in weeks.</li>
          </ul>

          <h3>Review the table before you export</h3>
          <p>Scanning software is quick, and quick work deserves a fast check before the numbers land in your books. Compare the table against the photo, row by row. Check the total first, because a misread digit usually shows up there. Then scan the date (March 3 loves to become March 8), the tax amount, and any line item priced over $100. Fix mistakes in the table, not in the spreadsheet later, so your export stays the single clean copy. Two minutes here saves a confusing afternoon in April.</p>

          <h3>Get the CSV into Excel or Google Sheets</h3>
          <p>Export your receipts as a CSV file. Then bring it into your spreadsheet in about a minute:</p>
          <ul>
          <li><strong>Google Sheets:</strong> Open a blank sheet. Click File, then Import, then Upload, and pick your CSV. Choose "Replace spreadsheet" or "Insert new sheet", set the separator to comma, and click Import data. Your receipts land as proper rows and columns.</li>
          <li><strong>Excel on desktop:</strong> Open a blank workbook. Go to the Data tab, click From Text/CSV, pick your file, and press Load. Excel reads the headers and types for you.</li>
          <li><strong>Excel the simple way:</strong> Double-click the CSV and it opens as a spreadsheet. Fine for a quick look, but save it as an .xlsx file before you add formulas or formatting, or they will vanish when you close it.</li>
          <li><strong>After importing:</strong> Freeze the top row (View, then Freeze in Sheets) so headers stay put, widen the vendor column, and save a backup copy before you start sorting.</li>
          </ul>

          <h2>Cost</h2>
          <ul>
          <li>Typing receipts into a spreadsheet yourself: free.</li>
          <li>Receipt Scanner: free, with no limits.</li>
          </ul>
        </div>
      </section>
      <SiteFooter />
    </main>
  );
}
