var elixir = require('laravel-elixir');

elixir(function(mix) {

    mix.styles([
        'node_modules/sweetalert/dist/sweetalert.css',
        'node_modules/chosen-js/chosen.css',
        'node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',
        'node_modules/switchery/switchery.css',
        'node_modules/jasny-bootstrap/dist/css/jasny-bootstrap.min.css',
        'node_modules/rickshaw/rickshaw.min.css',
        'node_modules/bxslider/dist/jquery.bxslider.min.css',
        'node_modules/datatables/media/css/jquery.dataTables.min.css',
        'node_modules/icheck/skins/square/blue.css',
        'public/assets/pleasure-admin-panel/css/elements.css',
        'public/assets/pleasure-admin-panel/css/plugins.css',
        'public/assets/pleasure-admin-panel/css/admin1.css',
        'public/css/style.css'
    ], 'public/dist/app.css', './');

    mix.scripts([
        'node_modules/npm-modernizr/modernizr.js',
        'node_modules/jquery/dist/jquery.min.js',
        'public/assets/pleasure-admin-panel/js/global-vendors.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/bxslider/dist/jquery.bxslider.min.js',
        'node_modules/jquery-knob/dist/jquery.knob.min.js',
        'node_modules/jquery-knob/excanvas.js',
        'node_modules/html2canvas/dist/html2canvas.min.js',
        'node_modules/html2canvas/dist/html2canvas.svg.min.js',
        'node_modules/gauge-js/dest/gauge.min.js',
        'node_modules/jasny-bootstrap/dist/js/jasny-bootstrap.min.js',
        'node_modules/sweetalert/dist/sweetalert.min.js',
        'node_modules/datatables/media/js/jquery.dataTables.min.js',
        'node_modules/icheck/icheck.min.js',
        'public/assets/pleasure-admin-panel/js/sliders.js',
        'public/assets/pleasure-admin-panel/js/layout.js',
        'public/assets/pleasure-admin-panel/js/pleasure.js'
    ], 'public/dist/app.js' , './');

    //the parameter is relative to the public directory
    mix.version(['dist/app.css', 'dist/app.js']);

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