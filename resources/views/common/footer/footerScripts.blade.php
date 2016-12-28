<script src="{{asset("/assets/globals/plugins/modernizr/modernizr.min.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/jquery/dist/jquery.min.js")}}"></script>
<!-- BEGIN GLOBAL AND THEME VENDORS -->
<script src="{{asset("/assets/globals/js/global-vendors.js")}}"></script>
<!-- END GLOBAL AND THEME VENDORS -->

<!-- BEGIN PLUGINS AREA -->
<script src="{{asset("/assets/globals/plugins/bootstrap/dist/js/bootstrap.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/bxslider/jquery.bxslider.min.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/jquery-knob/excanvas.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/html2canvas/html2canvas.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/html2canvas/html2canvas.svg.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/jquery-knob/dist/jquery.knob.min.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/gauge/gauge.min.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/jasny-bootstrap/dist/js/jasny-bootstrap.min.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/sweetalert/dist/sweetalert.min.js")}}"></script>
<!-- END PLUGINS AREA -->
<!-- PLEASURE -->
<script src="{{asset("/assets/globals/js/pleasure.js")}}"></script>
<!-- ADMIN 1 -->
<script src="{{asset("/assets/admin1/js/layout.js")}}"></script>

<script src="{{asset("/assets/admin1/scripts/sliders.js")}}"></script>
<script src="{{asset("/assets/globals/plugins/iCheck/icheck.js") }}"></script>
<script src="{{asset("/assets/globals/plugins/datatables/media/js/jquery.dataTables.js") }}"></script>
<script src="{{asset("/assets/globals/plugins/datatables/themes/bootstrap/dataTables.bootstrap.js") }}"></script>
{{--<script src="{{asset("/assets/globals/js/app.js") }}" type="text/javascript"></script>--}}
<script>
    $(document).ready(function () {
        Pleasure.init();
        Layout.init();

        setTimeout(function(){
            /*Close any flash message after some time*/
            $(".alert-dismissable").fadeTo(4000, 500).slideUp(500, function(){
                $(".alert-dismissable").alert('close');
            });
        }, 4000);
    });
</script>