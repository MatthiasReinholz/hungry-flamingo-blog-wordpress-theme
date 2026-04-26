# Contributing

## Local Checks

Run the checks before opening a pull request:

```sh
composer install
composer lint:php
npm install
npm run lint:js
npm run lint:css
```

For browser smoke tests, run a WordPress site with this theme active and set `WP_BASE_URL`:

```sh
WP_BASE_URL=http://localhost:8888 npm run test:visual
```

## Scope

Keep theme code focused on presentation and templates. Newsletter capture, ecommerce, custom post types, and other durable site functionality belong in plugins.
