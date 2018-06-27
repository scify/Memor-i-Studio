@extends('layouts.app')
@section('content')
    <div class="">
        <ol class="breadcrumb col-md-6">
            <li class="breadcrumb-item"><a href="{{url('home')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
            </li>
            <li class="breadcrumb-item active">{{$gameFlavor->name}}</li>
        </ol>
    </div>
    @if($gameFlavor->accessed_by_user)
        <div class="row">
            @if(count($equivalenceSets) == 0)
                <div class="col-md-6">
                    <div class="alert alert-warning noFloatAlert">
                        <strong>This game flavor does not contain any card sets!</strong> Press the "+" button to add
                        one.
                    </div>
                </div>
            @elseif(count($equivalenceSets) < 2)
                <div class="col-md-6">
                    <div class="alert alert-info noFloatAlert">
                        <strong>This game flavor contains only one card set!</strong> In order to proceed, add at least
                        one card set more.
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <div class="alert alert-info noFloatAlert">
                        Click <a href="{{route('getResourcesForGameFlavor', ['id' => $gameFlavor->id])}}">here</a> to
                        proceed to game sounds (optional)
                    </div>
                </div>
                <div class="col-md-2 margin-bottom-20">
                    @if($gameFlavor->submitted_for_approval)
                        <a href="#" class="width-percent-100 disabled btn btn-success btn-ripple padding-15">
                            Submitted
                        </a>
                    @else
                        <form method="GET" action="{{route('submitGameFlavorForApproval', $gameFlavor->id)}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="width-percent-100 btn btn-success btn-ripple padding-15">
                                Submit game
                            </button>
                        </form>
                    @endif
                </div>
                <div class="col-md-2 margin-bottom-20">
                    @if($gameFlavor->is_built)
                        <a class="width-percent-100 btn btn-primary btn-ripple padding-15" data-toggle="modal"
                           data-target="#downloadLinksModal">Download</a>
                    @endif
                </div>
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
    <div class="modal scale fade" id="downloadLinksModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Download Games</h4>
                </div>
                <div class="modal-body" style="height: 200px;">
                    <div class="row downloadBtnContainer">
                        <ul class="margin-bottom-30">
                            <li style="text-align: left">For Windows, run the installer .exe file to install the game.
                            </li>
                            <li style="text-align: left">For Linux, right click the .jar file -> Open with -> Oracle
                                Java 8
                            </li>
                        </ul>
                        <div class="col-md-6">
                            <a data-gameFlavorId="{{$gameFlavor->id}}" class="downloadBtnWindows"
                               id="tooltip-{{$gameFlavor->id}}"
                               title="Run the installer .exe file to install the game"
                               href="{{route('downloadGameFlavorWindows', $gameFlavor->id)}}">
                                <button class="downloadBtn btn btn-primary btn-ripple">
                                    <i class="fa fa-windows" aria-hidden="true"></i> Windows
                                </button>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a data-gameFlavorId="{{$gameFlavor->id}}" class="downloadBtnLinux"
                               id="tooltip-{{$gameFlavor->id}}"
                               title="Right click -> Open with -> Oracle Java 8"
                               href="{{route('downloadGameFlavorLinux', $gameFlavor->id)}}">
                                <button class="downloadBtn btn btn-primary btn-ripple">
                                    <i class="fa fa-linux" aria-hidden="true"></i> Linux
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->
    @include('equivalence_set.modals')
@endsection
@section('additionalFooter')
    <script>
        $(function () {
            $("[id^=tooltip-]").tooltip();
            var controller = new window.EquivalenceSetsController(cards, editCardRoute);
            controller.init();
        });
    </script>
@endsection