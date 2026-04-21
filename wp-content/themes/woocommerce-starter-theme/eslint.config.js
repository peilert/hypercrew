const js = require('@eslint/js');
const babelParser = require('@babel/eslint-parser');

module.exports = [
	js.configs.recommended,
	{
		ignores: [
			'assets/**/*.js',
			'node_modules/**',
			'tests/**/*.js',
			'vendor/**',
			'temp.js',
		],
	},
	{
		files: ['_src/js/**/*.js', '_src/index.js'],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'module',
			parser: babelParser,
			parserOptions: {
				requireConfigFile: false,
				babelOptions: {
					configFile: false,
				},
			},
			globals: {
				AbortController: 'readonly',
				document: 'readonly',
				fetch: 'readonly',
				FormData: 'readonly',
				URL: 'readonly',
				window: 'readonly',
				wp: 'readonly',
				jQuery: 'readonly',
			},
		},
		rules: {
			'no-console': 'warn',
		},
	},
];
