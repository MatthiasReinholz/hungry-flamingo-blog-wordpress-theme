/*! Hungry Flamingo Blog — color-scheme.js
 * Light/dark toggle, persisted in an hfb_theme cookie.
 * The initial scheme is already applied by the inline boot script in <head>
 * (see HFB\Assets::print_color_scheme_boot) to avoid FOUC.
 */
(function () {
	'use strict';

	var COOKIE  = 'hfb_theme';
	var TTL     = 31536000; // 1 year
	var html    = document.documentElement;
	var toggle  = document.getElementById('hfb-theme-toggle');
	if (!toggle) { return; }

	function readCookie(name) {
		var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
		return m ? decodeURIComponent(m[1]) : null;
	}
	function writeCookie(name, value) {
		// Skip redundant writes — cuts repeated Set-Cookie churn when the user
		// toggles the OS scheme back to a value we already have persisted.
		if (readCookie(name) === value) { return; }
		var expires = new Date(Date.now() + TTL * 1000).toUTCString();
		var secure  = (location.protocol === 'https:') ? '; Secure' : '';
		document.cookie = name + '=' + encodeURIComponent(value)
			+ '; expires=' + expires
			+ '; path=/; SameSite=Lax' + secure;
	}

	function setScheme(scheme, persist) {
		html.setAttribute('data-theme', scheme);
		toggle.setAttribute('aria-pressed', scheme === 'dark' ? 'true' : 'false');
		if (persist) {
			writeCookie(COOKIE, scheme);
		}
	}

	// Sync initial aria-pressed with whatever the boot script resolved.
	setScheme(html.getAttribute('data-theme') || 'light', false);

	toggle.addEventListener('click', function () {
		var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
		setScheme(next, true);
	});

	// Reflect OS changes only when the user hasn't explicitly chosen.
	if (window.matchMedia) {
		var mq = window.matchMedia('(prefers-color-scheme: dark)');
		mq.addEventListener && mq.addEventListener('change', function (e) {
			if (!readCookie(COOKIE)) {
				setScheme(e.matches ? 'dark' : 'light', false);
			}
		});
	}
}());
