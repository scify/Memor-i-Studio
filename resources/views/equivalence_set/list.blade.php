@extends('common.layout')
@section('content')
    <div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
        <li class="breadcrumb-item active">{{$gameFlavor->name}}</li>
    </ol>
    </div>
    @if($gameFlavor->accessed_by_user)
        @if(count($equivalenceSets) == 0)
            <div class="alert alert-warning noFloatAlert">
                <strong>This game flavor does not contain any card sets!</strong> Press the "+" button to add one.
            </div>
        @elseif(count($equivalenceSets) < 2)
            <div class="alert alert-info noFloatAlert">
                <strong>This game flavor contains only one card set!</strong> In order to proceed, add at least one card set more.
            </div>
        @else
            <div class="alert alert-info noFloatAlert">
                Click <a href="">here</a> to proceed to game sounds
            </div>
        @endif
    @endif
    <div class="row">
        <div class="col-md-12">
            @if($gameFlavor->accessed_by_user)
                <div class="col-md-6">
                    <button id="newEquivalenceSetBtn" class="btn btn-default btn-ripple" data-toggle="modal"
                            data-target="#cardOptions">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            @endif
            @foreach($equivalenceSets as $equivalenceSet)
                @include('equivalence_set.single', ['equivalenceSet' => $equivalenceSet])
            @endforeach
        </div>
    </div>
    @include('equivalence_set.modals')
@endsection
@section('additionalFooter')
    <script>
        $(function() {

            var controller = new window.EquivalenceSetsController(cards, editCardRoute);
            controller.init();
        });
    </script>
@endsection