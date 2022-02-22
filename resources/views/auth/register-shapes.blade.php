@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ mix('dist/css/login-page-shapes.css') }}">
@endpush

@section('content')
    <main>
        <div class="login-page-shapes container">
            <div class="row">
                <div class="col-md-8 col-sm-11 col-centered">
                    <form method="POST" action="{{ route('shapes.request-create-user') }}" class="content">
                        <img loading="lazy" src="{{asset('assets/img/shapes.png')}}" height="120px" alt="Shapes logo">
                        @csrf
                        <h2 class="text-center margin-bottom-20 margin-top-20 shapes-message">{{  __('auth.register_btn')}}</h2>
                        <div class="margin-bottom-20">
                            <label for="email" class="form-label">{{ __('auth.email_label') }}</label>
                            <input type="email" name='email' class="form-control @error('email') is-invalid @enderror"
                                   placeholder="{{  __('auth.email_label')}}"
                                   id="email" aria-describedby="emailHelp" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="margin-bottom-20">
                            <label for="password" class="form-label">{{ __('auth.password_label') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="{{  __('auth.password_label')}}"
                                   id="password" required autocomplete="current-password" name="password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group row p-0">
                            <div class="col-md-12 p-0">
                                <label for="password-confirm"
                                       class="col-form-label text-md-right">{{ __('auth.confirm_password_label') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                       placeholder="{{  __('auth.confirm_password_label')}}"
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <button type="submit"
                                class="btn btn-primary margin-bottom-10 margin-top-20 shapes-btn"> {{ __('auth.register_btn') }}</button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link shapes-message"
                               target="_blank"
                               href="https://kubernetes.pasiphae.eu/shapes/asapa/auth/password/recovery">
                                {{ __('auth.forgot_password_link') }}
                            </a>
                        @endif
                        <hr class="margin-top-10">
                        <p class="text-center">
                            <a class="margin-left-5 btn btn-info"
                               href="{{ route('shapes.login') }} "> {{ __('auth.already_account')}}</a>
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection

