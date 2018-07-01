const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    entry: {
        main: './js/main.js'
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: '[name].js',
        publicPath: './dist'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                use: {
                    loader: "babel-loader",
                    options: { presets: ["es2015"] }
                }
            },
            {
                test: /\.(s?)css$/,
                use: [
                     MiniCssExtractPlugin.loader,
                    "css-loader",
                    "sass-loader"
                ],
                /*use: [
                    {
                        loader: "style-loader" // creates style nodes from JS strings
                    },
                    {
                        loader: "css-loader" // translates CSS into CommonJS
                    },
                    {
                        loader: "sass-loader"
                    }
                ]*/
            },
            {
                test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                use: [
                    {
                        loader: 'url-loader?limit=10000',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'fonts/'
                        }
                    }
                ]
            },
            {
                test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'fonts/'
                        }
                    }
                ]
            }
        ]
    },
    mode: 'production',
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery'
        }),
        new MiniCssExtractPlugin({
            // Options similar to the same options in webpackOptions.output
            // both options are optional
            filename: "css/[name].css",
            chunkFilename: "[id].css"
        })
    ]
};