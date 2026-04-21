/**
 * PostCSS configuration file
 * as configured under cssRules.use['postcss-loader'] in development.js and production.js
 *
 * @docs https://postcss.org/
 * @since 2.0.0
 */

module.exports = (projectOptions) => {
  const postcssOptions = {};
  if (projectOptions.projectCss.use === 'sass') {
    Object.assign(postcssOptions, {
      plugins: [
        // To parse CSS and add vendor prefixes to CSS rules using values from Can I Use.
        // https://github.com/postcss/autoprefixer
        require('autoprefixer'),
      ],
    });
  }
  return {
    postcssOptions: postcssOptions
  };
};
