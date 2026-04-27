#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
WP_PATH="${HFB_WP_PATH:-/tmp/wordpress}"
WP_CLI_PHAR="${HFB_WP_CLI_PHAR:-/tmp/wp-cli.phar}"
THEME_SLUG="${HFB_THEME_SLUG:-hungry-flamingo-blog}"
OUTPUT="${HFB_THEME_CHECK_OUTPUT:-$ROOT_DIR/dist/theme-check.json}"

if [ ! -d "$WP_PATH" ] || [ ! -f "$WP_PATH/wp-config.php" ]; then
	echo "WordPress fixture not found at $WP_PATH. Run scripts/setup-wordpress-visual-fixture.sh first." >&2
	exit 1
fi

if [ ! -f "$WP_CLI_PHAR" ]; then
	echo "WP-CLI not found at $WP_CLI_PHAR. Run scripts/setup-wordpress-visual-fixture.sh first." >&2
	exit 1
fi

WP_CLI=(php -d memory_limit=512M -d error_reporting=8191 "$WP_CLI_PHAR" --path="$WP_PATH" --allow-root)

"${WP_CLI[@]}" plugin install theme-check --activate
mkdir -p "$(dirname "$OUTPUT")"
HFB_THEME_CHECK_SLUG="$THEME_SLUG" "${WP_CLI[@]}" eval '
require_once WP_PLUGIN_DIR . "/theme-check/checkbase.php";
$slug   = getenv( "HFB_THEME_CHECK_SLUG" ) ?: "hungry-flamingo-blog";
$theme  = wp_get_theme( $slug );
$passed = run_themechecks_against_theme( $theme, $slug );
$text   = html_entity_decode( wp_strip_all_tags( display_themechecks() ), ENT_QUOTES );
echo wp_json_encode(
	array(
		"passed" => (bool) $passed,
		"text"   => $text,
	)
);
' > "$OUTPUT"

php -r '
$path = $argv[1];
$data = json_decode((string) file_get_contents($path), true);
if (! is_array($data) || ! isset($data["text"])) {
	fwrite(STDERR, "Theme Check output is not valid JSON.\n");
	exit(1);
}
$text = (string) $data["text"];
$blockers = preg_match_all("/REQUIRED:.*?(?=(?:REQUIRED|WARNING|INFO):|$)/s", $text, $matches) ? $matches[0] : [];
if ($blockers) {
	fwrite(STDERR, "Theme Check reported blocking findings:\n" . implode("\n\n", $blockers) . "\n");
	exit(1);
}
if (trim($text) !== "") {
	fwrite(STDOUT, trim($text) . "\n");
}
' "$OUTPUT"

echo "Theme Check passed without blocking findings."
