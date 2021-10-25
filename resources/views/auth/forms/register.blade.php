<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><h4>{!! __('auth.register_btn') !!}</h4></div>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
            {{ csrf_field() }}
            <div class="form-content">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">{!! __('auth.name_label') !!}</label>

                    <div class="col-md-6">
                        <div class="inputer">
                            <div class="input-wrapper">
                                <input id="name" type="text" class="form-control" name="name"
                                       value="{{ old('name') }}" required autofocus>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">{!! __('auth.email_label') !!}</label>

                    <div class="col-md-6">
                        <div class="inputer">
                            <div class="input-wrapper">
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" required>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>


                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">{!! __('auth.password_label') !!}</label>

                    <div class="col-md-6">
                        <div class="inputer">
                            <div class="input-wrapper">
                                <input id="password" type="password" class="form-control"
                                       name="password" required>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm"
                           class="col-md-4 control-label">{!! __('auth.confirm_password_label') !!}</label>

                    <div class="col-md-6">
                        <div class="inputer">
                            <div class="input-wrapper">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            {!! __('auth.register_btn') !!}
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="privacyText">
            {!! __('messages.info_text_1') !!}
            <br><br>{!! __('messages.info_text_2') !!} <a
                    href="{{ __('messages.terms_link') }}"
                    target="_blank">{!! __('messages.terms_of_use_and_privacy_policy') !!}</a>.
            {!! __('messages.read_the') !!} <a href="{{ __('messages.gdpr_link') }}"
                                               target="_blank">{!! __('messages.gdpr_compliance_statement') !!}</a>.
        </div>
    </div>
    <div class="panel-footer footer-light text-dark">
        <ul class="justified-list">
            <li><small><a href="{{ url('login') }}">{!! __('auth.already_account') !!}</a></small></li>
        </ul>
    </div><!--.panel-footer-->
</div>
