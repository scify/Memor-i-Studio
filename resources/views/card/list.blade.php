@extends('common.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3">
                <button id="newCardBtn" class="btn btn-default btn-ripple" data-toggle="modal"
                        data-target="#cardOptions">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
            @foreach($cards as $card)
                <div class="col-md-3">
                    @include('card.single', ['card' => $card, 'user' => \Illuminate\Support\Facades\Auth::user()])
                </div>
            @endforeach
        </div>
    </div>
    @include('card.modals', ['card' => new \App\Models\Card()])
@endsection