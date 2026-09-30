import type { Metadata } from "next";
import { Fraunces, Nunito_Sans } from "next/font/google";
import "./globals.css";

const display = Fraunces({ subsets: ["latin"], variable: "--font-display" });
const body = Nunito_Sans({ subsets: ["latin"], variable: "--font-body" });

export const metadata: Metadata = {
  metadataBase: new URL(process.env.NEXT_PUBLIC_SITE_URL || "https://leaveittobumbum.com"),
  title: "Leave It to Bum Bum | Tiny tools for busy businesses",
  description: "Useful little tools for the annoying parts of running a small business. Start free, then pay as your business uses more.",
  icons: { icon: "/bum/favicon-cat.png" },
  openGraph: {
    title: "Leave It to Bum Bum | Tiny tools for busy businesses",
    description: "Useful little tools for the annoying parts of running a small business. Start free, then pay as your business uses more.",
    url: "https://leaveittobumbum.com/",
    siteName: "Leave It to Bum Bum",
    images: [{ url: "https://leaveittobumbum.com/bum/favicon-cat.png" }],
    type: "website",
  },
  twitter: {
    card: "summary",
    title: "Leave It to Bum Bum | Tiny tools for busy businesses",
    description: "Useful little tools for the annoying parts of running a small business. Start free, then pay as your business uses more.",
  },
};

const organizationLd = {
  "@context": "https://schema.org",
  "@type": "Organization",
  name: "Leave It to Bum Bum",
  url: "https://leaveittobumbum.com/",
  logo: "https://leaveittobumbum.com/bum/favicon-cat.png",
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en">
      <body className={`${display.variable} ${body.variable}`}>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(organizationLd) }}
        />
        {children}
      </body>
    </html>
  );
}

