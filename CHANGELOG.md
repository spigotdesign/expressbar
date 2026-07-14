# Changelog

All notable changes to ExpressBar are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.4] - 2026-07-14

### Fixed
- Fixed/sticky headers no longer drift or stack their offset when the bar is open ("header breaks sometimes"). The JS used to re-measure each header's `top` on every push; a resize burst (window dragging, mobile URL-bar collapse) or a read during the 0.5s transition could capture an already-pushed or mid-animation value as the "original," compounding the offset for the rest of the session. Each header's natural top is now recorded once as `--exb-original-top`, and the offset is computed by a single CSS rule from `--exb-original-top` + `--exb-height`, so it can never accumulate.
- Tracked headers no longer lose their theme's own transitions. The bar's `transition: top !important` was applied permanently, which replaced the theme's transition list and turned scroll-driven header animations (e.g. the Bricks sticky slide-up, which animates `transform`) into instant snaps. The top transition is now applied only for the brief window while the bar itself opens or closes (`.exb-animating`).

### Changed
- Front-end style and script enqueues now pass the plugin version (`1.0.4`) for cache busting; previously they fell back to the WordPress core version, so plugin updates could be masked by cached assets.

## [1.0.3] - 2026-06-26

### Added
- Font Family setting on the options page: enter a font-family stack to control the bar's typeface, with a live preview. Leave it blank to inherit the theme font.

### Changed
- The bar now inherits the active theme's font by default. Previously the font was hardcoded to a Helvetica Neue stack; the new default (`--exb-font-family: inherit`) also serves as the fallback if an optimizer strips the inline styles.

## [1.0.2] - 2026-06-22

### Fixed
- Wait for jQuery before initializing so the bar survives JavaScript optimizers that defer or delay scripts (e.g. WP Rocket "Delay JavaScript Execution"). Previously the script could run before jQuery loaded and fail silently, leaving the toggle button unresponsive.
- Treat the jQuery countdown and cookie plugins as optional, so a missing or late dependency no longer aborts initialization.

### Added
- Register a WP Rocket "Remove Unused CSS" safelist so the bar's styles are not stripped on optimized sites.

### Changed
- Replace the SCSS build pipeline with hand-maintained CSS driven by custom properties.

## [1.0.1] - 2026-06-03

### Security
- Escape all settings-page output (`esc_attr` / `esc_url`) to prevent stored XSS through saved options.
- Sanitize all saved options on write: text fields via `sanitize_text_field`, color fields via `sanitize_hex_color`, and custom CSS via `wp_strip_all_tags`.
- Add a `manage_options` capability check and harden nonce verification on the cookie-reset AJAX handler.
- Use the `manage_options` capability (instead of the `administrator` role name) for the settings menu.

### Added
- Automatic runtime detection of fixed/sticky headers, which are pushed down while the bar is open.

### Changed
- Smooth the transition when pushing fixed headers so it matches the bar open/close speed.

### Fixed
- Account for the optional bar border height when shifting the body and WordPress admin bar.
- Various bug fixes across JavaScript, PHP, and CSS.

## [1.0.0]

### Added
- The first version of ExpressBar.
