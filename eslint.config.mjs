import js from '@eslint/js';

export default [
	js.configs.recommended,
	{
		files: ['assets/js/**/*.js', 'tests/visual/**/*.js'],
		languageOptions: {
			ecmaVersion: 2020,
			sourceType: 'script',
			globals: {
				CustomEvent: 'readonly',
				HFB_CR: 'readonly',
				HFB_UI: 'readonly',
				IntersectionObserver: 'readonly',
				URLSearchParams: 'readonly',
				console: 'readonly',
				document: 'readonly',
				fetch: 'readonly',
				history: 'readonly',
				location: 'readonly',
				navigator: 'readonly',
				requestAnimationFrame: 'readonly',
				require: 'readonly',
				setTimeout: 'readonly',
				window: 'readonly'
			}
		},
		rules: {
			'no-var': 'off',
			'prefer-const': 'off'
		}
	}
];
