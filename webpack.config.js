/* global process __dirname */

const DEV = process.env.NODE_ENV !== 'production';

const path = require('path');
const webpack = require('webpack');

const CleanWebpackPlugin = require('clean-webpack-plugin');

const appPath = `${path.resolve(__dirname)}`;

// Plugin
const pluginPath = '/skin';
const pluginFullPath = `${appPath}${pluginPath}`;
const pluginPublicPath = `${pluginPath}/public/`;
const pluginEntry = `${pluginFullPath}/assets/application.js`;
const pluginOutput = `${pluginFullPath}/public`;

// Outputs
const outputJs = 'scripts/[name].js';

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
  ],
};

const allPlugins = [

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
