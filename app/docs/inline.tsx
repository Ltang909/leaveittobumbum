import React from "react";

// Renders the small inline markup used in docs content:
// **bold** and [label](/path/).
export function Inline({ text }: { text: string }) {
  const parts = text
    .split(/(\*\*[^*]+\*\*|\[[^\]]+\]\([^)]+\))/g)
    .filter((p) => p.length > 0);
  return (
    <>
      {parts.map((part, i) => {
        const bold = part.match(/^\*\*([^*]+)\*\*$/);
        if (bold) return <strong key={i}>{bold[1]}</strong>;
        const link = part.match(/^\[([^\]]+)\]\(([^)]+)\)$/);
        if (link)
          return (
            <a key={i} href={link[2]}>
              {link[1]}
            </a>
          );
        return <React.Fragment key={i}>{part}</React.Fragment>;
      })}
    </>
  );
}
