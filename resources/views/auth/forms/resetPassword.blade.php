<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><h4>{!! __('auth.reset_password') !!}</h4></div>
    </div>

    <div class="panel-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
            {{ csrf_field() }}
            <div class="form-content">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">{!! __('auth.email_label') !!}</label>

                    <div class="col-md-6">
                        <div class="inputer">
                            <div class="input-wrapper">
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ $email or old('email') }}" required autofocus>
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

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password-confirm" class="col-md-4 control-label">{!! __('auth.confirm_password_label') !!}</label>
                    <div class="col-md-6">
                        <div class="inputer">
                            <div class="input-wrapper">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            {!! __('auth.reset_password_btn') !!}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
