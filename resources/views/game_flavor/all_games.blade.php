@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 centerMessage" style="text-align: center">
                {!! __('messages.download_games') !!} <i class="fa fa-download" aria-hidden="true"></i>
                <i class="fa fa-arrow-right" aria-hidden="true"></i> {!! __('messages.put_your_headphones_on') !!} <i
                        class="fa fa-headphones" aria-hidden="true"></i>
                <i class="fa fa-arrow-right" aria-hidden="true"></i> {!! __('messages.play') !!}! <i
                        class="fa fa-gamepad"
                        aria-hidden="true"></i>
            </div>
        </div>
        <div class="row margin-bottom-30 text-center justify-content-center" id="gameFilters">
            <div class="col-md-2 col-md-offset-5">
                <select name="languages" id="languages" class="width-percent-100 form-control selecter">
                    <option selected>All Languages</option>
                    @foreach($languages as $language)
                        <option>{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button id="getGames"
                        class="btn btn-success btn-ripple margin-bottom-5">{!! __('messages.more_games') !!}
                </button>
                <p class="text-danger" id="error"></p>
                <div id="gamesLoader"
                     class="loader display-none"
                     role="status" aria-hidden="true"></div>
            </div>
        </div>
        <div class="row margin-bottom-50 margin-top-50" id="gameResults"></div>
        <div class="row">
            <div class="col-md-12 text-center">
                <h6 class="moreGames">{!! __('messages.more_games') !!}: <a href="https://www.gamesfortheblind.org/"
                                                                            target="_blank"
                                                                            class="">www.gamesfortheblind.org</a></h6>
            </div>
        </div>
    </div>
@endsection
@push('modals')
    <div class="modal scale fade" id="createGameFlavorReportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{!! __('messages.report_game_title') !!}</h4>
                </div>
                <div class="modal-body" style="max-height: none">
                    @include('game_flavor_report.forms.create_edit')
                </div>
            </div><!--.modal-content-->
        </div><!--.modal-dialog-->
    </div><!--.modal-->
@endpush
@push('scripts')
    <script>
        const user = {!!  json_encode($loggedInUser) !!};
        const gameFlavorsRoute = {!!  json_encode($gameFlavorsRoute) !!};
    </script>
    <script src="{{ mix('js/gameFlavorsController.js') }}"></script>
@endpush
