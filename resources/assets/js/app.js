const {Pleasure} = require("../pleasure-admin-panel/js/pleasure");
const {Layout} = require("../pleasure-admin-panel/js/layout");
window._ = require('lodash');
let $ = require("jquery");
require('jquery-ui');
require('jquery-ui/ui/widgets/sortable');
require('jquery-ui/ui/disable-selection');
require('icheck');
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

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
    }, 3000);

    $("input[type='checkbox'], input[type='radio']").iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
});

