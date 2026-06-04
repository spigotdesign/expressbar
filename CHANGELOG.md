# Changelog

All notable changes to ExpressBar are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
