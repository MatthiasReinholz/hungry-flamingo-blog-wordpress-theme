/*! Hungry Flamingo Blog — mobile-menu.js
 * Opens/closes the full-screen mobile nav overlay. Clones the primary nav's
 * <a> items into the overlay so editors only maintain one menu in WP Admin.
 */
(function () {
	'use strict';

	var burger   = document.getElementById('hfb-burger');
	var closeBtn = document.getElementById('hfb-close-menu');
	var overlay  = document.getElementById('hfb-mobile-menu');
	if (!burger || !closeBtn || !overlay) { return; }

	var target = overlay.querySelector('[data-hfb-mobile-nav]');
	var primary = document.querySelector('.primary-nav');
	var lastFocus = null;
	var FOCUSABLE = 'a[href], button, [tabindex]:not([tabindex="-1"])';

	function getFocusable() {
		var nodes = overlay.querySelectorAll(FOCUSABLE);
		var list = [];
		Array.prototype.forEach.call(nodes, function (n) {
			// Skip hidden / disabled controls.
			if (n.disabled) { return; }
			if (n.offsetParent === null && n !== document.activeElement) { return; }
			list.push(n);
		});
		return list;
	}

	function trapKeydown(e) {
		if (e.key === 'Escape') {
			close();
			return;
		}
		if (e.key !== 'Tab') { return; }
		var focusables = getFocusable();
		if (!focusables.length) {
			e.preventDefault();
			return;
		}
		var first = focusables[0];
		var last  = focusables[focusables.length - 1];
		var active = document.activeElement;
		if (e.shiftKey) {
			if (active === first || !overlay.contains(active)) {
				e.preventDefault();
				last.focus();
			}
		} else {
			if (active === last) {
				e.preventDefault();
				first.focus();
			}
		}
	}

	// Populate mobile overlay from the primary nav on first open, if not seeded.
	function seed() {
		if (!target || target.childElementCount) { return; }
		if (!primary) { return; }
		var items = primary.querySelectorAll('a');
		Array.prototype.forEach.call(items, function (a, i) {
			var clone = document.createElement('a');
			var label = document.createElement('span');
			var num = document.createElement('span');

			clone.href = a.getAttribute('href') || '#';
			label.textContent = (a.textContent || '').trim();
			num.className = 'num';
			num.textContent = String(i + 1).padStart(2, '0');
			clone.appendChild(label);
			clone.appendChild(num);
			target.appendChild(clone);
		});
	}

	function open() {
		seed();
		lastFocus = document.activeElement;
		overlay.hidden = false;
		overlay.classList.add('open');
		burger.setAttribute('aria-expanded', 'true');
		document.body.style.overflow = 'hidden';
		var firstLink = overlay.querySelector('a, button');
		if (firstLink) { firstLink.focus(); }
		document.addEventListener('keydown', trapKeydown);
	}
	function close() {
		overlay.hidden = true;
		overlay.classList.remove('open');
		burger.setAttribute('aria-expanded', 'false');
		document.body.style.overflow = '';
		document.removeEventListener('keydown', trapKeydown);
		if (lastFocus && lastFocus.focus) {
			try { lastFocus.focus(); } catch { /* detached node; ignore */ }
		}
	}

	burger.addEventListener('click', open);
	closeBtn.addEventListener('click', close);
	overlay.addEventListener('click', function (e) {
		if (e.target && e.target.tagName === 'A') { close(); }
	});
}());
