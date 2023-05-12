const mix = require('laravel-mix');
mix.disableSuccessNotifications();
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/assets/sass/app.scss', 'public/css/app.css').version();
mix.sass('resources/assets/sass/login-page-shapes.scss', 'public/dist/css/login-page-shapes.css')
mix.styles([
    'resources/assets/pleasure-admin-panel/css/admin1.css',
    'resources/assets/pleasure-admin-panel/css/elements.css',
    'node_modules/jasny-bootstrap/dist/css/jasny-bootstrap.min.css',
    'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
    'node_modules/datatables/media/css/jquery.dataTables.min.css',
    'node_modules/icheck/skins/flat/_all.css'
], 'public/css/vendors.css')
    .sourceMaps()
    .webpackConfig({
        devtool: 'source-map'
    }).version();

const fontDirectories = {
    'resources/assets/pleasure-admin-panel/fonts': 'public/build/fonts',
    'resources/assets/pleasure-admin-panel/fontawesome': 'public/build/fontawesome',
    'resources/assets/pleasure-admin-panel/ionicons': 'public/build/ionicons'
};

for (const directory in fontDirectories) {
    mix.copy(directory, fontDirectories[directory]);
}

const files = {
    'node_modules/icheck/skins/flat/*.png': 'public/build/css',
    'node_modules/chosen-js/*.png': 'public/build/css'
};

for (const file in files) {
    mix.copy(file, files[file]);
}

mix.js([
        'node_modules/chosen-js/chosen.jquery.js',
        'node_modules/velocity-animate/velocity.min.js',
        'node_modules/moment/moment.js',
        'node_modules/toastr/toastr.js',
        'node_modules/scrollmonitor/scrollMonitor.js',
        'node_modules/textarea-autosize/dist/textarea-autosize.js',
        'node_modules/fastclick/lib/fastclick.js',
        'node_modules/jasny-bootstrap/dist/js/jasny-bootstrap.min.js',
        'node_modules/datatables/media/js/jquery.dataTables.min.js',
        'node_modules/icheck/icheck.min.js',
        'resources/assets/pleasure-admin-panel/js/sliders.js',
        'resources/assets/pleasure-admin-panel/js/layout.js',
        'resources/assets/pleasure-admin-panel/js/pleasure.js'
    ],
    'public/js/libs.js')
    .js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/controllers/equivalenceSetsController.js', 'public/js')
    .js('resources/assets/js/controllers/gameFlavorsController.js', 'public/js')
    .extract([
        'jquery', 'bootstrap', 'bootstrap-select'
    ])
    .sourceMaps()
    .webpackConfig({
        devtool: 'source-map'
    })
    .version();

mix.autoload({
    'jquery': ['$', 'window.jQuery', 'jQuery']
});
