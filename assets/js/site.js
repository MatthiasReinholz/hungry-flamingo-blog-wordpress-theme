/*! Hungry Flamingo Blog — site.js
 * Small progressive-enhancement behaviors for search, dynamic links, and
 * article sidebar content.
 */
(function () {
	'use strict';

	var cfg = window.HFB_UI || {};

	function updateGeneratedLinks() {
		var homeUrl = cfg.homeUrl || '/';
		var feedUrl = cfg.feedUrl || '/feed/';

		document.querySelectorAll('[data-hfb-home-link]').forEach(function (link) {
			link.href = homeUrl;
		});
		document.querySelectorAll('[data-hfb-feed-link]').forEach(function (link) {
			link.href = feedUrl;
		});
		document.querySelectorAll('[data-hfb-search-link]').forEach(function (link) {
			link.href = homeUrl + (homeUrl.indexOf('?') === -1 ? '?s=' : '&s=');
		});
		document.querySelectorAll('[data-hfb-search-form]').forEach(function (form) {
			form.action = homeUrl;
		});
		document.querySelectorAll('[data-hfb-site-name]').forEach(function (el) {
			el.textContent = cfg.siteName || document.title || 'This site';
		});
	}

	function initSearchDialog() {
		var trigger = document.querySelector('[data-hfb-search]');
		var dialog = document.getElementById('hfb-search-dialog');
		if (!trigger || !dialog) { return; }

		var closeBtn = dialog.querySelector('[data-hfb-search-close]');
		var input = dialog.querySelector('[data-hfb-search-input]');
		var lastFocus = null;
		var focusable = 'a[href], button, input, [tabindex]:not([tabindex="-1"])';

		function close() {
			dialog.hidden = true;
			document.body.style.overflow = '';
			document.removeEventListener('keydown', onKeydown);
			if (lastFocus && lastFocus.focus) {
				try { lastFocus.focus(); } catch { /* detached node */ }
			}
		}

		function open() {
			lastFocus = document.activeElement;
			dialog.hidden = false;
			document.body.style.overflow = 'hidden';
			document.addEventListener('keydown', onKeydown);
			if (input) { input.focus(); }
		}

		function onKeydown(e) {
			if (e.key === 'Escape') {
				close();
				return;
			}
			if (e.key !== 'Tab') { return; }

			var nodes = Array.prototype.slice.call(dialog.querySelectorAll(focusable)).filter(function (node) {
				return !node.disabled && node.offsetParent !== null;
			});
			if (!nodes.length) {
				e.preventDefault();
				return;
			}

			var first = nodes[0];
			var last = nodes[nodes.length - 1];
			if (e.shiftKey && (document.activeElement === first || !dialog.contains(document.activeElement))) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
		}

		trigger.addEventListener('click', open);
		if (closeBtn) { closeBtn.addEventListener('click', close); }
		dialog.addEventListener('click', function (e) {
			if (e.target === dialog) { close(); }
		});
	}

	function initArticleSidebar() {
		var article = document.querySelector('.hfb-article--main');
		if (!article) { return; }

		initToc(article);
		initAuthorCard(article);
	}

	function initToc(article) {
		var toc = document.querySelector('[data-hfb-toc]');
		if (!toc) { return; }

		var widget = toc.closest('.widget');
		var headings = Array.prototype.slice.call(article.querySelectorAll('.hfb-article__body h2, .hfb-article__body h3'));
		if (!headings.length) {
			if (widget) { widget.hidden = true; }
			return;
		}

		headings.forEach(function (heading, index) {
			if (!heading.id) {
				heading.id = 'hfb-section-' + String(index + 1);
			}

			var item = document.createElement('li');
			var link = document.createElement('a');
			link.href = '#' + heading.id;
			link.textContent = heading.textContent.trim();
			if (heading.tagName === 'H3') { item.className = 'nested'; }
			item.appendChild(link);
			toc.appendChild(item);
		});

		if (typeof window.IntersectionObserver !== 'function') { return; }

		var links = Array.prototype.slice.call(toc.querySelectorAll('a'));
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) { return; }
				links.forEach(function (link) {
					link.parentNode.classList.toggle('active', link.hash.slice(1) === entry.target.id);
				});
			});
		}, { rootMargin: '0px 0px -75% 0px', threshold: 0 });

		headings.forEach(function (heading) { observer.observe(heading); });
	}

	function initAuthorCard(article) {
		var target = document.querySelector('[data-hfb-author-card]');
		if (!target) { return; }

		var widget = target.closest('.widget');
		var card = article.querySelector('.hfb-article__author-card');
		if (!card) {
			if (widget) { widget.hidden = true; }
			return;
		}

		var clone = card.cloneNode(true);
		if (!clone.textContent.trim() && !clone.querySelector('img')) {
			if (widget) { widget.hidden = true; }
			return;
		}

		clone.classList.add('hfb-article__author-card--sidebar');
		target.appendChild(clone);
	}

	updateGeneratedLinks();
	initSearchDialog();
	initArticleSidebar();
}());
