var Encore = require('@symfony/webpack-encore');
var Dotenv = require('dotenv-webpack');

// require offline-plugin
var OfflinePlugin = require('offline-plugin');
// manifest plugin
var ManifestPlugin = require('webpack-manifest-plugin');
var commonChunk = require("webpack/lib/optimize/CommonsChunkPlugin");
var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addPlugin(new Dotenv())
    .addPlugin(new CopyWebpackPlugin([
        { from: './assets/img', to: 'images' }
    ]))
    .addEntry('app', './assets/js/app.js')
    .addEntry('sw', './assets/js/sw.js')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

var config = Encore.getWebpackConfig();
config.plugins.push(new commonChunk({
    name: 'chunck',
    async: true
}));

// push offline-plugin it must be the last one to use
config.plugins.push(new OfflinePlugin({
    "strategy": "changed",
    "responseStrategy": "cache-first",
    "publicPath": "/build/",
    "caches": {
        // offline plugin doesn't know about build folder
        // if I added build in it , it will show something like : OfflinePlugin: Cache pattern [build/images/*] did not match any assets
        "main": [
            '*.json',
            '*.css',
            '*.js',
            'images/*'
        ]
    },
    "ServiceWorker": {
        "events": !Encore.isProduction(),
        "entry": "./assets/js/sw.js",
        "cacheName": "SymfonyVue",
        "navigateFallbackURL": '/',
        "minify": !Encore.isProduction(),
        "output": "./public/build/sw.js",
        "scope": "/"
    },
    "AppCache": null
}));



module.exports = config;
