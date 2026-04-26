const { defineConfig, devices } = require('@playwright/test');

const baseURL = process.env.WP_BASE_URL || 'http://localhost:8888';

module.exports = defineConfig({
	testDir: './tests/visual',
	timeout: 30000,
	use: {
		baseURL,
		trace: 'on-first-retry'
	},
	projects: [
		{ name: 'desktop', use: { ...devices['Desktop Chrome'] } },
		{ name: 'mobile', use: { ...devices['Pixel 5'] } }
	]
});
