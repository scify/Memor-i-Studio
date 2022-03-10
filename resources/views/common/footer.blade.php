<footer class="margin-top-40 bg-white padding-top-30 padding-bottom-30">
    <div class="container-fluid">
        <div class="row" id="sitemap">
            <div class="col-md-6 col-sm-11 col-centered">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="footer-grp">
                                <h3>Memor-i Studio</h3>
                                <div>
                                    <a href="{{ route('showAboutPage') }}">About</a>
                                </div>
                                <div>
                                    <a href="https://scify.org/" target="_blank">The team</a>
                                </div>
                                <div>
                                    <a href="{{ route('termsOfUsePage') }}">Terms of Use</a>
                                </div>
                                <div>
                                    <a href="{{ route('privacyPolicyPage') }}">Privacy
                                        Policy</a>
                                </div>
                                <div><a href="https://github.com/scify/memori-online-games-repository"
                                        target="_blank">Github</a></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="footer-grp">
                                <h3>Get in touch</h3>
                                <div>
                                    <b>Phone:</b> +30 211 4004 192
                                </div>
                                <div>
                                    <b>E-mail:</b> info(at)scify.org
                                </div>
                                <div>
                                    <a href="{{ route('showContactForm') }}" target="_blank">Contact us</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            Copyright &copy; {{ date("Y") }} <a target=" _blank"
                                                                href="https://scify.org">scify.org</a><br>
                            All rights reserved. | Version {{ config("app.version")}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script> {{-- Vendor libraries like jQuery, bootstrap --}}
<script src="{{ mix('js/libs.js') }}"></script> {{-- Vendor libraries like jQuery, bootstrap --}}
<script src="{{ mix('js/app.js') }}"></script> {{-- our application common code --}}
<script src="{{ mix('js/equivalenceSetsController.js') }}"></script>
@include('common.analytics')
@stack('scripts')
