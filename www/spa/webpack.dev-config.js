var webpack = require('webpack');
var path = require('path');
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var autoprefixer = require('autoprefixer');

module.exports = {
    entry: {
        Survey: "./src/Survey"
    },
    output: {
        path: __dirname + "/dist",
        filename: "[name].js"
    },
    module: {
        loaders: [
            {
                test: /.js?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['es2015', 'stage-0', 'react',],
                    plugins: ['transform-decorators-legacy']
                }
            }, {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract("style-loader", "css-loader")
            }, {
                test: /(\.scss)$/,
                loader: ExtractTextPlugin.extract('style', 'css?sourceMap&modules&importLoaders=1&localIdentName=[name]__[local]___[hash:base64:5]!postcss!sass')
            },
            {
                test: /\.png$/,
                loader: "url-loader?limit=10000&mimetype=image/png"
            },
            {
                test: /\.jsx$/,
                exclude: [/node_modules/],
                loader: "babel-loader",
                query: {
                    presets: ['es2015', 'stage-0', 'react'],
                    plugins: ['transform-decorators-legacy']
                }
            }
        ]
    },
    postcss: [autoprefixer],
    plugins: [
        new ExtractTextPlugin("[name].css")
    ],
    devtool: 'source-map'
};