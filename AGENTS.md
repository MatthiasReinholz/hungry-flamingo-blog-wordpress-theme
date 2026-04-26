# Repository Instructions for AI Coding Agents

## Scope

This repository is the Hungry Flamingo Blog WordPress theme. Keep it focused on presentation: block templates, template parts, patterns, `theme.json`, styling, and small progressive-enhancement scripts.

Feature logic that creates custom blocks, REST endpoints, data storage, forms, newsletter capture, analytics, or other plugin-territory behavior belongs in the companion plugin at:

`/Users/matthias/DEV/wordpress/plugins/hungry-flamingo-blog-wordpress-companion-plugin`

## Architecture Notes

- The theme is a block/FSE theme and should stay usable without the companion plugin.
- WooCommerce compatibility is theme-owned presentation only: support declarations, block templates, theme.json styles, and conditional styling belong here; store data, checkout behavior, payments, custom product logic, and REST endpoints stay in WooCommerce or plugins.
- Do not enqueue remote assets. Fonts must be self-hosted or left as local CSS stacks with documented licenses.
- Keep WooCommerce assets conditional to WooCommerce contexts so blog pages do not pay the store CSS cost.
- The only browser persistence currently allowed in the theme is the first-party `hfb_theme` color-scheme cookie. Update `README.md` and `readme.txt` when privacy behavior changes.
- Keep hardcoded brand/site URLs out of templates where a WordPress-derived value can be passed through `HFB_UI` or represented with a core block.

## Required Checks

Run these before handing off theme changes:

```sh
composer test:php
npm test
npm run dist
```

Visual smoke tests require a running WordPress site:

```sh
WP_BASE_URL=http://localhost:8888 npm run test:visual
```

## Release Hygiene

- Keep `style.css`, `readme.txt`, `README.md`, `CHANGELOG.md`, `LICENSE`, `THIRD-PARTY-NOTICES.md`, and `docs/release-checklist.md` aligned.
- Check the release ZIP contents after `npm run dist`.
- Do not commit `dist/`, `vendor/`, or `node_modules/`.
