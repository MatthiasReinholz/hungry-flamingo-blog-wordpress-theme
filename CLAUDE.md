# Claude Agent Notes

This file complements `AGENTS.md`. Read `AGENTS.md` first; it defines the theme/plugin boundary and required checks.

## Working Rules

- Use `rg`/`rg --files` for search.
- Keep the theme WordPress.org-compatible: templates, parts, patterns, `theme.json`, styles, and small progressive-enhancement scripts only.
- Custom blocks, REST endpoints, forms, analytics, durable data, and continuous-reading logic belong in the companion plugin.
- Do not enqueue remote assets. Fonts are local stacks, bundled assets, or WordPress core Font Library concerns.
- WooCommerce work in this repository is presentation-only.

## Common Commands

| Task | Command |
| --- | --- |
| Full local validation | `npm run validate` |
| PHP checks only | `composer test:php` |
| JS/CSS/unit checks | `npm test` |
| Build package | `npm run dist` |
| Verify built package | `HFB_VERIFY_SKIP_BUILD=1 npm run verify:dist` |
| Regenerate POT | `composer i18n:pot` |
| Visual/a11y smoke | `WP_BASE_URL=http://127.0.0.1:8888 npm run test:visual` |
| Fixture setup | `bash scripts/setup-wordpress-visual-fixture.sh` |

## File Map

- `templates/*.html`: FSE templates.
- `parts/*.html`: thin wrappers that load PHP patterns for translatable site chrome.
- `patterns/*.php`: reusable block patterns and translated pattern wrappers.
- `inc/class-assets.php`: front-end/editor assets, color boot script, dynamic link hydration.
- `inc/class-woocommerce.php`: WooCommerce presentation support and conditional styles.
- `assets/js/*.js`: progressive enhancement only.
- `scripts/*.sh`: release package, fixture, i18n, and verification automation.

## Translation Workflow

If you add user-facing text in PHP, wrap it with the `hungry-flamingo-blog` text domain. If the text must appear inside a template part or block-template area that cannot be translated from raw HTML, use a PHP pattern wrapper and include it from the `.html` template with:

```html
<!-- wp:pattern {"slug":"hungry-flamingo-blog/example-pattern"} /-->
```

After string changes, run:

```sh
composer i18n:pot
```

## Dynamic Links

Header/footer pattern links may include marker attributes such as `data-hfb-home-link`, `data-hfb-articles-link`, `data-hfb-feed-link`, and search form markers. `HFB\Assets::hydrate_dynamic_links()` rewrites those markers with WordPress-derived URLs using `WP_HTML_Tag_Processor`.

## Query IDs

Use high, documented `queryId` values for decorative/sidebar queries to avoid collisions with primary content queries. Existing sidebar related posts use `queryId:99`.
