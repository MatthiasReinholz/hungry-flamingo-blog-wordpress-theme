# Hungry Flamingo Blog

Hungry Flamingo Blog is a colorful block theme for editorial blogs and longform publishing. It includes full-site-editing templates, block patterns, a dark/light color toggle, a mobile navigation overlay, and polished single-article layouts.

## Requirements

- WordPress 6.4 or newer; tested through WordPress 6.9.
- PHP 8.2 or newer.

## Included Theme Features

- Block templates and template parts for home, front page, single, page, archive, search, 404, category, tag, author, and date views.
- WooCommerce block templates for product archives, product search, single products, cart, checkout, and order confirmation.
- Patterns: article card, home hero, and sidebar category widget.
- Client-side enhancements for search overlay, table of contents, sidebar author card, mobile menu, feed links, and color scheme persistence.
- WooCommerce support declarations, product gallery support, theme.json block styles, and conditional store styles for WooCommerce catalog, product, cart, checkout, order-confirmation, and account surfaces.
- Translation seed file at `languages/hungry-flamingo-blog.pot`.
- Continuous reading is available through the separate Hungry Flamingo Blog Companion plugin.
- Repository-specific AI coding-agent instructions are documented in `AGENTS.md`.

## Distribution Notes

This repository intentionally contains development tooling, CI configuration, tests, and agent documentation. Do not install the repository root as the production theme package. Install the verified release artifact at `dist/hungry-flamingo-blog.zip`, or the matching GitHub Release ZIP produced by the release workflows.

The release artifact contains only the installable WordPress theme tree. `npm run verify:dist` enforces required files, excludes development-only files, and checks that plugin-territory custom block code is not shipped in the theme package.

The continuous-reading feature lives in the separate Hungry Flamingo Blog Companion plugin so the theme remains focused on templates, styling, and presentation.

Newsletter forms were intentionally replaced with RSS links. Add newsletter capture through a plugin or service integration, not directly in the theme.

WooCommerce compatibility is intentionally theme-only. Commerce data, payments, product management, and checkout behavior remain owned by WooCommerce.

## Development

```sh
composer install
composer lint:php
npm install
npm run lint:js
npm run lint:css
```

Visual smoke tests expect a running WordPress site:

```sh
WP_BASE_URL=http://localhost:8888 npm run test:visual
```

The visual smoke suite includes accessibility checks and optional WooCommerce coverage. WooCommerce tests are skipped when the running WordPress fixture does not expose product, cart, and checkout pages.

Release ZIPs can be built with the project-owned dist script:

```sh
npm run dist
npm run verify:dist
```

## Release Workflow

Use the GitHub Actions release flow for production artifacts:

- `Prepare release` creates or updates a `release/x.y.z` pull request and bumps theme metadata.
- `Finalize release` publishes the release after a merged `release/*` or `hotfix/*` PR to `main`.
- `Release repair` rebuilds and republishes the artifact for an existing tag.

All three flows build the clean package through `scripts/build-theme-zip.sh` and verify it through `scripts/verify-release-package.sh`.

## Privacy

The theme stores a first-party `hfb_theme` cookie for the light/dark preference. It contains only `light` or `dark`, lasts one year, uses `SameSite=Lax`, and is not used for tracking or shared with external services.

## Release Checklist

See [docs/release-checklist.md](docs/release-checklist.md).

## License

GNU General Public License v3. See [LICENSE](LICENSE).
