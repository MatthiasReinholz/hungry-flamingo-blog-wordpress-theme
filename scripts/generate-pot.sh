#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
# shellcheck source=lib/meta.sh
. "$ROOT_DIR/scripts/lib/meta.sh"

cd "$ROOT_DIR"

export WP_CLI_PHP_ARGS="${WP_CLI_PHP_ARGS:--d error_reporting=8191}"

vendor/bin/wp i18n make-pot . "languages/${HFB_TEXT_DOMAIN}.pot" \
	--slug="$HFB_SLUG" \
	--domain="$HFB_TEXT_DOMAIN" \
	--exclude=".github,dist,docs,node_modules,scripts,tests,vendor" \
	--headers='{"Report-Msgid-Bugs-To":"https://github.com/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme/issues","POT-Creation-Date":""}' \
	--package-name="Hungry Flamingo Blog" \
	--skip-audit
