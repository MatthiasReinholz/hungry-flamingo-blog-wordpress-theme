import { beforeEach, describe, expect, it, vi } from 'vitest';
import { readFileSync } from 'node:fs';
import { resolve } from 'node:path';

function runAsset(path) {
	window.eval(readFileSync(resolve(path), 'utf8'));
}

describe('color scheme toggle', () => {
	beforeEach(() => {
		document.body.innerHTML = '<button id="hfb-theme-toggle" aria-pressed="false"></button>';
		document.documentElement.setAttribute('data-theme', 'light');
		Object.defineProperty(window, 'matchMedia', {
			value: vi.fn(() => ({
				addEventListener: vi.fn(),
				matches: false
			})),
			writable: true
		});
	});

	it('does not write a cookie until the visitor chooses', () => {
		runAsset('assets/js/color-scheme.js');

		expect(document.cookie).toBe('');

		document.getElementById('hfb-theme-toggle').click();

		expect(document.documentElement.getAttribute('data-theme')).toBe('dark');
		expect(document.cookie).toContain('hfb_theme=dark');
	});
});

describe('mobile menu', () => {
	beforeEach(() => {
		document.body.innerHTML = `
			<nav class="primary-nav">
				<a href="/articles">Articles</a>
				<a href="/unsafe"><img src="x" onerror="alert(1)">Unsafe</a>
			</nav>
			<button id="hfb-burger" aria-expanded="false"></button>
			<div id="hfb-mobile-menu" hidden>
				<button id="hfb-close-menu"></button>
				<nav data-hfb-mobile-nav></nav>
			</div>
		`;
	});

	it('clones nav links with text nodes instead of HTML', () => {
		runAsset('assets/js/mobile-menu.js');

		document.getElementById('hfb-burger').click();
		const overlay = document.getElementById('hfb-mobile-menu');
		const nav = overlay.querySelector('[data-hfb-mobile-nav]');

		expect(overlay.hidden).toBe(false);
		expect(nav.textContent).toContain('Unsafe');
		expect(nav.innerHTML).not.toContain('onerror');
		expect(document.getElementById('hfb-burger').getAttribute('aria-expanded')).toBe('true');
	});
});

describe('site enhancements', () => {
	beforeEach(() => {
		window.HFB_UI = {
			articlesUrl: 'https://example.test/articles/',
			feedUrl: 'https://example.test/feed/',
			homeUrl: 'https://example.test/',
			searchUrl: 'https://example.test/?s=',
			siteName: 'Hungry Flamingo'
		};
		document.body.innerHTML = `
			<a data-hfb-home-link href="/">Articles</a>
			<a data-hfb-feed-link href="/feed/">RSS</a>
			<button data-hfb-search>Search</button>
			<div id="hfb-search-dialog" hidden>
				<button data-hfb-search-close>Close</button>
				<form data-hfb-search-form action="/"><input data-hfb-search-input></form>
			</div>
			<article class="hfb-article hfb-article--main">
				<div class="hfb-article__body"><h2>Intro</h2><h3>Detail</h3></div>
				<aside class="hfb-article__author-card">Author bio</aside>
			</article>
			<div class="widget"><ul data-hfb-toc></ul></div>
			<div class="widget"><div data-hfb-author-card></div></div>
		`;
	});

	it('hydrates links and sidebar content', () => {
		runAsset('assets/js/site.js');

		expect(document.querySelector('[data-hfb-home-link]').href).toBe('https://example.test/articles/');
		expect(document.querySelector('[data-hfb-feed-link]').href).toBe('https://example.test/feed/');
		expect(document.querySelector('[data-hfb-toc]').children).toHaveLength(2);
		expect(document.querySelector('[data-hfb-author-card]').textContent).toContain('Author bio');
	});

	it('opens and closes the search dialog', () => {
		runAsset('assets/js/site.js');

		const dialog = document.getElementById('hfb-search-dialog');
		document.querySelector('[data-hfb-search]').click();
		expect(dialog.hidden).toBe(false);

		document.querySelector('[data-hfb-search-close]').click();
		expect(dialog.hidden).toBe(true);
	});
});
