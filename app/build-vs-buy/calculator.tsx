"use client";

import { useState } from "react";

const DIY_HOURS = 25;
const OPERATOR_PRICE = 49;

function fmt(n: number) {
  return n.toLocaleString("en-US", { maximumFractionDigits: 0 });
}

export default function DiyCalculator() {
  const [rate, setRate] = useState(75);
  const diyCost = rate * DIY_HOURS;
  const multiple = diyCost / OPERATOR_PRICE;

  return (
    <div className="calc-card">
      <h2>Do the vibe-code math</h2>
      <p>
        A solid little tool takes about <strong>{DIY_HOURS} hours</strong> to build, test, and polish.
        That is the conservative estimate, and it does not include the 11pm bug reports.
        Move the slider to what an hour of your time is worth.
      </p>
      <label className="calc-slider-label" htmlFor="hourly-rate">
        My hour is worth <strong>${rate}</strong>
      </label>
      <input
        id="hourly-rate"
        type="range"
        min={25}
        max={200}
        step={5}
        value={rate}
        onChange={(e) => setRate(Number(e.target.value))}
        className="calc-slider"
        aria-valuetext={`$${rate} per hour`}
      />
      <div className="calc-results">
        <div className="calc-result">
          <b>Build it yourself</b>
          <span className="calc-number">${fmt(diyCost)}</span>
          <small>{DIY_HOURS} hours of your evenings, and you maintain it forever</small>
        </div>
        <div className="calc-vs" aria-hidden="true">vs</div>
        <div className="calc-result calc-winner">
          <b>Request it</b>
          <span className="calc-number">${OPERATOR_PRICE}</span>
          <small>In your toolbox within 36 hours, maintained by us</small>
        </div>
      </div>
      <p className="calc-punchline">
        Building it yourself costs roughly <strong>{multiple < 10 ? multiple.toFixed(1) : fmt(multiple)}x more</strong>,
        and you still have to QA it on a phone you do not own.
      </p>
    </div>
  );
}
