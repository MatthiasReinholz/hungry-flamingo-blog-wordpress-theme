# Hungry Flamingo Blog

[![CI](https://github.com/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme/actions/workflows/ci.yml/badge.svg)](https://github.com/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme/actions/workflows/ci.yml)
[![Latest Release](https://img.shields.io/github/v/release/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme)](https://github.com/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme/releases)
[![License: GPL-3.0-only](https://img.shields.io/badge/license-GPL--3.0--only-blue.svg)](LICENSE)
[![WordPress Tested](https://img.shields.io/badge/WordPress-tested%20to%206.9-blue.svg)](readme.txt)

Hungry Flamingo Blog is a colorful block theme for editorial blogs and longform publishing. It includes full-site-editing templates, block patterns, a dark/light color toggle, a mobile navigation overlay, and polished single-article layouts.

## Requirements

- WordPress 6.4 or newer; tested through WordPress 6.9.
- PHP 8.2 or newer.

## Included Theme Features

- Block templates and template parts for home, front page, single, page, archive, search, 404, category, tag, author, and date views.
- WooCommerce block templates for product archives, product search, single products, cart, checkout, and order confirmation.
- Patterns: article card, home hero, sidebar category widget, and WooCommerce presentation layouts.
- Client-side enhancements for search overlay, table of contents, sidebar author card, mobile menu, feed links, and color scheme persistence.
- WooCommerce support declarations, product gallery support, theme.json block styles, and conditional store styles for WooCommerce catalog, product, cart, checkout, order-confirmation, and account surfaces.
- WooCommerce presentation patterns for product collections, article-product callouts, comparison tables, cart trust bands, and post-purchase reading.
- Style variations for alternative first-party color systems.
- Translation seed file at `languages/hungry-flamingo-blog.pot`.
- Continuous reading is available through the separate Hungry Flamingo Blog Companion plugin.
- Repository-specific AI coding-agent instructions are documented in `AGENTS.md`.

## Distribution Notes

This repository intentionally contains development tooling, CI configuration, tests, and agent documentation. Do not install the repository root as the production theme package. Install the verified release artifact at `dist/hungry-flamingo-blog.zip`, or the matching GitHub Release ZIP produced by the release workflows.

The release artifact contains only the installable WordPress theme tree. `npm run verify:dist` enforces required files, excludes development-only files, and checks that plugin-territory custom block code is not shipped in the theme package.

The continuous-reading feature lives in the separate Hungry Flamingo Blog Companion plugin so the theme remains focused on templates, styling, and presentation.

Newsletter forms were intentionally replaced with RSS links. Add newsletter capture through a plugin or service integration, not directly in the theme.

WooCommerce compatibility is intentionally theme-only. Commerce data, payments, product management, and checkout behavior remain owned by WooCommerce.

The theme includes WooCommerce presentation patterns. Insert them only on sites where WooCommerce is active; they are layout starters, not commerce logic.

## Editor And Fonts

See [docs/editorial-workflow.md](docs/editorial-workflow.md) for template/pattern guidance and [docs/font-policy.md](docs/font-policy.md) for the local-font policy. Site-specific font uploads should use WordPress core's Font Library, not a companion-plugin uploader.

## Development

```sh
composer install
npm install
npm run validate
```

Visual smoke tests expect a running WordPress site:

```sh
bash scripts/setup-wordpress-visual-fixture.sh
HFB_VISUAL_STRICT_WOO=1 WP_BASE_URL=http://127.0.0.1:8888 npm run test:visual
```

Regenerate the translation template after any user-facing string change:

```sh
composer i18n:pot
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
- `Publish tag release` is a safety net for pushed prerelease semver tags such as `v1.0.0-beta.1`. It creates or repairs the GitHub prerelease and uploads the verified installable ZIP so a prerelease tag does not remain tag-only. Stable `x.y.z` tags are published by the release PR/finalize flow, not by tag push.

All release publication flows build the clean package through `scripts/build-theme-zip.sh` and verify it through `scripts/verify-release-package.sh`. Stable and prerelease publications also attach a CycloneDX SBOM and keyless Sigstore bundle next to the installable ZIP.

Prerelease tags package the matching stable WordPress metadata version. For example, `v1.0.0-beta.1` installs as theme version `1.0.0` while GitHub marks the artifact as a prerelease for tester distribution.

## Privacy

The theme stores a first-party `hfb_theme` cookie for the light/dark preference. It contains only `light` or `dark`, lasts one year, uses `SameSite=Lax`, and is not used for tracking or shared with external services.

Public templates may render WordPress avatars for authors or commenters. The theme uses WordPress' configured avatar system; on default WordPress installs, visitors' browsers may request avatar images from Gravatar.

## Release Checklist

See [docs/release-checklist.md](docs/release-checklist.md).

## License

GPL-3.0-only. See [LICENSE](LICENSE).
