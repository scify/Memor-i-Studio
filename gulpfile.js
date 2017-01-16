console.log(process.argv[3]);

if (process.argv[3] == '--local' || process.argv[2] == '--local') {
    process.env.DISABLE_NOTIFIER = true;
}

var elixir = require('laravel-elixir');

// Include gulp
var gulp = require('gulp');

elixir(function(mix) {

    mix.sass('app.scss',  'public/css/app.css');

    mix.styles([
        'resources/assets/pleasure-admin-panel/css/plugins.css',
        'resources/assets/pleasure-admin-panel/css/admin1.css',
        'resources/assets/pleasure-admin-panel/css/elements.css',
        'node_modules/sweetalert/dist/sweetalert.css',

        'node_modules/chosen-js/chosen.css',
        'node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',
        'node_modules/jasny-bootstrap/dist/css/jasny-bootstrap.min.css',
        'node_modules/bxslider/dist/jquery.bxslider.min.css',
        'node_modules/datatables/media/css/jquery.dataTables.min.css',
        'node_modules/icheck/skins/square/blue.css'
    ], 'public/css/vendors.css', './');

    var fontDirectories = {
        'resources/assets/pleasure-admin-panel/fonts': 'public/build/fonts',
        'resources/assets/pleasure-admin-panel/fontawesome': 'public/build/fontawesome'
    };

    for (var directory in fontDirectories) {
        mix.copy(directory, fontDirectories[directory]);
    }

    mix.scripts([
        'node_modules/npm-modernizr/modernizr.js',
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/jquery-ui-dist/jquery-ui.min.js',
        'node_modules/chosen-js/chosen.jquery.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/velocity-animate/velocity.min.js',
        'node_modules/moment/moment.js',
        'node_modules/toastr/toastr.js',
        'node_modules/scrollmonitor/scrollMonitor.js',
        'node_modules/textarea-autosize/dist/jquery.textarea_autosize.min.js',
        'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
        'node_modules/fastclick/lib/fastclick.js',
        'node_modules/bxslider/dist/jquery.bxslider.min.js',
        'node_modules/jquery-knob/dist/jquery.knob.min.js',
        'node_modules/jquery-knob/excanvas.js',
        'node_modules/html2canvas/dist/html2canvas.min.js',
        'node_modules/html2canvas/dist/html2canvas.svg.min.js',
        'node_modules/jasny-bootstrap/dist/js/jasny-bootstrap.min.js',
        'node_modules/sweetalert/dist/sweetalert.min.js',
        'node_modules/datatables/media/js/jquery.dataTables.min.js',
        'node_modules/icheck/icheck.min.js',
        'resources/assets/pleasure-admin-panel/js/sliders.js',
        'resources/assets/pleasure-admin-panel/js/layout.js',
        'resources/assets/pleasure-admin-panel/js/pleasure.js'
    ],
        'public/js/app.js' , './')
        .scripts([
            'resources/assets/js/controllers/'
        ], 'public/js/controllers.js');


    //the parameter is relative to the public directory
    mix.version(['css/app.css', 'css/vendors.css', 'js/app.js', 'js/controllers.js']);

    // mix.sass('app.scss',
    //     'public/assets/css/app.css')
    //     .styles([
    //         'libs/sweetalert.css'
    //     ],  'public/assets/css/libs.css')
    //     .scripts([
    //         'bootstrap.js'
    //     ],  'public/assets/js/app.js')
    //     .scripts([
    //         'libs/sweetalert-dev.js'
    //     ],  'public/assets/js/libs.js');
});