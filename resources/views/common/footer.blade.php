<script src="{{mix('js/bootstrap.js')}}"></script>
<script src="{{mix('js/app.js')}}"></script>
<script src="{{mix('js/controllers.js')}}"></script>
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

        $("input[type='checkbox'], input[type='radio']").iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
    });
</script>

<section>
    @yield('additionalFooter')
</section>
