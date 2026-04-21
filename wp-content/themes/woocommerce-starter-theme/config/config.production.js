/**
 * Webpack configurations for the production environment
 * Run with: "npm run prod" or or "npm run prod:watch"
 *
 * @since 2.0.0
 */
 const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");
 
 module.exports = (projectOptions) => {
 
   process.env.NODE_ENV = 'production';
 
   const Base = require('./config.base')(projectOptions);
 
   const cssRules = {
     ...Base.cssRules,
   };
 
   const jsRules = {
     ...Base.jsRules,
   };
 
   const imageRules = Base.imageRules;
 
   const fontRules = {
     ...Base.fontRules,
   };
 
   const optimizations = {
     ...Base.optimizations,
     splitChunks: {
       cacheGroups: {
         styles: {
           name: 'styles',
           test: /\.css$/,
           chunks: 'all',
           enforce: true,
         },
       },
     },
     minimizer: [
       new ImageMinimizerPlugin({
         minimizer: {
           implementation: ImageMinimizerPlugin.imageminMinify,
           options: {
             plugins: [
               ['gifsicle', { interlaced: true }],
               ['mozjpeg', { progressive: true, quality: 75 }],
               ['optipng', { optimizationLevel: 5 }],
             ],
           },
         },
       }),
     ],
   };
 
  const plugins = [
    ...Base.plugins,
  ];
 
   const sourceMap = { devtool: false };
   if (
     projectOptions.projectSourceMaps.enable === true &&
     (projectOptions.projectSourceMaps.env === 'prod' || projectOptions.projectSourceMaps.env === 'dev-prod')
   ) {
     sourceMap.devtool = projectOptions.projectSourceMaps.devtool;
   }
 
   return {
     mode: 'production',
     entry: projectOptions.projectJs.entry,
     output: {
       path: projectOptions.output,
       filename: projectOptions.projectJs.filename,
     },
     devtool: sourceMap.devtool,
     optimization: optimizations,
     module: { rules: [cssRules, jsRules, imageRules, fontRules] },
     plugins: plugins,
   };
 };
 
