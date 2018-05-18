// Get path
const path = require('path');

// Get webpack
const webpack = require('webpack');

// Webpack globs
const WebpackWatchedGlobEntries = require('webpack-watched-glob-entries-plugin');

// Css plugin
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

// Config
module.exports = (env) => {

    if (typeof env !== 'object' || !'env' in env) {
        env = {
            env: 'dev'
        };
    }

    return {

        // Watchoptions, enable this for running in vagrant
        // watchOptions: {
        //     poll: true,
        // },

        // Devtool
        devtool: 'source-map',

        // Entries
        entry: WebpackWatchedGlobEntries.getEntries(path.resolve(__dirname, 'src', 'Assets', 'Entry', '**', '*.js'), {ignore: '**/Bootstrap.js'}),

        // Output
        output: {
            filename: "[name].js",
            path: path.resolve(__dirname, 'private_html', 'dist'),
            chunkFilename: "[name].js",
            publicPath: "/",
        },

        // Mode
        mode: env.env === 'dev' ? 'development' : 'production',

        // Resolve
        resolve: {
            modules: [path.resolve(__dirname, 'vendor', 'node_modules'), path.resolve(__dirname, 'src', 'Assets'), 'node_modules'],
        },

        // Module
        module: {
            rules: [

                // SCSS rule
                {
                    test: /\.scss$|.css$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: "css-loader",
                            options: {
                                minimize: env.env !== 'dev',
                            },
                        },
                        'postcss-loader',
                        "sass-loader",
                    ]
                },

                // Babel rule
                {
                    test: /\.js$/,
                    use: [
                        "babel-loader",
                    ],

                },

                // Font and img rules
                {
                    test: /\.(eot|svg|ttf|woff|woff2|jpg|jpeg|png|gif)$/,
                    use: {
                        loader: "file-loader",
                        options: {
                            publicPath: './',
                            name: "[name].[ext]"
                        }
                    },
                }

            ],
        },

        // Optimization
        optimization: {
            runtimeChunk: {
                name: 'commons',
            },
            splitChunks: {
                chunks: "all",
                cacheGroups: {
                    vendors: false,
                    style: {
                        test: /\.scss$|.css$/,
                        name: "commons",
                        minChunks: 1,
                    },
                    commons: {
                        name: "commons",
                        chunks: "all",
                        minChunks: 2,
                        enforce: true
                    },
                },
            }
        },

        // Performance
        performance: {
            hints: false
        },

        // Stats
        stats: {
            // chunkModules: false,
            assets: false,
        },

        // Plugins
        plugins: [
            new MiniCssExtractPlugin({
                filename: "[name].css",
                chunkFilename: "[name].css"
            }),
            new WebpackWatchedGlobEntries(),
            new webpack.ProvidePlugin({
                $: "jquery",
                jQuery: "jquery",
                "window.jQuery": "jquery"
            }),
        ]
    };
};
