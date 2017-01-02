<script src="{{elixir('dist/app.js')}}">
</script>

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

<section>
    @yield('additionalFooter')
</section>