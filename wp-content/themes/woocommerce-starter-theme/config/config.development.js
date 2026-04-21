/**
 * Webpack configurations for the development environment
 * Run with: "npm run dev" or "npm run dev:watch"
 *
 * @since 2.0.0
 */

const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');

module.exports = (projectOptions) => {

  process.env.NODE_ENV = 'development';

  const Base = require('./config.base')(projectOptions);

  const cssRules = {
    ...Base.cssRules,
  };

  const jsRules = {
    ...Base.jsRules,
  };

  const imageRules = {
    ...Base.imageRules,
  };

  const fontRules = {
    ...Base.fontRules,
  };

  const optimizations = {
    ...Base.optimizations,
  };

  const plugins = [
    ...Base.plugins,
  ];

  // Add eslint to plugins if enabled
  if (projectOptions.projectJs.eslint === true) {
    plugins.push(new ESLintPlugin());
  }
  // Add stylelint to plugins if enabled
  if (projectOptions.projectCss.stylelint === true) {
    plugins.push(new StylelintPlugin());
  }

  if (projectOptions.browserSync.enable === true) {
    const browserSyncOptions = {
      files: projectOptions.browserSync.files,
      host: projectOptions.browserSync.host,
      port: projectOptions.browserSync.port,
    };

    if (projectOptions.browserSync.mode === 'server') {
      Object.assign(browserSyncOptions, { server: projectOptions.browserSync.server });
    } else {
      Object.assign(browserSyncOptions, { proxy: projectOptions.browserSync.proxy });
    }

    plugins.push(new BrowserSyncPlugin(browserSyncOptions, { reload: projectOptions.browserSync.reload }));
  }

  /***
   * Add sourcemap for development if enabled
   */
  const sourceMap = { devtool: false };
  if (projectOptions.projectSourceMaps.enable === true && (
      projectOptions.projectSourceMaps.env === 'dev' || projectOptions.projectSourceMaps.env === 'dev-prod'
  )) {
    sourceMap.devtool = projectOptions.projectSourceMaps.devtool;
  }

  return {
    mode: 'development',
    entry: projectOptions.projectJs.entry,
    output: {
      path: projectOptions.output,
      filename: projectOptions.projectJs.filename
    },
    devtool: sourceMap.devtool,
    optimization: optimizations,
    module: { rules: [cssRules, jsRules, imageRules, fontRules], },
    plugins: plugins,
  };
};
