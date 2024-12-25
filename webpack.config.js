const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const webpack = require('webpack');
const WPDependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';

    return {
        mode: isProduction ? 'production' : 'development',
        entry: {
            index: './src/index.js',  // Frontend und Editor Script
            'editor-styles': './src/editor-styles.js',  // Editor Styles
        },
        output: {
            path: path.resolve(__dirname, 'build'),
            filename: '[name].js',
        },
        devtool: isProduction ? 'source-map' : 'inline-source-map',
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env', '@babel/preset-react'],
                            plugins: ['@babel/plugin-transform-class-properties'],
                        },
                    },
                },
                {
                    test: /\.scss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                sourceMap: true,
                            },
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                sourceMap: true,
                                sassOptions: {
                                    includePaths: [
                                        path.resolve(__dirname, 'src/styles'),
                                        path.resolve(__dirname, 'node_modules'),
                                    ],
                                },
                            },
                        },
                    ],
                },
                {
                    test: /\.(png|jpg|gif|svg)$/,
                    use: [
                        {
                            loader: 'file-loader',
                            options: {
                                name: '[name].[ext]',
                                outputPath: 'images/',
                            },
                        },
                    ],
                },
                {
                    test: /block\.json$/,
                    type: 'javascript/auto',
                    use: [
                        {
                            loader: 'file-loader',
                            options: {
                                name: '[name].[ext]',
                                outputPath: 'blocks/',
                            },
                        },
                    ],
                },
            ],
        },
        plugins: [
            new CleanWebpackPlugin(),
            new MiniCssExtractPlugin({
                filename: '[name].css',  // [name] wird entweder 'index.css' oder 'editor-styles.css'
            }),
            new webpack.DefinePlugin({
                'process.env.THEME_STYLES_URL': JSON.stringify(process.env.THEME_STYLES_URL || '')
            }),
            new WPDependencyExtractionWebpackPlugin({
                injectPolyfill: true,
                combineAssets: true,
            }),
        ],
        externals: {
            '@wordpress/blocks': ['wp', 'blocks'],
            '@wordpress/block-editor': ['wp', 'blockEditor'],
            '@wordpress/components': ['wp', 'components'],
            '@wordpress/element': ['wp', 'element'],
            '@wordpress/i18n': ['wp', 'i18n'],
        },
        resolve: {
            extensions: ['.js', '.jsx', '.scss', '.css'],
            alias: {
                '@styles': path.resolve(__dirname, 'src/styles'),
                '@theme': path.resolve(__dirname, '../../themes/sunflower/src'),
            },
        },
    };
};
