@extends('layouts.app')
@section('content')
    <div class="col-md-5">
        @include('card.forms.create_edit', ['formTitle'=> $card->id == null ? trans('messages.messages.create_new_card') : trans('messages.messages.messages.edit_card')])
    </div>
@endsection
