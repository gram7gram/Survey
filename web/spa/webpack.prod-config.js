var WebpackStripLoader = require('strip-loader');
var path = require('path');
var devConfig = require('./webpack.dev-config.js');
var webpack = require('webpack');

var prodConfig = devConfig;

prodConfig.module.loaders.push({
    test: /\.js$/,
    exclude: /node_modules/,
    loader: WebpackStripLoader.loader('console.log')
});

prodConfig.output = {
    path: __dirname + "/dist",
    filename: "[name].min.js"
};

prodConfig.plugins.push(new webpack.DefinePlugin({
    'process.env': {
        'NODE_ENV': '"production"'
    }
}));

prodConfig.plugins.push(new webpack.optimize.UglifyJsPlugin());

prodConfig.devtool = ''

module.exports = prodConfig;