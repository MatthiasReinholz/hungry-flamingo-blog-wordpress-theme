# Contributing

## Local Checks

Run the checks before opening a pull request:

```sh
composer install
composer test:php
npm install
npm test
npm run dist
HFB_VERIFY_SKIP_BUILD=1 npm run verify:dist
```

For browser smoke tests, run a WordPress site with this theme active and set `WP_BASE_URL`:

```sh
WP_BASE_URL=http://localhost:8888 npm run test:visual
```

## Scope

Keep theme code focused on presentation and templates. Newsletter capture, ecommerce, custom post types, and other durable site functionality belong in plugins.

## Release Changes

Stable releases should use the `Prepare release` and `Finalize release` workflows. Beta releases may use prerelease tags such as `v1.0.0-beta.1`, but the `Publish tag release` workflow must finish green and attach the verified installable ZIP to the GitHub prerelease.

When release metadata, privacy wording, or package contents change, keep `style.css`, `readme.txt`, `README.md`, `CHANGELOG.md`, `SECURITY.md`, `THIRD-PARTY-NOTICES.md`, and `docs/release-checklist.md` aligned.
