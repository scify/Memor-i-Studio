const {Pleasure} = require("../pleasure-admin-panel/js/pleasure");
const {Layout} = require("../pleasure-admin-panel/js/layout");
// window._ = require('lodash'); // Removed: conflicts with vendor scripts and is not used
let $ = require("jquery");
require('jquery-ui');
require('jquery-ui/ui/widgets/sortable');
require('jquery-ui/ui/disable-selection');
require('icheck');
require('bootstrap');
require('bootstrap-select');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

import * as Sentry from "@sentry/browser";

if (process.env.MIX_SENTRY_DSN_PUBLIC) {
    Sentry.init({
        dsn: process.env.MIX_SENTRY_DSN_PUBLIC,
    });
}

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = $ = require('jquery');
} catch (e) {
    console.error(e);
}

$(document).ready(function () {
    Pleasure.init();
    Layout.init();
    $("[id^=tooltip-]").tooltip();
    setTimeout(function () {
        /*Close any flash message after some time*/
        $(".alert-dismissable").fadeTo(4000, 500).slideUp(500, function () {
            $(".alert-dismissable").alert('close');
        });
    }, 5000);

    $("input[type='checkbox'], input[type='radio']").iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
