@extends('common.layout')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
        <li class="breadcrumb-item active">{{$gameFlavor->name}}</li>
    </ol>
    @if(count($equivalenceSets) == 0)
        <div class="alert alert-info">
            <strong>This game flavor does not contain any card sets!</strong> @if($gameFlavor->accessed_by_user)Press the "+" button to add one.@endif
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            @if($gameFlavor->accessed_by_user)
                <div class="col-md-5">
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