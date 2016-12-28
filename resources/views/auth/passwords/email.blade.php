@extends('common.layout')

<!-- Main Content -->
@section('content')
<div class="container">
    @include('common.elements.rowWithLogo')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('auth.forms.resetPasswordEmail')
        </div>
    </div>
</div>
@endsection
