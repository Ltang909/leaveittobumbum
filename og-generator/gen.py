#!/usr/bin/env python3
"""Generate per-tool share cards (og:image) from og-generator/template.png.
Only the title and description change; everything else is the template.
Usage: python3 og-generator/gen.py [key ...]   (no args = all cards)"""
import os, sys
from PIL import Image, ImageDraw, ImageFont

ROOT = os.path.dirname(os.path.abspath(__file__))
OUT = os.path.join(ROOT, '..', 'hostinger', 'public_html', 'bum', 'og')
TPL = os.path.join(ROOT, 'template.png')
BALOO = os.path.join(ROOT, 'fonts', 'Baloo_2_wght_800.ttf')
NUNITO = os.path.join(ROOT, 'fonts', 'Nunito_wght_600.ttf')
CREAM = (254, 247, 245)
TITLE_COLOR = (44, 23, 14)
SUB_COLOR = (108, 105, 105)
X, TITLE_W, SUB_W = 47, 715, 670

CARDS = {
    'clips': ("Screen Recorder", "Record your screen, mic, and camera in your browser."),
    'notes': ("Voice Notes to Text", "Talk it out and get a clean transcript in seconds."),
    'cutline': ("Subscription Tracker", "Every subscription and renewal, in one place."),
    'purrsuit': ("Client Follow-Up Tracker", "Never let a warm lead go cold again."),
    'corporate-bum-bum': ("Job Application Tracker", "Track every application without the spreadsheet."),
    'doodle': ("Online Sketchpad", "Sketch ideas fast, right in your browser."),
    'invoice-chaser': ("Invoice Reminder", "Polite chase emails that get you paid."),
    'receipt-reader': ("Receipt Scanner", "Snap receipts, get a tidy spreadsheet."),
    'poster-to-calendar': ("Poster to Calendar", "Turn any event poster into a calendar entry."),
    'meal-scanner': ("Meal Scanner", "Snap your plate, get the nutrition numbers."),
    'gap-scout': ("Resume Gap Checker", "See the skills job posts ask for that you miss."),
    'video-trimmer': ("Video Trimmer", "Keep the good part, snip the rest in seconds."),
    'image-converter': ("Image Converter", "WebP, PNG, and JPG converted in your browser."),
    'pdf-merger': ("Merge PDF", "Stitch all your PDFs into one file in seconds."),
    'pdf-to-image': ("PDF to Image", "Turn PDF pages into clean JPG or PNG images."),
    'image-cropper': ("Image Cropper", "Crop to the part that matters, in seconds."),
    'ghostwriter': ("Voice to Content", "Ramble out loud, get hooks and captions back."),
    'sound-human': ("Make AI Sound Human", "Prompt add-ons that make AI replies sound human."),
    'audiogram': ("Audiogram Maker", "Turn an MP3 into a captioned video for social."),
    'purr-code': ("QR Code Generator", "Cute QR codes for your links, ready to share."),
    'sop-ify': ("SOP Generator", "Turn your messy process into a clean playbook."),
    'hearsay': ("AI Mention Tracker", "See what AI models say about your brand."),
    'video-converter': ("Video Converter", "Convert video to MP4, WebM, or GIF in your browser."),
    'video-mp3-overlay': ("Video MP3 Overlay", "Pin your soundtrack onto your video."),
    'day-log': ("Day Log", "Five minutes a night to log your day and lessons."),
    'email-signature-generator': ("Email Signature Generator", "A clean, professional signature in minutes."),
    'pdf-compressor': ("PDF Compressor", "Same PDF, smaller file, right in your browser."),
    'invoice-generator': ("Invoice Generator", "Build a clean invoice and download the PDF."),
    'heic-to-jpg': ("HEIC to JPG Converter", "Turn HEIC photos into JPGs in seconds."),
    'heic-to-png': ("HEIC to PNG Converter", "Turn HEIC photos into PNGs in seconds."),
    'heic-to-pdf': ("HEIC to PDF Converter", "Turn HEIC photos into one tidy PDF."),
    'jpg-to-pdf': ("JPG to PDF Converter", "Turn your images into one tidy PDF."),
    'split-pdf': ("Split PDF", "Pull the pages you need out of any PDF."),
    'png-to-jpg': ("PNG to JPG Converter", "Turn PNGs into smaller JPGs in seconds."),
    'webp-to-png': ("WebP to PNG Converter", "Turn WebP images into PNGs in seconds."),
    'webp-to-jpg': ("WebP to JPG Converter", "Turn WebP images into JPGs in seconds."),
    'png-to-webp': ("PNG to WebP Converter", "Turn PNGs into WebP files in seconds."),
    'jpg-to-webp': ("JPG to WebP Converter", "Turn JPGs into WebP files in seconds."),
    'jpg-to-png': ("JPG to PNG Converter", "Turn JPGs into PNGs in seconds."),
    'pdf-to-jpg': ("PDF to JPG Converter", "Turn PDF pages into JPG images in seconds."),
    'pdf-to-png': ("PDF to PNG Converter", "Turn PDF pages into PNG images in seconds."),
    'png-converter': ("PNG Converter", "Every PNG conversion, in and out, in one place."),
    'jpg-converter': ("JPG Converter", "Every JPG conversion, in and out, in one place."),
    'webp-converter': ("WebP Converter", "Every WebP conversion, in and out, in one place."),
    'heic-converter': ("HEIC Converter", "Turn iPhone HEIC photos into JPG, PNG, or PDF."),
    'pdf-converter': ("PDF Converter", "Turn PDFs into images, merge, split, and shrink."),
}

def wrap(d, text, font, maxw):
    words, lines, cur = text.split(), [], ''
    for w in words:
        t = (cur + ' ' + w).strip()
        if d.textlength(t, font=font) <= maxw or not cur:
            cur = t
        else:
            lines.append(cur); cur = w
    if cur: lines.append(cur)
    return lines

def render(title, sub, out):
    im = Image.open(TPL).convert('RGB')
    d = ImageDraw.Draw(im)
    d.rectangle([30, 285, 862, 455], fill=CREAM)
    d.rectangle([30, 455, 792, 568], fill=CREAM)
    d.rectangle([30, 600, 736, 768], fill=CREAM)
    size = 124
    while size > 58:
        f = ImageFont.truetype(BALOO, size)
        lines = wrap(d, title, f, TITLE_W)
        if len(lines) <= 2 and all(d.textlength(l, font=f) <= TITLE_W for l in lines):
            break
        size -= 4
    y = 267
    for l in lines:
        d.text((X, y), l, font=f, fill=TITLE_COLOR)
        y += int(size * 1.04)
    fs = ImageFont.truetype(NUNITO, 47)
    y = 638
    for l in wrap(d, sub, fs, SUB_W)[:2]:
        d.text((X, y), l, font=fs, fill=SUB_COLOR)
        y += 59
    im.save(out, quality=90)
    return size, lines

if __name__ == '__main__':
    keys = sys.argv[1:] or list(CARDS)
    os.makedirs(OUT, exist_ok=True)
    for k in keys:
        title, sub = CARDS[k]
        size, lines = render(title, sub, os.path.join(OUT, k + '.jpg'))
        print(f'{k}: size {size}, lines {lines}')
