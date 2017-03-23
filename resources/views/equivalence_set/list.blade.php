@extends('layouts.app')
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
            <div class="row">
                <div class="col-md-8">
                    <div class="alert alert-info noFloatAlert">
                        Click <a href="{{route('getResourcesForGameFlavor', ['id' => $gameFlavor->id])}}">here</a> to proceed to game sounds (optional)
                    </div>
                </div>
                <div class="col-md-4">
                    @if($gameFlavor->submitted_for_approval)
                        <a href="#" class="disabled btn btn-success btn-ripple padding-15">
                            Submitted for approval
                        </a>
                    @else
                        <form method="GET" action="{{route('submitGameFlavorForApproval', $gameFlavor->id)}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-6">
                                <label style="text-align: right">
                                    <input type="checkbox" name="terms" required>
                                    I accept the <a href="http://www.scify.gr/site/en/">terms & conditions</a>
                                </label>
                            </div>
                            <div class="col-md-6">
                            <button type="submit" class="btn btn-success btn-ripple padding-15">
                                Submit game for approval
                            </button>
                            </div>
                        </form>
                    @endif
                </div><!--.col-md-9-->
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