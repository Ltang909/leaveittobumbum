"use client";

import { createContext, useCallback, useContext, useRef, useState } from "react";
import type { ReactNode } from "react";

// Shared "which face is Bum Bum wearing" state between the front desk
// (capture-bar.tsx) and the hero portrait (page.tsx). The desk drives
// it: a mood flash while he works or celebrates, a hat (the routed
// tool's persona icon) once he has filed something, and a pop when the
// bell rings. The portrait renders mood first, then hat, then its own
// boop-cycled face. Outside a provider everything is a no-op, so the
// desk can never break the page over a missing face.
type DeskFaceApi = {
  mood: string | null;
  hat: string | null;
  popKey: number;
  flashMood: (src: string, ms: number) => void;
  setHat: (src: string | null) => void;
  pop: () => void;
};

const noop = () => {};

const DeskFaceContext = createContext<DeskFaceApi>({
  mood: null,
  hat: null,
  popKey: 0,
  flashMood: noop,
  setHat: noop,
  pop: noop,
});

export function DeskFaceProvider({ children }: { children: ReactNode }) {
  const [mood, setMood] = useState<string | null>(null);
  const [hat, setHatState] = useState<string | null>(null);
  const [popKey, setPopKey] = useState(0);
  const moodTimer = useRef<number | undefined>(undefined);

  const flashMood = useCallback((src: string, ms: number) => {
    setMood(src);
    window.clearTimeout(moodTimer.current);
    moodTimer.current = window.setTimeout(() => setMood(null), ms);
  }, []);

  const setHat = useCallback((src: string | null) => {
    setHatState(src);
  }, []);

  const pop = useCallback(() => {
    setPopKey((k) => k + 1);
  }, []);

  return (
    <DeskFaceContext.Provider value={{ mood, hat, popKey, flashMood, setHat, pop }}>
      {children}
    </DeskFaceContext.Provider>
  );
}

export function useDeskFace(): DeskFaceApi {
  return useContext(DeskFaceContext);
}
