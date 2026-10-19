import $ from "jquery";

// Bootstrap 3, the jQuery plugins and inline Blade scripts read the global jQuery, so set it before they evaluate.
window.$ = window.jQuery = $;

export default $;
