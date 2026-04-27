#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
# shellcheck source=lib/meta.sh
. "$ROOT_DIR/scripts/lib/meta.sh"

POT_PATH="$ROOT_DIR/languages/${HFB_TEXT_DOMAIN}.pot"
BEFORE="$(mktemp)"
trap 'rm -f "$BEFORE"' EXIT

if [ -f "$POT_PATH" ]; then
	cp "$POT_PATH" "$BEFORE"
else
	: > "$BEFORE"
fi

bash "$ROOT_DIR/scripts/generate-pot.sh"

if ! cmp -s "$BEFORE" "$POT_PATH"; then
	echo "Translation template is stale. Review and commit languages/${HFB_TEXT_DOMAIN}.pot." >&2
	diff -u "$BEFORE" "$POT_PATH" || true
	exit 1
fi

echo "Translation template is current."
