@extends('layouts.app')

@section('content')
    <div id="terms-of-use-page" class="container padding-top-40 margin-bottom-50" style="margin-top: 100px">
        <div class="margin-bottom-50">
            <div class="row">
                <div class="col-md-12 col-centered margin-bottom-30 text-center">
                    <h1>{!! __('messages.terms-of-use') !!}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-centered">
                    <ul class="list-group">
                        <li class="list-group-item margin-bottom-20"><b>{!! __('messages.terms-of-use-prologue') !!}</b></li>
                        <li class="list-group-item">{!! __('messages.term-of-use-1') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-2') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-3') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-4') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-5') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-6') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-7') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-8') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-9') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-10') !!}</li>
                        <li class="list-group-item">{!! __('messages.term-of-use-11') !!}</li>
                    </ul>
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                        <p>{!! __('messages.last-amendment') !!}: 11/04/2022</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
