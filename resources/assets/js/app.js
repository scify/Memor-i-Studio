import $ from "./jquery-global";
import "bootstrap-sass";
import "bootstrap-select";
import "icheck";
import "jasny-bootstrap/dist/js/jasny-bootstrap.js";
import {Pleasure} from "../pleasure-admin-panel/js/pleasure";
import {Layout} from "../pleasure-admin-panel/js/layout";
import * as Sentry from "@sentry/browser";

if (import.meta.env.VITE_SENTRY_DSN_PUBLIC) {
    Sentry.init({
        dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
    });
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
