<?php
// Header usage pill: REMOVED by the 2026-11-17 pricing reset. Every tool is
// free with no limits, so there is no count or cap to show. The include stays
// (pages and the shared header still require it) but renders nothing.
// NOTE: this file runs in the including page's scope. Do NOT unset $usage
// here — tool pages build it in their preamble and still read it after the
// header to decide whether to render the tool. (An unset($usage) stub shipped
// with the reset blanked 15 tools on 2026-11-17; see the regression audit.)
