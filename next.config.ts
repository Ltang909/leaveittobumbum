import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  reactStrictMode: true,
  output: process.env.STATIC_EXPORT === "true" ? "export" : undefined,
  // Static export writes page/index.html instead of page.html, so
  // Hostinger/LiteSpeed serves /page/ URLs without needing postbuild.
  trailingSlash: process.env.STATIC_EXPORT === "true" ? true : undefined,
};

export default nextConfig;

