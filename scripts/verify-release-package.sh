#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
# shellcheck source=lib/meta.sh
. "$ROOT_DIR/scripts/lib/meta.sh"
DIST_DIR="$ROOT_DIR/dist"
PACKAGE_DIR="$ROOT_DIR/$HFB_PACKAGE_DIR"
ZIP_FILE="$DIST_DIR/$HFB_ZIP_FILE"

if [ "${HFB_VERIFY_SKIP_BUILD:-}" != "1" ]; then
	npm run dist >/dev/null
fi

required_files=(
	"$PACKAGE_DIR/style.css"
	"$PACKAGE_DIR/theme.json"
	"$PACKAGE_DIR/readme.txt"
	"$PACKAGE_DIR/screenshot.png"
	"$PACKAGE_DIR/languages/hungry-flamingo-blog.pot"
	"$PACKAGE_DIR/assets/css/woocommerce.css"
	"$PACKAGE_DIR/templates/index.html"
	"$PACKAGE_DIR/templates/archive-product.html"
	"$PACKAGE_DIR/templates/single-product.html"
	"$PACKAGE_DIR/templates/page-cart.html"
	"$PACKAGE_DIR/templates/page-checkout.html"
	"$PACKAGE_DIR/templates/order-confirmation.html"
	"$PACKAGE_DIR/templates/product-search-results.html"
)

for file in "${required_files[@]}"; do
	if [ ! -f "$file" ]; then
		echo "Release package is missing ${file#$PACKAGE_DIR/}." >&2
		exit 1
	fi
done

allowed_top_level=(
	"LICENSE"
	"README.md"
	"THIRD-PARTY-NOTICES.md"
	"assets"
	"functions.php"
	"inc"
	"languages"
	"parts"
	"patterns"
	"readme.txt"
	"screenshot.png"
	"styles"
	"style.css"
	"templates"
	"theme.json"
)

while IFS= read -r item; do
	name="$(basename "$item")"
	allowed=false
	for expected in "${allowed_top_level[@]}"; do
		if [ "$name" = "$expected" ]; then
			allowed=true
			break
		fi
	done
	if [ "$allowed" != "true" ]; then
		echo "Release package contains unexpected top-level item: $name" >&2
		exit 1
	fi
done < <(find "$PACKAGE_DIR" -mindepth 1 -maxdepth 1)

zip_listing="$(mktemp)"
trap 'rm -f "$zip_listing"' EXIT
unzip -Z1 "$ZIP_FILE" > "$zip_listing"

required_zip_entries=(
	"$HFB_SLUG/style.css"
	"$HFB_SLUG/theme.json"
	"$HFB_SLUG/readme.txt"
	"$HFB_SLUG/screenshot.png"
	"$HFB_SLUG/languages/hungry-flamingo-blog.pot"
	"$HFB_SLUG/assets/css/woocommerce.css"
	"$HFB_SLUG/templates/archive-product.html"
	"$HFB_SLUG/templates/single-product.html"
	"$HFB_SLUG/templates/page-cart.html"
	"$HFB_SLUG/templates/page-checkout.html"
	"$HFB_SLUG/templates/order-confirmation.html"
	"$HFB_SLUG/templates/product-search-results.html"
)

for entry in "${required_zip_entries[@]}"; do
	if ! grep -Fxq "$entry" "$zip_listing"; then
		echo "Release ZIP is missing $entry." >&2
		exit 1
	fi
done

forbidden_pattern='(^|/)(\.git|\.github|AGENTS\.md|composer\.json|composer\.lock|package\.json|package-lock\.json|phpcs\.xml\.dist|playwright\.config\.js|eslint\.config\.mjs|tests|scripts|docs|node_modules|vendor)(/|$)'
if grep -E "$forbidden_pattern" "$zip_listing"; then
	echo "Release ZIP contains development-only files." >&2
	exit 1
fi

if find "$PACKAGE_DIR" -type f | grep -E "$forbidden_pattern"; then
	echo "Release package contains development-only files." >&2
	exit 1
fi

if grep -RInE 'register_block_type|hfb/post-stack' "$PACKAGE_DIR"; then
	echo "Release package contains theme-side custom block functionality." >&2
	exit 1
fi

if grep -RInE 'fonts\.googleapis\.com|fonts\.gstatic\.com' "$PACKAGE_DIR"; then
	echo "Release package loads remote Google Fonts." >&2
	exit 1
fi

if grep -RInE 'https?://' "$PACKAGE_DIR/assets" "$PACKAGE_DIR/parts" "$PACKAGE_DIR/patterns" "$PACKAGE_DIR/templates"; then
	echo "Release package contains runtime remote URL references in templates, patterns, or assets." >&2
	exit 1
fi

grep -Fq 'hfb_theme' "$PACKAGE_DIR/readme.txt"
file "$ROOT_DIR/screenshot.png" | grep -F '1200 x 900' >/dev/null

screenshot_bytes="$(wc -c < "$PACKAGE_DIR/screenshot.png" | tr -d ' ')"
if [ "$screenshot_bytes" -gt 150000 ]; then
	echo "screenshot.png is ${screenshot_bytes} bytes; keep it at or below 150 KB." >&2
	exit 1
fi

while IFS= read -r asset; do
	bytes="$(wc -c < "$asset" | tr -d ' ')"
	case "$asset" in
		*.css) budget=70000 ;;
		*.js) budget=20000 ;;
		*) continue ;;
	esac
	if [ "$bytes" -gt "$budget" ]; then
		echo "Release asset ${asset#$PACKAGE_DIR/} is ${bytes} bytes; budget is ${budget} bytes." >&2
		exit 1
	fi
done < <(find "$PACKAGE_DIR/assets/css" "$PACKAGE_DIR/assets/js" -type f \( -name '*.css' -o -name '*.js' \))

echo "Verified $ZIP_FILE and $PACKAGE_DIR"
