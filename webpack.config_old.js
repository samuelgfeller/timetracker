var Encore = require('@symfony/webpack-encore');

// var $ = require('jquery');
// window.$ = $;
// window.jQuery = $;

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
     .addEntry('js/app', './assets/js/app.js')
     .addEntry('js/ort', './assets/js/ort.js')
     .addEntry('js/timetracker', './assets/js/timetracker.js')
      .addEntry('images/sunset', './assets/img/ant.jpg')
    .addEntry()

    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/loader', './assets/css/loader.scss')

    // uncomment if you use Sass/SCSS files
     .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
     .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
// const encoreConfig =Encore.getWebpackConfig();
// const jqueryConfig = {
//     entry: 'jquery', //will load jquery from node_modules
//     output: {
//         filename: 'jqueryOutput.js'
//     }
// };
// module.exports = [
//     encoreConfig,
//     jqueryConfig
// ];
/*
plugins: [
    new webpack.ProvidePlugin({
        $: "jquery",
        jQuery: "jquery",
        "window.jQuery": "jquery"
    })
]*/
