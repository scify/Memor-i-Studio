<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><h4>{!! __('auth.login_btn') !!}</h4></div>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}
            <div class="form-content">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">{!! __('auth.email_label') !!}</label>

                    <div class="col-md-6">
                        <div class="inputer">
                            <div class="input-wrapper">
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" required autofocus>
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
                                <input id="password" type="password" class="form-control" name="password" required>
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
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> {!! __('auth.remember_me_label') !!}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            {!! __('auth.login_btn') !!}
                        </button>

                        <a class="btn btn-link" href="{{ url('/password/reset') }}">
                            {!! __('auth.forgot_password_link') !!}
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="panel-footer footer-light text-dark">
        <ul class="justified-list">
            <li><small><a href="{{ url('register') }}">{!! __('auth.no_account') !!}</a></small></li>
        </ul>
    </div><!--.panel-footer-->
</div>
