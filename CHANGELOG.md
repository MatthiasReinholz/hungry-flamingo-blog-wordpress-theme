# Changelog

## 1.0.0 - Pending stable release

- Added tag-push release automation so beta tags create or repair GitHub prereleases with installable ZIP assets.
- Hardened prerelease publication so stable tags stay owned by the release PR flow and prerelease tags must come from trusted history.
- Excluded the current single post from the sidebar related-post query.
- Fixed first beta release-readiness issues from the final pre-release audit.
- Added release documentation, tooling, packaging exclusions, and CI scaffolding.
- Removed remote Google Fonts loading from the front end and editor.
- Replaced placeholder newsletter forms with RSS feed links.
- Added working search overlay, sidebar table of contents, and sidebar author-card hydration.
- Replaced placeholder navigation refs with portable page-list/category/archive blocks.
- Moved continuous-reading functionality to the Hungry Flamingo Blog Companion plugin.
- Added template coverage for front page, blog home, category, tag, author, and date archives.
- Added WooCommerce support declarations, product gallery support, block templates, theme.json Woo block styles, conditional store styling, accessibility checks, and store smoke-test coverage.
- Added WooCommerce presentation patterns, style variations, and first-party editor/font documentation.
- Added release packaging rules for dist archives and Git source exports.
- Added GitHub Actions release workflows that publish verified installable theme artifacts from the source repository.
