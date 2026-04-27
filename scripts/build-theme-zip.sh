#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
# shellcheck source=lib/meta.sh
. "$ROOT_DIR/scripts/lib/meta.sh"
DIST_DIR="$ROOT_DIR/dist"
PACKAGE_ROOT="$DIST_DIR/package"
PACKAGE_DIR="$PACKAGE_ROOT/$HFB_SLUG"
EXCLUDES_FILE="$ROOT_DIR/.distignore"

rm -rf "$PACKAGE_DIR"
mkdir -p "$DIST_DIR" "$PACKAGE_ROOT" "$PACKAGE_DIR"

rsync -a \
	--exclude-from="$EXCLUDES_FILE" \
	--exclude="/.git/" \
	--exclude="/.distignore" \
	--exclude="/dist/" \
	"$ROOT_DIR/" "$PACKAGE_DIR/"

find "$PACKAGE_DIR" -type d -empty -delete

if [ ! -f "$PACKAGE_DIR/style.css" ] || [ ! -f "$PACKAGE_DIR/theme.json" ] || [ ! -f "$PACKAGE_DIR/templates/index.html" ]; then
	echo "Release package is missing required theme files." >&2
	exit 1
fi

rm -f "$DIST_DIR/$HFB_ZIP_FILE"
find "$PACKAGE_DIR" -exec touch -h -t 200001010000.00 {} +
(
	cd "$PACKAGE_ROOT"
	find "$HFB_SLUG" -print | LC_ALL=C sort | zip -X -q "$DIST_DIR/$HFB_ZIP_FILE" -@
)

echo "Created $DIST_DIR/$HFB_ZIP_FILE"
