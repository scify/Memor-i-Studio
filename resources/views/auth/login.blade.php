@extends('layouts.app')

@section('content')
    <div class="container">
        @include('common.elements.rowWithLogo')
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('auth.forms.login')
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <hr class="margin-top-10 margin-bottom-10">
                <div style="text-align: center">
                    <img class="margin-top-5 margin-bottom-10" alt="Shapes Logo" title=""
                         src="{{asset('assets/img/shapes.png')}}"
                         style="width:120px"><br>
                    <a class="btn btn-info margin-top-5 margin-bottom-10" href="{{ route('shapes.login') }}">
                        {!! __('auth.shapes_create_account_prompt') !!}
                    </a>
                    <p class="margin-top-5 margin-bottom-10" style="font-size: medium; font-style: italic">
                        {!! __('auth.shapes_create_account') !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
