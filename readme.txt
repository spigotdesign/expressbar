=== ExpressBar ===
Contributors: Spgigot Design, DesignWall
Tags: promotion ,topbar, header bar, quick notice, bar, notification bar, countdown, responsive
Tested up to: 5.6
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==
A clean and simple WordPress plugin that allows you to have a promotion and message bar displayed at the top of your site. You can have a meaningful message or simply a catchy message for your promotion within a few simple setup. You can even have a countdown clock. All these features can be easily configured in the admin panel. You have a control over the entire bar from text message, countdown time to text and link colors.

== Installation ==

1. Upload `expressbar` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Log In to your WordPress Dashboard and go to menu `Dashboard > Settings > Expressbar` to configure Expressbar

== Screenshots ==
1. Back-end settings
2. Front-end appearance

== Changelog ==

= 1.0.2 =
- Resilience: wait for jQuery before initializing so the bar keeps working with JavaScript optimizers (e.g. WP Rocket "Delay JavaScript Execution") instead of failing silently.
- Treat the countdown and cookie plugins as optional so a missing or late dependency no longer breaks the bar.
- Add a WP Rocket "Remove Unused CSS" safelist so the bar's styles are not stripped on optimized sites.
- Replace the SCSS build with hand-maintained CSS driven by custom properties.

= 1.0.1 =
- Security: escape all settings-page output and sanitize all saved options to prevent stored XSS.
- Security: add capability and nonce checks to the cookie-reset AJAX handler.
- Add automatic runtime detection of fixed/sticky headers and push them down when the bar is open.
- Smooth the transition when pushing fixed headers to match the bar open/close speed.
- Account for the optional bar border height when shifting the body and admin bar.
- Various bug fixes across JavaScript, PHP, and CSS.

= 1.0.0 =
- The first version of ExpressBar