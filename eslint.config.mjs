import js from '@eslint/js';
import globals from 'globals';

export default [
	js.configs.recommended,
	{
		ignores: ['dist/**', 'node_modules/**', 'vendor/**', 'playwright-report/**', 'test-results/**']
	},
	{
		files: ['assets/js/**/*.js'],
		languageOptions: {
			ecmaVersion: 2020,
			sourceType: 'script',
			globals: {
				...globals.browser,
				CustomEvent: 'readonly',
				HFB_CR: 'readonly',
				HFB_UI: 'readonly',
				IntersectionObserver: 'readonly',
				requestAnimationFrame: 'readonly',
				require: 'readonly'
			}
		},
		rules: {
			eqeqeq: ['error', 'always'],
			'no-implicit-globals': 'error',
			'no-var': 'off',
			'prefer-const': 'off'
		}
	},
	{
		files: ['tests/visual/**/*.js'],
		languageOptions: {
			ecmaVersion: 2020,
			sourceType: 'commonjs',
			globals: {
				...globals.browser,
				...globals.node
			}
		},
		rules: {
			eqeqeq: ['error', 'always']
		}
	},
	{
		files: ['tests/js/**/*.js'],
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: 'module',
			globals: {
				...globals.browser
			}
		},
		rules: {
			eqeqeq: ['error', 'always']
		}
	}
];
