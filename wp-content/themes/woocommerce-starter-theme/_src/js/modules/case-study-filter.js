const SELECTORS = {
	root: '[data-case-study-filter]',
	select: '[data-case-study-filter-select]',
	results: '[data-case-study-filter-results]',
};

class CaseStudyFilter {
	constructor({ select, results, ajaxUrl, nonce }) {
		this.select = select;
		this.results = results;
		this.ajaxUrl = ajaxUrl;
		this.nonce = nonce;
		this.abortController = null;
		this.handleChange = this.handleChange.bind(this);
	}

	init() {
		this.select.addEventListener('change', this.handleChange);
	}

	async handleChange() {
		this.syncBrowserUrl();
		this.setLoadingState(true);

		if (this.abortController) {
			this.abortController.abort();
		}

		const controller = new AbortController();
		this.abortController = controller;

		try {
			const response = await fetch(this.ajaxUrl, {
				method: 'POST',
				body: this.createRequestBody(),
				credentials: 'same-origin',
				signal: controller.signal,
			});

			if (!response.ok) {
				throw new Error(`Unexpected status: ${response.status}`);
			}

			const payload = await response.json();
			this.renderResponse(payload);
		} catch (error) {
			if (error.name !== 'AbortError') {
				this.renderErrorState();
			}
		} finally {
			if (this.abortController === controller) {
				this.abortController = null;
				this.setLoadingState(false);
			}
		}
	}

	createRequestBody() {
		const formData = new FormData();
		formData.append('action', 'starter_filter_case_studies');
		formData.append('nonce', this.nonce);
		formData.append('industry', this.select.value || 'all');

		return formData;
	}

	renderResponse(payload) {
		if (!payload || !payload.success || !payload.data || typeof payload.data.html !== 'string') {
			throw new Error('Invalid response payload.');
		}

		this.results.innerHTML = payload.data.html;
	}

	renderErrorState() {
		this.results.innerHTML =
			'<p class="case-studies__empty">Wystapil problem podczas filtrowania. Sprobuj ponownie.</p>';
	}

	setLoadingState(isLoading) {
		this.results.classList.toggle('is-loading', isLoading);
		this.results.setAttribute('aria-busy', isLoading ? 'true' : 'false');
		this.select.disabled = isLoading;
	}

	syncBrowserUrl() {
		const nextUrl = new URL(window.location.href);

		if (!this.select.value || this.select.value === 'all') {
			nextUrl.searchParams.delete('industry');
		} else {
			nextUrl.searchParams.set('industry', this.select.value);
		}

		window.history.replaceState({}, '', nextUrl);
	}
}

export function initCaseStudyFilter() {
	const config = window.starterThemeData?.caseStudyFilter;

	if (!config) {
		return;
	}

	const root = document.querySelector(SELECTORS.root);
	const select = root ? root.querySelector(SELECTORS.select) : null;
	const results = document.querySelector(SELECTORS.results);

	if (!root || !select || !results) {
		return;
	}

	const filter = new CaseStudyFilter({
		select,
		results,
		ajaxUrl: config.ajaxUrl,
		nonce: config.nonce,
	});

	filter.init();
}
