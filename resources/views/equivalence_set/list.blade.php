@extends('layouts.app')
@section('content')
    <div class="">
        <ol class="breadcrumb col-md-12">
            <li class="breadcrumb-item"><a href="{{route('showHomePage')}}"><i class="fa fa-home"
                                                                               aria-hidden="true"></i> {!! __('messages.home') !!}
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{route('showAllGameFlavors')}}"><i class="fa fa-gamepad"
                                                                                     aria-hidden="true"></i> {!! __('messages.all_games') !!}
                </a>
            </li>
            <li class="breadcrumb-item active">{{$gameFlavor->name}}</li>
        </ol>
    </div>

    <div class="row">
        @if($gameFlavor->accessed_by_user)
            @if(count($equivalenceSets) == 0)
                <div class="col-md-6">
                    <div class="alert alert-warning noFloatAlert">
                        {!! __('messages.no_card_sets') !!}
                    </div>
                </div>
            @elseif(count($equivalenceSets) < 3)
                <div class="col-md-6">
                    <div class="alert alert-info noFloatAlert">
                        {!! __('messages.not_enough_card_sets') !!}
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <div class="alert alert-info noFloatAlert">
                        {!! __('messages.click') !!} <a
                                href="{{route('getResourcesForGameFlavor', ['id' => $gameFlavor->id])}}">{!! __('messages.here') !!}</a> {!! __('messages.proceed_to_game_sounds') !!}
                    </div>
                </div>
                <div class="col-md-2 margin-bottom-20">
                    @if($gameFlavor->submitted_for_approval)
                        <a href="#" class="width-percent-100 disabled btn btn-success btn-ripple padding-15">
                            {!! __('messages.submitted') !!}
                        </a>
                    @else
                        <form method="GET" action="{{route('submitGameFlavorForApproval', $gameFlavor->id)}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="width-percent-100 btn btn-success btn-ripple padding-15">
                                {!! __('messages.submit_game_btn') !!}
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        @endif
        <div class="col-md-2 margin-bottom-20">
            @if($gameFlavor->is_built)
                <a class="width-percent-100 btn btn-primary btn-ripple padding-15" data-toggle="modal"
                   data-target="#downloadLinksModal">{!! __('messages.download') !!}</a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 p-0">
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
                    <h4 class="modal-title">{!! __('messages.download_games') !!}</h4>
                </div>
                <div class="modal-body" style="height: 200px;">
                    <div class="row downloadBtnContainer">
                        <ul class="margin-bottom-30">
                            <li style="text-align: left">{!! __('messages.download_windows_info') !!}
                            </li>
                            <li style="text-align: left">{!! __('messages.download_linux_info') !!}
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
@push('scripts')
    <script>
        $(function () {
            var controller = new window.EquivalenceSetsController(cards, editCardRoute);
            controller.init();
        });
    </script>
@endpush
