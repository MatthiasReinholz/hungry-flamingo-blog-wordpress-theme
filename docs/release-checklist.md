# Release Checklist

- Confirm all files are tracked and there are no accidental local artifacts.
- Run PHP syntax checks, PHPCS, JavaScript lint, CSS lint, and visual smoke tests.
- Test on the minimum supported WordPress and PHP versions, plus the latest WordPress stable.
- Test front page, blog home, single post, page, category, tag, author, date, search, and 404 templates.
- With WooCommerce active, test shop archive, product category/tag archive, product search, single product, cart, checkout, order confirmation, account page, notices, product gallery, and add-to-cart flow.
- Test single posts with and without the Hungry Flamingo Blog Companion plugin active.
- Test mobile menu, search dialog, dark/light toggle, comments, pagination, no-featured-image posts, and empty archives.
- Confirm `screenshot.png` matches the actual theme appearance and is no larger than 1200 x 900.
- Confirm `style.css`, `readme.txt`, `README.md`, `CHANGELOG.md`, and `LICENSE` agree on version, requirements, and license.
- Confirm third-party asset notices are complete.
- Confirm `languages/hungry-flamingo-blog.pot` is regenerated after user-facing string changes.
- Build the release ZIP with `npm run dist` and verify it with `npm run verify:dist`.
- Confirm the installable artifact is `dist/hungry-flamingo-blog.zip` or `dist/package/hungry-flamingo-blog`, not the repository root.
- If continuous reading is part of the release story, test the Hungry Flamingo Blog Companion plugin separately.
- Confirm the release ZIP excludes development-only files such as `AGENTS.md`, CI config, tests, Composer files, npm files, and local tooling.
- Confirm keyboard access, skip link, search dialog focus handling, mobile menu focus handling, and visible focus states.
- Confirm WooCommerce product cards, add-to-cart controls, notices, cart quantity controls, checkout fields, mobile cart submit container, and order confirmation blocks pass keyboard and accessibility smoke checks.
- Release through the `Prepare release`, `Finalize release`, or `Release repair` GitHub Actions workflows so GitHub Release assets are produced from the verified package.
