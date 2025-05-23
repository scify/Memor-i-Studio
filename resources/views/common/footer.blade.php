<footer class="margin-top-40 bg-white padding-top-30 padding-bottom-30">
    <div class="container-fluid">
        <div class="row" id="sitemap">
            <div class="col-md-6 col-sm-11 col-centered">
                <div class="container-fluid p-0">
                    <div class="row" style="margin-bottom: 30px">
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
                                    <a href="{{ __('messages.terms-of-service-link') }}" target="_blank">{!! ucwords(__('messages.terms-of-use')) !!}</a>
                                </div>
                                <div>
                                    <a href="{{ __('messages.privacy-policy-link') }}" target="_blank">{!! ucwords(__('messages.privacy_policy_title')) !!}</a>
                                </div>
                                <div>
                                    <a href="{{ __('messages.cookies-policy-link') }}" target="_blank">{!! ucwords(__('messages.cookies-policy')) !!}</a>
                                </div>
                                <div><a href="https://github.com/scify/memori-online-games-repository"
                                        target="_blank">Github</a></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="footer-grp">
                                <h3>{!! __('messages.contact') !!}</h3>
                                <div>
                                    <b>Tel:</b> +30 211 4004 192
                                </div>
                                <div>
                                    <b>E-mail:</b> info(at)scify.org
                                </div>
                                <div>
                                    <a href="{{ route('showContactForm') }}"
                                       target="_blank">{!! __('messages.contact_us_lower') !!}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            {!! __('messages.copyright') !!} &copy; {{ date("Y") }} <a target=" _blank"
                                                                                       href="https://scify.org">scify.org</a><br>
                            {!! __('messages.all_rights_reserved') !!}.<br>{!! __('messages.version') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="copyright">
                                <p style="margin: 0">Created by <a href="https://www.scify.gr/site/en/">SciFY</a>
                                    @ {{ now()->year }}
                                </p>
                                <p>version <b>{{ config('app.version') }}</b></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6" style="margin-left: auto; margin-right: 0;">
                            <div>
                                <img alt="EU Logo" title="" src="{{ asset('assets/img/EULogo.jpg') }}"
                                     style="width:70px;height:50px;float:right;display:block;margin-right:100px">
                                <img alt="Shapes Logo" title="" src="{{ asset('assets/img/shapes.png') }}"
                                     style="width:70px;height:50px; float: right; display: block; background: white; margin-right:10px;">
                            </div>
                            <p style="font-size: small;">This project has received funding from
                                the
                                European Union's Horizon 2020 research and innovation programme under grant agreement
                                No.
                                857159.</p>
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
@if(config('app.userway_id'))
    <script>(function (d) {
            var s = d.createElement("script");
            s.setAttribute("data-account", "{{config('app.userway_id')}}");
            s.setAttribute("src", "https://cdn.userway.org/widget.js");
            (d.body || d.head).appendChild(s);
        })(document)</script>
    <noscript>Please ensure Javascript is enabled for purposes of <a href="https://userway.org">website
            accessibility</a></noscript>
@endif
@stack('scripts')
