var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('assets/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    // .addEntry('js/singleAds', './assets/js/Controller/SingleAdsController.js')
    .addEntry('js/app', [
        './assets/js/index.js'
    ])

    // .createSharedEntry('js/vendor', ['jquery', 'bootstrap'])
    .addStyleEntry('css/app',[
        './assets/scss/_index.scss',
    ])
    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
        'window.$': 'jquery',
    })
    // .addLoader({
    //     test: /\.(png|jp(e*)g|svg)$/,
    //     use: [{
    //         loader: 'url-loader',
    //         options: {
    //             limit: 10000, // Convert images < 8kb to base64 strings
    //             name: 'images/[name].[hash].[ext]',
    //         }
    //     }]
    // })
;

let config = Encore.getWebpackConfig();

module.exports = config;
