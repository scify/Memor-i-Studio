@extends('common.layout')
@section('content')
    <div class="col-md-5">
        @include('card.forms.create_edit', ['formTitle'=> $card->id == null ? 'Create a new Memor-i Card' : 'Edit this card'])
    </div>
    <div class="col-md-5">
        {{--@include('card.forms.create_edit',['formTitle'=> $equivalentCard->id == null ? 'Create the equivalent card' : 'Edit the equivalent card'])--}}
    </div>
@endsection