# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Waxy** is a minimal, responsive Typecho blog theme. Typecho is a lightweight PHP blogging platform. This is a pure PHP theme — no build step, no package manager, no transpilation.

## Development Setup

No build process required. To use the theme:
1. Copy theme files to `/usr/themes/Waxy/` in a Typecho installation
2. Activate via Typecho admin panel: 控制台 → 外观 → Waxy → 启用

There are no tests, no linting tools, and no CI/CD configuration in this repo.

## Architecture

### Template System
Typecho routes requests to PHP templates. All templates follow this pattern:
```php
get_header();        // includes header.php
// ... page-specific content ...
get_sidebar();       // includes sidebar.php
get_footer();        // includes footer.php
```

Template files: `index.php` (post list), `post.php` (single post), `page.php` (static page), `archive.php`, `404.php`, plus special page templates prefixed with `page_` (`page_friends.php`, `page_timeline.php`, `page_articles.php`, `page_articles_month.php`, `page_sitemap.php`).

The post list markup lives in `post_list.php`, included by `index.php`. It renders two modes based on `$this->options->articles_list`: full-article mode (`== 1`) and excerpt/card mode (`== 0`).

Full-article mode shows "阅读全文" from whichever of two triggers fires first. `getIndexContent()` returns `['content' => ..., 'has_more' => bool]`: if the post has a `<!--more-->` marker, content is truncated there (like the old excerpt-index behavior) and `has_more` is true, so `post_list.php` renders the sibling `.readall` block visible immediately (no `hidden` attribute) — the author's marker always wins regardless of rendered height. If there's no marker, the full content is shown and `.readall` starts `hidden`; `.post-content.post__content[data-clamp-total="800"]` is then watched by `initContentClamp()` in `js/waxy-main.js`, which measures the whole `.post` article's rendered `scrollHeight` (via `ResizeObserver`, since lazy-loaded images grow it after initial paint) — if the *whole item* (head + content + footer) would exceed 800px, it adds `.post__content--clamped` (CSS `max-height: 460px`) to just the content box and reveals `.readall`. `.post__content--clamped` sets `overflow-x: visible; overflow-y: clip` rather than plain `overflow: hidden`, so wide content (tables, code blocks) isn't clipped sideways along with the bottom — CSS's "visible pairs with non-visible → becomes auto" rule means a plain `overflow-y: hidden` would silently turn `overflow-x` into a second clipping axis too. Since full-mode list content is no longer guaranteed to be short, `header.php`'s code-highlight gate loads Prism whenever `articles_list == 1`, not just on single/page views (it can't cheaply pre-scan every row in the loop for code fences); `shortcode.php`'s `[poststats]` guard was similarly relaxed to render in full-list mode, not just `is('single') || is('page')`.

Excerpt/card mode is a flex layout: a fixed-ratio cover image (`.excerpt__img`, 5:3 via `aspect-ratio`) beside a body column (`.excerpt__body`) with title → excerpt text → footer. The footer (`.excerpt__info`) holds author/date (`.excerpt__item`) plus one or more badge groups (`.excerpt__badges`): a single leading icon followed by each category/tag rendered as its own `.excerpt__badge` link. Both groups are capped (`$waxy_badge_groups` in `post_list.php`: 2 categories, 3 tags) — items beyond the cap collapse into a `+N` badge (`.excerpt__badge--more`) whose CSS-only hover/focus tooltip (`.excerpt__badge-tip`) lists the rest as clickable `.excerpt__badge-tip__item` links. Badge fill color is `var(--waxy-badge-bg)`, a variable that differs from `--waxy-bg-soft` specifically so it stays visible against the card's `--waxy-surface` background in light mode; the sidebar tag cloud (`.tag-cloud a`) shares this same badge styling for visual consistency.

### Core Files
- **`functions.php`** — All theme functions and the `themeConfig()` hook that registers 30+ admin settings. This is the main logic file.
- **`shortcode.php`** — Registers shortcode handlers; delegates to `lib/shortcode.php` for the engine.
- **`lib/icons.php`** — Inline SVG icon library; use `waxy_icon('name')` or `waxy_icon('name', 'extra-class')` everywhere an icon is needed. Never embed raw SVG in templates.
- **`css/waxy-main.css`** — All custom styles (~3600+ lines); Bootstrap 3 is the base framework loaded via CDN.
- **`js/waxy-main.js`** — Custom JS: lazy loading (IntersectionObserver), lightbox (no deps), back-to-top, dropdown menu, dark mode toggle, mobile drawer.

### Content Processing Pipeline
In `functions.php`, two content functions exist — use the right one:
- `getContent($content)` — for single post/page views; runs shortcodes → `getPicHtml()` → `waxy_process_toc()` (populates `$GLOBALS['waxy_toc_items']` for the sidebar TOC widget)
- `getIndexContent($content)` — for the post list's full-article mode (`articles_list == 1`); runs shortcodes → `getPicHtml()`. It ignores `<!--more-->` and always returns the complete content — truncation is no longer done server-side

`getPicHtml()` wraps `<img>` tags with lightbox links, centering, and lazy loading. Image lazy loading has two modes:
- **JS mode** (`JQlazyload = 1`): replaces `src` with `data-src` + 1px GIF placeholder; IntersectionObserver swaps on scroll
- **Native mode**: adds `loading="lazy"` attribute directly

### TOC Sidebar
`waxy_process_toc()` (called inside `getContent()`) scans the rendered HTML for `<h1>`–`<h4>` tags, injects `id="toc-N"` attributes, and stores the item list in `$GLOBALS['waxy_toc_items']`. `sidebar.php` then reads this global to render the TOC widget. The TOC only appears when `showToc` is enabled and there are ≥ 3 headings.

### Comments System
`comments.php` uses a custom rendering approach that bypasses Typecho's default output:
1. Defines a global `threadedComments()` function — Typecho's `listComments()` calls this hook for each comment (including children recursively)
2. Collects all comments into `$waxy_coms_collect` via `ob_start`/`ob_end_clean`
3. Splits into roots and replies, groups replies under their root ancestor, and renders a two-level `<ol>` tree
4. Avatars are fetched from `cravatar.cn` using the MD5 of the commenter's email
5. Inline reply JS (`waxySetReply`) moves the comment form inline below the target comment

### CDN Switching
`footer.php` and `header.php` conditionally load jQuery/Bootstrap from one of: local files, Staticfile, 75CDN, Bootcss, or jsDelivr — based on the `cdn` theme option.

### Shortcodes
Available shortcodes (defined in `shortcode.php`):
- Alert boxes: `[info]`, `[warning]`, `[danger]`
- Text styles: `[em]`, `[hi]`, `[lo]`
- Media: `[audio src="..."]`, `[video src="..." poster="..."]`
- Collapsible: `[shrinks title="..."]...[/shrinks]`
- Alert: `[alert style="..."]...[/alert]`
- Checkboxes: `[check]`, `[uncheck]`

### Custom Post Fields
Templates read Typecho custom fields on posts:
- `$this->fields->img` — custom cover image URL (overrides auto-detect)
- `$this->fields->star` — marks post as "featured" (shows star badge)
- `$this->fields->info` — custom excerpt text (overrides auto-generated excerpt)

### Theme Options
All settings are registered in `themeConfig()` in `functions.php` and accessed via:
```php
$this->options->optionName
// or in static context:
Typecho_Widget::widget('Widget_Options')->optionName
```

### CSS Conventions
- BEM naming throughout: `.post__head`, `.post__title`, `.excerpt__body`, `.card__avatar`, etc.
- CSS variables in `:root` and `html.dark` for theming; dark mode toggled by the `html.dark` class (stored in `localStorage`)
- Single breakpoint: `1000px` (mobile vs desktop)
- Content area anchor: `.post-content` (not `.post__content` — the latter is removed)
- All `.post-content` block elements (headings' `#` prefix, `blockquote`, `.hint`, `.alert`) render flush at `margin-left: 0` — none of them bleed into the card padding. This keeps their left edge lined up with `.post__border` (the divider under the post title/meta), which is the visual reference width for the whole content column
- `.btn` base class carries all `transition` declarations; variants must not re-declare them
- No `transition: all` — always specify concrete properties
- `--waxy-primary-hover` is derived via `color-mix(in srgb, var(--waxy-primary) 85%, black)` in `:root` rather than a separate hardcoded hex — a subtler darken than an unrelated hover color, and it doesn't need a `html.dark` override since it tracks `--waxy-primary` automatically
- When a color needs a different value per theme beyond what an existing variable gives you (e.g. badge fill needing more contrast than `--waxy-bg-soft` in light mode but being fine as-is in dark mode), add a new semantic variable in both `:root` and `html.dark` (see `--waxy-badge-bg`) rather than hardcoding a light/dark check in the rule itself

## PHP Compatibility
The theme targets PHP 7.0+ and PHP 8.0+. Avoid deprecated patterns and ensure null-safety (use `?? ''` / `?? []` for potentially undefined variables).
