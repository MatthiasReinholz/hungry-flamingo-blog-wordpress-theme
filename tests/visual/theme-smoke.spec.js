const { test, expect } = require('@playwright/test');
const { AxeBuilder } = require('@axe-core/playwright');

async function runA11y(page, context = null) {
	let builder = new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa']);

	if (context) {
		builder = builder.include(context);
	}

	const results = await builder.analyze();
	expect(results.violations, JSON.stringify(results.violations, null, 2)).toEqual([]);
}

async function skipUnlessPresent(page, selector, message) {
	const count = await page.locator(selector).count();
	test.skip(count === 0, message);
}

async function expectWooStylesheet(page) {
	await expect(page.locator('link[href*="woocommerce.css"]').first()).toHaveAttribute('href', /woocommerce\.css/);
}

test.describe('Hungry Flamingo Blog smoke checks', () => {
	test('front page renders and core controls respond', async ({ page }) => {
		await page.goto('/');
		await expect(page.locator('.site-header')).toBeVisible();
		await expect(page.locator('.site-footer')).toBeVisible();

		await page.locator('[data-hfb-search]').click();
		await expect(page.locator('#hfb-search-dialog')).toBeVisible();
		await page.keyboard.press('Escape');
		await expect(page.locator('#hfb-search-dialog')).toBeHidden();

		await page.locator('#hfb-theme-toggle').click();
		await expect(page.locator('html')).toHaveAttribute('data-theme', /dark|light/);

		await runA11y(page);
	});

	test('single posts do not show empty sidebar placeholders', async ({ page }) => {
		const response = await page.request.get('/?rest_route=/wp/v2/posts&per_page=1&_fields=link');
		test.skip(!response.ok(), 'WordPress REST posts endpoint is not available.');

		const posts = await response.json();
		test.skip(!Array.isArray(posts) || posts.length === 0, 'No published posts are available for single-post smoke checks.');

		await page.goto(posts[0].link);

		const toc = page.locator('[data-hfb-toc]').first();
		if (await toc.count()) {
			const tocHidden = await toc.evaluate((el) => Boolean(el.closest('.widget')?.hidden));
			if (!tocHidden) {
				await expect(toc.locator('li').first()).toBeVisible();
			}
		}

		const authorCard = page.locator('[data-hfb-author-card]').first();
		if (await authorCard.count()) {
			const authorHidden = await authorCard.evaluate((el) => Boolean(el.closest('.widget')?.hidden));
			if (!authorHidden) {
				await expect(authorCard).not.toBeEmpty();
			}
		}
	});

	test('woocommerce catalog, product, cart, and checkout render', async ({ page }) => {
		await page.goto('/?post_type=product');
		await skipUnlessPresent(page, '.hfb-store-layout', 'WooCommerce product archive is not available.');
		await expect(page.locator('.hfb-store-layout').first()).toBeVisible();
		await expectWooStylesheet(page);
		const productHeading = page.getByRole('heading', { name: 'Smoke Product' }).first();
		await expect(productHeading).toBeVisible();
		const catalogProductId = await page.locator('.hfb-store-layout [data-product_id]').first().getAttribute('data-product_id').catch(() => null);
		await runA11y(page, '.hfb-store-layout');

		await page.goto('/?s=Smoke&post_type=product');
		await skipUnlessPresent(page, '.hfb-store-layout', 'WooCommerce product search is not available.');
		await expect(page.locator('.hfb-store-layout').first()).toBeVisible();
		await expect(page.getByRole('heading', { name: 'Smoke Product' }).first()).toBeVisible();
		await expectWooStylesheet(page);
		await runA11y(page, '.hfb-store-layout');

		const productLink = productHeading.locator('a').first();
		const productHref = await productLink.count() ? await productLink.getAttribute('href') : '/?product=smoke-product';

		await page.goto(productHref || '/?product=smoke-product');
		await expect(page.locator('.hfb-product-page, .single-product, .wp-block-add-to-cart-form, form.cart').first()).toBeVisible();
		await expect(page.getByText('Smoke Product').first()).toBeVisible();
		await expectWooStylesheet(page);

		const addToCart = page.locator(
			'.wp-block-add-to-cart-form button[type="submit"], form.cart button[type="submit"], .single_add_to_cart_button, .add_to_cart_button'
		).first();
		await expect(addToCart).toBeVisible();
		if (catalogProductId) {
			await page.goto(`/?add-to-cart=${encodeURIComponent(catalogProductId)}`);
		} else {
			await addToCart.click();
			await page.waitForLoadState('networkidle', { timeout: 10000 }).catch(() => undefined);
		}

		await page.goto('/?pagename=cart');
		await expect(page.locator('.hfb-cart-page, .wc-block-cart, .wp-block-woocommerce-cart, .woocommerce-cart-form').first()).toBeVisible();
		await expect(page.getByText('Smoke Product').first()).toBeVisible();
		await expectWooStylesheet(page);
		await runA11y(page, '.hfb-cart-page');

		await page.goto('/?pagename=checkout');
		await expect(page.locator('.hfb-checkout-page, .wc-block-checkout, .wp-block-woocommerce-checkout, form.checkout').first()).toBeVisible();
		await expectWooStylesheet(page);
		await runA11y(page, '.hfb-checkout-page');
	});
});
