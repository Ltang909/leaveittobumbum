#!/usr/bin/env python3
"""Generates fixtures/sample.heic, a standards-compliant HEIF still image.

ffmpeg in this environment has no HEIF muxer (its brand-forced MP4 write is
rejected by the site's libheif decoder), so the HEIC fixture is made with
Pillow + pillow-heif instead. Called by scripts/make-fixtures.mjs; if
python3 or pillow_heif is unavailable the fixture is skipped and the HEIC
specs fall back to their validation-state coverage.
"""
import sys
from pathlib import Path

from PIL import Image, ImageDraw
import pillow_heif

pillow_heif.register_heif_opener()

out = Path(__file__).resolve().parent.parent / "fixtures" / "sample.heic"
img = Image.new("RGB", (320, 240), (51, 102, 204))
draw = ImageDraw.Draw(img)
draw.rectangle([40, 40, 280, 200], fill=(255, 179, 53))
img.save(out, format="HEIF", quality=90)
print("fixture: sample.heic")
