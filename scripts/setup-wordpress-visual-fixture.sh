#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
WP_PATH="${HFB_WP_PATH:-/tmp/wordpress}"
WP_URL="${HFB_WP_URL:-http://127.0.0.1:8888}"
THEME_SOURCE="${HFB_THEME_SOURCE:-$ROOT_DIR}"
SERVER_HOST="${HFB_WP_SERVER_HOST:-127.0.0.1}"
SERVER_PORT="${HFB_WP_SERVER_PORT:-8888}"
SERVER_LOG="${HFB_WP_SERVER_LOG:-/tmp/wp-server.log}"
WP_CLI_PHAR="${HFB_WP_CLI_PHAR:-/tmp/wp-cli.phar}"
WP_CLI_VERSION="${HFB_WP_CLI_VERSION:-2.11.0}"
WP_CLI_SHA512="${HFB_WP_CLI_SHA512:-adb12146bab8d829621efed41124dcd0012f9027f47e0228be7080296167566070e4a026a09c3989907840b21de94b7a35f3bfbd5f827c12f27c5803546d1bba}"
WP_VERSION="${HFB_WP_VERSION:-6.9.4}"
WOO_VERSION="${HFB_WOOCOMMERCE_VERSION:-10.7.0}"

if [ ! -f "$THEME_SOURCE/style.css" ] || [ ! -f "$THEME_SOURCE/theme.json" ]; then
	echo "HFB_THEME_SOURCE must point to an installable Hungry Flamingo Blog theme directory." >&2
	exit 1
fi

rm -rf "$WP_PATH"
mkdir -p "$WP_PATH"

curl -fsSL "https://github.com/wp-cli/wp-cli/releases/download/v${WP_CLI_VERSION}/wp-cli-${WP_CLI_VERSION}.phar" -o "$WP_CLI_PHAR"
echo "${WP_CLI_SHA512}  ${WP_CLI_PHAR}" | sha512sum -c -

WP_CLI=(php -d memory_limit=512M -d error_reporting=8191 "$WP_CLI_PHAR" --path="$WP_PATH" --allow-root)

"${WP_CLI[@]}" core download --force --version="$WP_VERSION"
"${WP_CLI[@]}" config create --dbname=wordpress --dbuser=root --dbpass=root --dbhost=127.0.0.1 --skip-check
# The fixture uses disposable local credentials against the CI/local MySQL service.
php -r '
mysqli_report( MYSQLI_REPORT_OFF );
$mysqli = @new mysqli( "127.0.0.1", "root", "root" );
if ( $mysqli->connect_errno ) {
	fwrite( STDERR, "Could not connect to MySQL fixture at 127.0.0.1: " . $mysqli->connect_error . PHP_EOL );
	exit( 1 );
}
if ( ! $mysqli->query( "DROP DATABASE IF EXISTS wordpress" ) ) {
	fwrite( STDERR, "Could not reset fixture database: " . $mysqli->error . PHP_EOL );
	exit( 1 );
}
if ( ! $mysqli->query( "CREATE DATABASE wordpress CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci" ) ) {
	fwrite( STDERR, "Could not create fixture database: " . $mysqli->error . PHP_EOL );
	exit( 1 );
}
'
"${WP_CLI[@]}" core install --url="$WP_URL" --title="Hungry Flamingo Smoke" --admin_user=admin --admin_password=password --admin_email=admin@example.com
"${WP_CLI[@]}" option update home "$WP_URL"
"${WP_CLI[@]}" option update siteurl "$WP_URL"

ln -s "$THEME_SOURCE" "$WP_PATH/wp-content/themes/hungry-flamingo-blog"
"${WP_CLI[@]}" plugin install woocommerce --version="$WOO_VERSION" --activate
"${WP_CLI[@]}" theme activate hungry-flamingo-blog

"${WP_CLI[@]}" post create --post_type=post --post_status=publish --post_title="Smoke post" --post_content='<!-- wp:heading --><h2>Smoke section</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Smoke body for visual checks.</p><!-- /wp:paragraph -->'
SHOP_ID=$("${WP_CLI[@]}" post create --post_type=page --post_status=publish --post_title="Shop" --post_name=shop --porcelain)
CART_ID=$("${WP_CLI[@]}" post create --post_type=page --post_status=publish --post_title="Cart" --post_name=cart --post_content='<!-- wp:woocommerce/cart {"align":"wide"} /-->' --porcelain)
CHECKOUT_ID=$("${WP_CLI[@]}" post create --post_type=page --post_status=publish --post_title="Checkout" --post_name=checkout --post_content='<!-- wp:woocommerce/checkout {"align":"wide","showFormStepNumbers":true} /-->' --porcelain)
ACCOUNT_ID=$("${WP_CLI[@]}" post create --post_type=page --post_status=publish --post_title="My account" --post_name=my-account --post_content='<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->' --porcelain)

"${WP_CLI[@]}" option update woocommerce_shop_page_id "$SHOP_ID"
"${WP_CLI[@]}" option update woocommerce_cart_page_id "$CART_ID"
"${WP_CLI[@]}" option update woocommerce_checkout_page_id "$CHECKOUT_ID"
"${WP_CLI[@]}" option update woocommerce_myaccount_page_id "$ACCOUNT_ID"
"${WP_CLI[@]}" option update woocommerce_coming_soon no || true

PRODUCT_ID=$("${WP_CLI[@]}" post create --post_type=product --post_status=publish --post_title="Smoke Product" --post_name=smoke-product --post_content='<!-- wp:paragraph --><p>A test product used by visual smoke checks.</p><!-- /wp:paragraph -->' --post_excerpt="Smoke product excerpt." --porcelain)
"${WP_CLI[@]}" post meta update "$PRODUCT_ID" _regular_price 19.00
"${WP_CLI[@]}" post meta update "$PRODUCT_ID" _sale_price 15.00
"${WP_CLI[@]}" post meta update "$PRODUCT_ID" _price 15.00
"${WP_CLI[@]}" post meta update "$PRODUCT_ID" _stock_status instock
"${WP_CLI[@]}" post meta update "$PRODUCT_ID" _manage_stock no
"${WP_CLI[@]}" term get product_type simple --by=slug >/dev/null 2>&1 || "${WP_CLI[@]}" term create product_type simple --slug=simple
"${WP_CLI[@]}" post term set "$PRODUCT_ID" product_type simple
"${WP_CLI[@]}" term get product_cat smoke-category --by=slug >/dev/null 2>&1 || "${WP_CLI[@]}" term create product_cat "Smoke Category" --slug=smoke-category
"${WP_CLI[@]}" post term add "$PRODUCT_ID" product_cat smoke-category

nohup php -d memory_limit=512M -S "$SERVER_HOST:$SERVER_PORT" -t "$WP_PATH" >"$SERVER_LOG" 2>&1 < /dev/null &
server_pid="$!"
echo "$server_pid" > /tmp/hfb-wp-server.pid
disown "$server_pid" 2>/dev/null || true

for _ in {1..30}; do
	if curl -fsS "$WP_URL" >/dev/null; then
		exit 0
	fi
	sleep 1
done

cat "$SERVER_LOG"
exit 1
