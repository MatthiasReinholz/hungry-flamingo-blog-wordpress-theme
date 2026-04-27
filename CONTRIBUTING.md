# Contributing

## Local Checks

Run the checks before opening a pull request:

```sh
composer install
npm install
npm run validate
```

For browser smoke tests, prepare the local fixture and set `WP_BASE_URL`:

```sh
bash scripts/setup-wordpress-visual-fixture.sh
HFB_VISUAL_STRICT_WOO=1 WP_BASE_URL=http://127.0.0.1:8888 npm run test:visual
```

Run `composer i18n:pot` after adding or changing translatable strings. CI fails if `languages/hungry-flamingo-blog.pot` drifts from source.

Run `bash scripts/run-theme-check.sh` after the fixture is available when a change may affect WordPress.org review readiness.

## Scope

Keep theme code focused on presentation and templates. Newsletter capture, ecommerce, custom post types, and other durable site functionality belong in plugins.

## Release Changes

Stable releases should use the `Prepare release` and `Finalize release` workflows. Beta releases may use prerelease tags such as `v1.0.0-beta.1`, but the `Publish tag release` workflow must finish green and attach the verified installable ZIP to the GitHub prerelease.

New prerelease tags must be annotated. Lightweight tags are intentionally rejected by release publication and repair workflows.

When release metadata, privacy wording, or package contents change, keep `style.css`, `readme.txt`, `README.md`, `CHANGELOG.md`, `SECURITY.md`, `THIRD-PARTY-NOTICES.md`, and `docs/release-checklist.md` aligned.
