const { defineConfig, devices } = require('@playwright/test');

const baseURL = process.env.WP_BASE_URL || 'http://127.0.0.1:8888';

module.exports = defineConfig({
	testDir: './tests/visual',
	timeout: 30000,
	retries: process.env.CI ? 2 : 0,
	reporter: process.env.CI ? [['html', { outputFolder: 'playwright-report', open: 'never' }], ['list']] : 'list',
	use: {
		baseURL,
		trace: 'on-first-retry'
	},
	projects: [
		{ name: 'desktop', use: { ...devices['Desktop Chrome'] } },
		{ name: 'mobile', use: { ...devices['Pixel 5'] } }
	]
});
