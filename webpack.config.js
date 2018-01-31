/* global process __dirname */

const DEV = process.env.NODE_ENV !== 'production';

const path = require('path');
const webpack = require('webpack');

const jQuery = require.resolve('jquery');

const CleanWebpackPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const appPath = `${path.resolve(__dirname)}`;

// Plugin
const pluginPath = '/skin';
const pluginFullPath = `${appPath}${pluginPath}`;
const pluginPublicPath = `${pluginPath}/public/`;
const pluginEntry = `${pluginFullPath}/assets/application.js`;
const pluginOutput = `${pluginFullPath}/public`;


// Outputs
const outputJs = 'scripts/[name].js';
const outputCss = 'styles/[name].css';
const outputFile = '[name].[ext]';
const outputImages = `images/${outputFile}`;
const outputFonts = `fonts/${outputFile}`;

const allModules = {
  rules: [
    {
      test: /\.(js|jsx)$/,
      use: 'babel-loader',
      exclude: /node_modules/,
    },
    {
      test: /\.json$/,
      exclude: /node_modules/,
      use: 'file-loader',
    },
    {
      test: /\.(png|svg|jpg|jpeg|gif|ico)$/,
      exclude: [/fonts/, /node_modules/],
      use: `file-loader?name=${outputImages}`,
    },
    {
      test: /\.(eot|otf|ttf|woff|woff2|svg)$/,
      exclude: [/images/, /node_modules/],
      use: `file-loader?name=${outputFonts}`,
    },
    {
      test: /\.scss$/,
      exclude: /node_modules/,
      use: ExtractTextPlugin.extract({
        fallback: 'style-loader',
        use: ['css-loader', 'postcss-loader', 'sass-loader'],
      }),
    },
    {

      // Exposes jQuery for use outside Webpack build.
      test: jQuery,
      use: [{
        loader: 'expose-loader',
        options: 'jQuery',
      },
      {
        loader: 'expose-loader',
        options: '$',
      }],
    },
  ],
};

const allPlugins = [
  new ExtractTextPlugin(outputCss),

  new webpack.ProvidePlugin({
    $: 'jquery',
    jQuery: 'jquery',
  }),

  new webpack.DefinePlugin({
    'process.env': {
      NODE_ENV: JSON.stringify(process.env.NODE_ENV || 'development'),
    },
  }),
];

// Use only for production build
if (!DEV) {
  allPlugins.push(
    new CleanWebpackPlugin([pluginOutput]),
    new webpack.optimize.UglifyJsPlugin({
      output: {
        comments: false,
      },
      compress: {
        warnings: false,
        drop_console: true, // eslint-disable-line camelcase
      },
      sourceMap: true,
    })
  );
}

module.exports = [

  // Main Plugin
  {
    context: path.join(__dirname),
    entry: {
      'djc-application': [pluginEntry],
    },
    output: {
      path: pluginOutput,
      publicPath: pluginPublicPath,
      filename: outputJs,
    },

    module: allModules,

    plugins: allPlugins,

    devtool: DEV ? '#inline-source-map' : '',
  },
];
