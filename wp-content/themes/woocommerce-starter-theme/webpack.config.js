/**
 * This is a main entrypoint for Webpack config.
 *
 * @since 2.0.0
 */
 const path = require('path');
 const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");
 const { WebpackManifestPlugin } = require('webpack-manifest-plugin');

 const themePaths = {
   dir: __dirname,
   indexPath: path.resolve(__dirname, '_src'),
   jsPath: path.resolve(__dirname, '_src/js'),
   scssPath: path.resolve(__dirname, '_src/scss'),
   imagesPath: path.resolve(__dirname, '_src/images'),
   fontsPath: path.resolve(__dirname, '_src/fonts'),
   output: path.resolve(__dirname, 'assets'),
   webpack: path.resolve(__dirname, 'config'),
 };

 const themeFiles = {
   // BrowserSync
   browserSync: {
     enable: true,
     host: 'localhost',
     port: 3000,
     mode: 'proxy',
     proxy: 'http://your-project.local',
     files: '**/**/**.php',
     reload: true,
   },

   // JavaScript
   projectJs: {
     eslint: true,
     filename: '[name].js',
     entry: {
       main: themePaths.indexPath + '/index.js',
     },
     rules: {
       test: /\.m?js$/,
     }
   },

   // SCSS / PostCSS
   projectCss: {
     postCss: themePaths.webpack + '/postcss.config.js',
     stylelint: true,
     filename: '[name].css',
     use: 'sass',
     rules: {
       sass: {
         test: /\.s[ac]ss$/i
       },
       postcss: {
         test: /\.pcss$/i
       }
     },
   },

   // Source maps
   projectSourceMaps: {
     enable: true,
     env: 'prod',
     devtool: 'source-map'
   },

   // IMAGES (PNG, JPG, GIF, WEBP, SVG)
   projectImages: {
    rules: {
      test: /\.(png|jpe?g|gif|webp|svg)$/i,
      type: 'asset/resource',
      generator: {
        filename: (pathData) => {
          // Zrób ścieżkę względem _src i zamień backslash na slash
          const relativePath = path
            .relative('_src', path.dirname(pathData.filename))
            .replace(/\\/g, '/');
      
          return `${relativePath}/[name].[contenthash][ext]`;
        }
      },
    },
     minimizer: {
       implementation: ImageMinimizerPlugin.imageminMinify,
       options: {
         plugins: [
           ["gifsicle", { interlaced: true }],
           ["mozjpeg", { progressive: true, quality: 75 }],
           ["optipng", { optimizationLevel: 5 }],
         ],
       },
     },
   },

   projectPlugins: [
     new WebpackManifestPlugin({
       fileName: 'manifest.json',
       publicPath: '/assets/',
       generate: (seed, files) => {
         return files.reduce((manifest, file) => {
           if (!file.path || file.path.endsWith('.map') || !/\.(css|js)$/i.test(file.path)) {
             return manifest;
           }

           manifest[file.name] = file.path;
           return manifest;
         }, seed);
       },
     }),
   ],
 };

 const projectOptions = {
   ...themePaths,
   ...themeFiles,
 };

 module.exports = env => {
   const configFile = env.NODE_ENV === 'production'
     ? './config/config.production'
     : './config/config.development';

   const config = require(configFile)(projectOptions);

   if (!config.plugins) config.plugins = [];
   config.plugins.push(...projectOptions.projectPlugins);

   return config;
 };
