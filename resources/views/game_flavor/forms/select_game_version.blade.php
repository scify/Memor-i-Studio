@extends('layouts.app')
@section('content')
    <div class="row margin-bottom-30 padding-left-30">
        <div class="col-md-6 centeredVertically">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>{!! __('messages.select_game_version') !!}</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body">
                    <form id="gameVersion-handling-form" class="memoriForm" method="GET"
                          action="{{isset($gameFlavor) ? route('changeGameVersion', $gameFlavor->id) : route('createGameFlavorIndex')}}"
                          enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-2">{!! __('messages.game_type') !!}</div><!--.col-md-3-->
                            <div class="col-md-8">
                                <select class="selecter width-percent-100" name="game_version_id">
                                    @foreach($gameVersions as $gameVersion)
                                        <option value="{{$gameVersion->id}}">{{$gameVersion->name}} {{$gameVersion->version_code}}</option>
                                    @endforeach
                                </select>
                            </div><!--.col-md-9-->
                            <div class="col-md-2">
                                <button type="submit" id="gameVersionLangSubmitBtn" class="btn btn-primary btn-ripple">
                                    {!! __('messages.continue') !!}
                                </button>
                            </div><!--.col-md-9-->
                        </div><!--.row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
