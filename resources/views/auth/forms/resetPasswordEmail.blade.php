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

        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
            {{ csrf_field() }}
            <div class="form-content">
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

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            {!! __('auth.send_password_reset_link') !!}
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
