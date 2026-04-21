module.exports = {
	ignoreFiles: [
		'./assets/public/css/**/*.css',
		'./vendor/**/**/*.css',
		'./node_modules/**/**/*.css',
		'./tests/**/**/*.css',
	],
	extends: ['stylelint-config-standard-scss'],
	rules: {
		'no-descending-specificity': null,
		'selector-class-pattern': [
			'^[a-z][a-z0-9]*(?:[-_][a-z0-9]+)*(?:__(?:[a-z0-9]+(?:[-_][a-z0-9]+)*))?(?:--(?:[a-z0-9]+(?:[-_][a-z0-9]+)*))?$',
			{
				resolveNestedSelectors: true,
			},
		],
		'selector-id-pattern': '^[a-z][a-z0-9]*(?:[-_][a-z0-9]+)*$',
	},
};
