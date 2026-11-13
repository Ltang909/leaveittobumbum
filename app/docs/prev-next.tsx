import { prevNext } from "./manifest";

export default function PrevNext({ href }: { href: string }) {
  const { prev, next } = prevNext(href);
  if (!prev && !next) return null;
  return (
    <nav className="docs-prevnext" aria-label="More docs">
      {prev ? (
        <a href={prev.href} className="docs-prevnext-link">
          <span>Previous</span>
          <strong>{prev.title}</strong>
        </a>
      ) : (
        <span />
      )}
      {next ? (
        <a href={next.href} className="docs-prevnext-link docs-prevnext-next">
          <span>Next</span>
          <strong>{next.title}</strong>
        </a>
      ) : (
        <span />
      )}
    </nav>
  );
}
