{{--<script src="{{asset("dist/pleasure-admin-panel/js/global-vendors.js")}}"></script>--}}
{{--<script src="{{asset("dist/npm-modernizr/modernizr.js")}}"></script>--}}
{{--<script src="{{asset("dist/chosen-js/chosen.jquery.js")}}"></script>--}}

{{--<script src="{{asset("dist/bxslider/dist/jquery.bxslider.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/jquery-knob/dist/jquery.knob.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/jquery-knob/excanvas.js")}}"></script>--}}
{{--<script src="{{asset("dist/html2canvas/dist/html2canvas.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/html2canvas/dist/html2canvas.svg.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/gauge-js/dest/gauge.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/jasny-bootstrap/dist/js/jasny-bootstrap.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/sweetalert/dist/sweetalert.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/datatables/media/js/jquery.dataTables.min.js")}}"></script>--}}
{{--<script src="{{asset("dist/icheck/icheck.min.js")}}"></script>--}}

{{--<script src="{{asset("dist/pleasure-admin-panel/js/sliders.js")}}"></script>--}}
{{--<script src="{{asset("dist/pleasure-admin-panel/js/layout.js")}}"></script>--}}
{{--<script src="{{asset("dist/pleasure-admin-panel/js/pleasure.js")}}"></script>--}}
<script src="{{asset(elixir('js/app.js'))}}"></script>
<script src="{{asset(elixir('js/controllers.js'))}}"></script>
<script>
    $(document).ready(function () {
        Pleasure.init();
        Layout.init();

        setTimeout(function(){
            /*Close any flash message after some time*/
            $(".alert-dismissable").fadeTo(4000, 500).slideUp(500, function(){
                $(".alert-dismissable").alert('close');
            });
        }, 3000);
    });
</script>

<section>
    @yield('additionalFooter')
</section>