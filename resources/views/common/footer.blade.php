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
                                    <a href="{{ route('showAboutPage') }}">{!! __('messages.about') !!}</a>
                                </div>
                                <div>
                                    <a href="https://scify.org/" target="_blank">{!! __('messages.the_team') !!}</a>
                                </div>
                                <div>
                                    <a href="{{ route('termsOfUsePage') }}">{!! ucwords(__('messages.terms-of-use')) !!}</a>
                                </div>
                                <div>
                                    <a href="{{ route('privacyPolicyPage') }}">{!! ucwords(__('messages.privacy_policy_title')) !!}</a>
                                </div>
                                <div><a href="https://github.com/scify/memori-online-games-repository"
                                        target="_blank">Github</a></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="footer-grp">
                                <h3>{!! ucwords(strtolower(__('messages.contact'))) !!}</h3>
                                <div>
                                    <b>Tel:</b> +30 211 4004 192
                                </div>
                                <div>
                                    <b>E-mail:</b> info(at)scify.org
                                </div>
                                <div>
                                    <a href="{{ route('showContactForm') }}" target="_blank">{!! ucfirst(__('messages.contact_us')) !!}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            {!! __('messages.copyright') !!} &copy; {{ date("Y") }} <a target=" _blank"
                                                                href="https://scify.org">scify.org</a><br>
                            {!! __('messages.all_rights_reserved') !!}.<br>{!! __('messages.version') !!} <b>{{ config("app.version")}}</b>
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
