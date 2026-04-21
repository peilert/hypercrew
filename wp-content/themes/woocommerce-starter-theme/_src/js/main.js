import { initCaseStudyFilter } from './modules/case-study-filter.js';

const initializers = [initCaseStudyFilter];

const boot = () => {
	initializers.forEach((initializer) => {
		if (typeof initializer === 'function') {
			initializer();
		}
	});
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
	boot();
}
