@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" style="float: left; position: initial;">
                <strong>{!! __('messages.not_found') !!}!</strong> {{$message}} <a href="{{url('home')}}"
                                                                                   class="alert-link">{!! __('messages.return_home') !!}</a>
            </div>
        </div>
    </div>
@endsection
