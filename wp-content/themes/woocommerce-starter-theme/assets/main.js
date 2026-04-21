/******/ (() => { // webpackBootstrap
/******/ 	"use strict";

;// ./_src/js/modules/case-study-filter.js
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var SELECTORS = {
  root: '[data-case-study-filter]',
  select: '[data-case-study-filter-select]',
  results: '[data-case-study-filter-results]'
};
class CaseStudyFilter {
  constructor(_ref) {
    var {
      select,
      results,
      ajaxUrl,
      nonce
    } = _ref;
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
  handleChange() {
    var _this = this;
    return _asyncToGenerator(function* () {
      _this.syncBrowserUrl();
      _this.setLoadingState(true);
      if (_this.abortController) {
        _this.abortController.abort();
      }
      var controller = new AbortController();
      _this.abortController = controller;
      try {
        var response = yield fetch(_this.ajaxUrl, {
          method: 'POST',
          body: _this.createRequestBody(),
          credentials: 'same-origin',
          signal: controller.signal
        });
        if (!response.ok) {
          throw new Error("Unexpected status: ".concat(response.status));
        }
        var payload = yield response.json();
        _this.renderResponse(payload);
      } catch (error) {
        if (error.name !== 'AbortError') {
          _this.renderErrorState();
        }
      } finally {
        if (_this.abortController === controller) {
          _this.abortController = null;
          _this.setLoadingState(false);
        }
      }
    })();
  }
  createRequestBody() {
    var formData = new FormData();
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
    this.results.innerHTML = '<p class="case-studies__empty">Wystapil problem podczas filtrowania. Sprobuj ponownie.</p>';
  }
  setLoadingState(isLoading) {
    this.results.classList.toggle('is-loading', isLoading);
    this.results.setAttribute('aria-busy', isLoading ? 'true' : 'false');
    this.select.disabled = isLoading;
  }
  syncBrowserUrl() {
    var nextUrl = new URL(window.location.href);
    if (!this.select.value || this.select.value === 'all') {
      nextUrl.searchParams.delete('industry');
    } else {
      nextUrl.searchParams.set('industry', this.select.value);
    }
    window.history.replaceState({}, '', nextUrl);
  }
}
function initCaseStudyFilter() {
  var _window$starterThemeD;
  var config = (_window$starterThemeD = window.starterThemeData) === null || _window$starterThemeD === void 0 ? void 0 : _window$starterThemeD.caseStudyFilter;
  if (!config) {
    return;
  }
  var root = document.querySelector(SELECTORS.root);
  var select = root ? root.querySelector(SELECTORS.select) : null;
  var results = document.querySelector(SELECTORS.results);
  if (!root || !select || !results) {
    return;
  }
  var filter = new CaseStudyFilter({
    select,
    results,
    ajaxUrl: config.ajaxUrl,
    nonce: config.nonce
  });
  filter.init();
}
;// ./_src/js/main.js

var initializers = [initCaseStudyFilter];
var boot = () => {
  initializers.forEach(initializer => {
    if (typeof initializer === 'function') {
      initializer();
    }
  });
};
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot, {
    once: true
  });
} else {
  boot();
}
;// ./_src/index.js



/******/ })()
;
//# sourceMappingURL=main.js.map