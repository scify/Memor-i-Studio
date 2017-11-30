<div class="aboutLink">Icons made by <a href="https://www.flaticon.com/authors/nikita-golubev" title="Nikita Golubev">Nikita Golubev</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
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

        $("input[type='checkbox'], input[type='radio']").iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
    });
</script>

<section>
    @yield('additionalFooter')
</section>