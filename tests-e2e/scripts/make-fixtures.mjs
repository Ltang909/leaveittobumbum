#!/usr/bin/env node
/**
 * Generates the tiny media fixtures the E2E suite uploads:
 *   fixtures/tiny.png     64x64 solid PNG (generic image upload)
 *   fixtures/receipt.png  drawn "receipt" with legible text for the vision tools
 *   fixtures/poster.png   drawn event poster with a date for Poster to Calendar
 *   fixtures/meal.png     drawn plate (coloured shapes) for Meal Scanner
 *   fixtures/tone.wav     2s 440 Hz sine WAV
 *   fixtures/speech.mp3   spoken sentence if ffmpeg has the flite filter,
 *                         otherwise the tone re-encoded (transcription specs
 *                         assert terminal pipeline states, not exact words)
 *   fixtures/video.mp4    2s test pattern + tone (video tools)
 *   fixtures/resume.pdf   minimal valid one-page PDF resume (Gap Scout)
 *
 * Requires ffmpeg on PATH. Run: npm run fixtures
 * Idempotent: existing files are left alone (delete fixtures/ to rebuild).
 */
import { execFileSync } from 'node:child_process';
import { existsSync, mkdirSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const out = join(here, '..', 'fixtures');
mkdirSync(out, { recursive: true });

const FONT = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
const FONT_BOLD = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
const font = existsSync(FONT_BOLD) ? FONT_BOLD : existsSync(FONT) ? FONT : null;

function ffmpeg(args) {
  execFileSync('ffmpeg', ['-hide_banner', '-loglevel', 'error', '-y', ...args], { stdio: 'inherit' });
}
function done(name) {
  console.log('fixture:', name);
}
function skip(name) {
  if (existsSync(join(out, name))) return true;
  return false;
}
const dt = (text, x, y, size) =>
  font
    ? `drawtext=fontfile=${font}:text='${text}':x=${x}:y=${y}:fontsize=${size}:fontcolor=black`
    : '';

// --- tiny.png -------------------------------------------------------------
if (!skip('tiny.png')) {
  ffmpeg(['-f', 'lavfi', '-i', 'color=c=0xff6b35:size=64x64', '-frames:v', '1', join(out, 'tiny.png')]);
  done('tiny.png');
}

// --- receipt.png ----------------------------------------------------------
if (!skip('receipt.png')) {
  const lines = [
    dt('E2E COFFEE CO', 150, 60, 44),
    dt('123 Test Street', 170, 130, 32),
    dt('Latte .......... 4.50', 90, 240, 36),
    dt('Muffin ......... 1.90', 90, 300, 36),
    dt('TOTAL .......... 6.40', 90, 400, 40),
    dt('Thank you!', 190, 500, 32),
  ].filter(Boolean);
  ffmpeg([
    '-f', 'lavfi', '-i', 'color=c=white:size=600x800',
    '-vf', lines.join(',') || 'null',
    '-frames:v', '1', join(out, 'receipt.png'),
  ]);
  done('receipt.png');
}

// --- poster.png -----------------------------------------------------------
if (!skip('poster.png')) {
  const today = new Date();
  const d = new Date(today.getTime() + 14 * 24 * 3600 * 1000);
  const when = d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
  const lines = [
    dt('E2E TEST FEST', 130, 120, 64),
    dt('Live music and food trucks', 110, 260, 36),
    dt(when.replace(/'/g, ''), 90, 360, 36),
    dt('6 PM to 9 PM', 200, 430, 36),
    dt('Riverside Park', 190, 500, 36),
    dt('Free entry', 230, 580, 36),
  ].filter(Boolean);
  ffmpeg([
    '-f', 'lavfi', '-i', 'color=c=0xfff3c4:size=800x1000',
    '-vf', lines.join(',') || 'null',
    '-frames:v', '1', join(out, 'poster.png'),
  ]);
  done('poster.png');
}

// --- meal.png -------------------------------------------------------------
if (!skip('meal.png')) {
  ffmpeg([
    '-f', 'lavfi', '-i', 'color=c=0xdfe8ff:size=640x640',
    '-vf',
    'drawbox=x=170:y=170:w=300:h=300:color=0xffffff:t=fill,' +
      'drawbox=x=220:y=240:w=200:h=90:color=0x8a5a2b:t=fill,' +
      'drawbox=x=230:y=350:w=180:h=60:color=0x3f9e4d:t=fill',
    '-frames:v', '1', join(out, 'meal.png'),
  ]);
  done('meal.png');
}

// --- tone.wav + speech.mp3 -------------------------------------------------
if (!skip('tone.wav')) {
  ffmpeg(['-f', 'lavfi', '-i', 'sine=frequency=440:duration=2', '-ar', '16000', join(out, 'tone.wav')]);
  done('tone.wav');
}
if (!skip('speech.mp3')) {
  let spoke = false;
  try {
    const filters = execFileSync('ffmpeg', ['-hide_banner', '-filters'], { encoding: 'utf8' });
    if (/flite/.test(filters)) {
      ffmpeg([
        '-f', 'lavfi',
        '-i', "flite=text='The quick brown fox jumps over the lazy dog. This is a test note for Bum Bum.':voice=slt",
        '-ar', '16000', join(out, 'speech-src.wav'),
      ]);
      ffmpeg(['-i', join(out, 'speech-src.wav'), '-codec:a', 'libmp3lame', '-q:a', '4', join(out, 'speech.mp3')]);
      spoke = true;
    }
  } catch {
    spoke = false;
  }
  if (!spoke) {
    // No TTS on this machine: fall back to the tone so the upload paths
    // still run end to end. Transcription specs accept any terminal state.
    ffmpeg(['-i', join(out, 'tone.wav'), '-codec:a', 'libmp3lame', '-q:a', '4', join(out, 'speech.mp3')]);
    console.log('fixture: speech.mp3 (tone fallback, no flite filter found)');
  } else {
    done('speech.mp3');
  }
}

// --- video.mp4 --------------------------------------------------------------
if (!skip('video.mp4')) {
  ffmpeg([
    '-f', 'lavfi', '-i', 'testsrc=duration=2:size=320x240:rate=15',
    '-f', 'lavfi', '-i', 'sine=frequency=660:duration=2',
    '-shortest', '-c:v', 'libx264', '-pix_fmt', 'yuv420p', '-c:a', 'aac',
    join(out, 'video.mp4'),
  ]);
  done('video.mp4');
}

// --- resume.pdf (hand-built minimal PDF) ------------------------------------
if (!skip('resume.pdf')) {
  const textLines = [
    'Alex Example',
    'Marketing Manager',
    'Experience: ran email campaigns, managed a small team,',
    'wrote landing page copy, tracked results in spreadsheets.',
    'Skills: email marketing, copywriting, spreadsheets.',
  ];
  const stream =
    'BT /F1 14 Tf 56 760 Td ' +
    textLines.map((l, i) => `${i ? '0 -22 Td ' : ''}(${l.replace(/[()\\]/g, '')}) Tj`).join(' ') +
    ' ET';
  const objects = [
    '<< /Type /Catalog /Pages 2 0 R >>',
    '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
    '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>',
    `<< /Length ${Buffer.byteLength(stream)} >>\nstream\n${stream}\nendstream`,
    '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
  ];
  let pdf = '%PDF-1.4\n';
  const offsets = [];
  objects.forEach((body, i) => {
    offsets.push(Buffer.byteLength(pdf));
    pdf += `${i + 1} 0 obj\n${body}\nendobj\n`;
  });
  const xrefAt = Buffer.byteLength(pdf);
  pdf += `xref\n0 ${objects.length + 1}\n0000000000 65535 f \n`;
  for (const off of offsets) pdf += `${String(off).padStart(10, '0')} 00000 n \n`;
  pdf += `trailer\n<< /Size ${objects.length + 1} /Root 1 0 R >>\nstartxref\n${xrefAt}\n%%EOF\n`;
  writeFileSync(join(out, 'resume.pdf'), pdf, 'latin1');
  done('resume.pdf');
}

console.log('fixtures ready in', out);
