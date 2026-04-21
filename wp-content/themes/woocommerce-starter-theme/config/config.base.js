/**
 * This holds the configuration that is being used for both development and production.
 *
 * @since 2.0.0
 */
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackBar = require('webpackbar');
const webpack = require('webpack');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');

module.exports = (projectOptions) => {
  const cssRules = {
    test: projectOptions.projectCss.use === 'sass' ? projectOptions.projectCss.rules.sass.test : projectOptions.projectCss.rules.postcss.test,
    exclude: /(node_modules|vendor)/,
    use: [
      MiniCssExtractPlugin.loader,
      'css-loader',
      {
        loader: 'postcss-loader',
        options:
            require(projectOptions.projectCss.postCss)(projectOptions)
      }
    ],
  };

  if (projectOptions.projectCss.use === 'sass') {
    cssRules.use.push({
      loader: 'sass-loader',
      options: {
        sassOptions: {
          quietDeps: true
        }
      }
    });
  }

  const jsRules = {
    test: projectOptions.projectJs.rules.test,
    include: projectOptions.jsPath,
    exclude: /(node_modules|vendor)/,
    use: 'babel-loader' // Configurations in "config/babel.config.js"
  };

  const imageRules = {
    ...projectOptions.projectImages.rules,
  };


  /**
   * Font rules
   */
  const fontRules = {
    test: /\.(eot|ttf|woff|woff2)$/i,
    use: [
      {
        loader: 'file-loader',
        options: {
          publicPath: '/assets/fonts/',
          outputPath: 'fonts/',
          name: '[name].[ext]',
        },
      },
    ],
  };

  const optimizations = {
    minimizer: [
      "...",
      new ImageMinimizerPlugin({
        minimizer: projectOptions.projectImages.minimizer,
        // loader: false
      }),
    ],
  };

  const plugins = [
    new WebpackBar(),
    new MiniCssExtractPlugin({
      filename: projectOptions.projectCss.filename
    }),
    new CopyPlugin({
      patterns: [
        // { from: projectOptions.imagesPath, to: 'images', noErrorOnMissing: true },
        { from: projectOptions.fontsPath, to: 'fonts', noErrorOnMissing: true },
      ],
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      bootstrap: ['bootstrap']
    }),
  ];

  return {
    cssRules: cssRules,
    jsRules: jsRules,
    imageRules: imageRules,
    fontRules: fontRules,
    optimizations: optimizations,
    plugins: plugins
  };
};
