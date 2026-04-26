=== Hungry Flamingo Blog ===
Contributors: matthiasreinholz
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 8.2
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Tags: blog, block-theme, full-site-editing, one-column, two-columns, custom-colors, custom-logo, featured-images, threaded-comments, wide-blocks, block-styles, editor-style, news, e-commerce

A colorful editorial block theme for longform publishing.

== Description ==

Hungry Flamingo Blog is built for editorial blogs, personal publications, and content-heavy sites. It ships with block templates, template parts, article patterns, dark/light color scheme persistence, a mobile menu, and polished longform article templates.

The theme includes WooCommerce support declarations, product gallery support, block templates, theme.json block styles, a translation seed file, and conditional store styling for catalog, product, cart, checkout, order-confirmation, and account views. Newsletter capture and continuous reading are out of scope for the theme and should be handled by plugins.

== Installation ==

1. Upload the theme directory to `wp-content/themes/`.
2. Activate Hungry Flamingo Blog in Appearance > Themes.
3. Add pages and posts, then customize the header, footer, and navigation in the Site Editor.
4. Optional: configure a newsletter plugin or provider if you want email capture beyond the built-in RSS links.

== Frequently Asked Questions ==

= Does this theme support WooCommerce? =

Yes. The theme declares WooCommerce support, enables product gallery zoom/lightbox/slider support, includes block templates for WooCommerce store screens, and loads WooCommerce-specific styles only on WooCommerce screens. It relies on WooCommerce for products, cart, checkout, payments, and store data.

= Where is the continuous-reading feature? =

Continuous reading lives in the separate Hungry Flamingo Blog Companion plugin.

= Does the theme load Google Fonts? =

No. The theme uses CSS font stacks and does not enqueue remote font assets at runtime.

== Privacy ==

The theme stores a first-party `hfb_theme` cookie for the visitor's light/dark preference. The value is limited to `light` or `dark`, expires after one year, uses `SameSite=Lax`, and is not used for tracking or sent to external services.

== Changelog ==

= 1.0.0 =

Initial beta release.
