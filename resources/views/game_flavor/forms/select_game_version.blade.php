@extends('common.layout')
@section('content')
    <div class="row margin-bottom-30 padding-left-30">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>Select Game version</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body">
                    <form id="gameVersion-handling-form" class="memoriForm" method="GET"
                          action="{{route('createGameFlavorIndex')}}"
                          enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3">Game version</div><!--.col-md-3-->
                            <div class="col-md-6">
                                <select class="selecter width-percent-80" name="game_version_id">
                                    @foreach($gameVersions as $gameVersion)
                                        <option value="{{$gameVersion->id}}">{{$gameVersion->name}} {{$gameVersion->version_code}}</option>
                                    @endforeach
                                </select>
                            </div><!--.col-md-9-->
                            <div class="col-md-3">
                                <button type="submit" id="gameVersionLangSubmitBtn" class="btn btn-primary btn-ripple">
                                    Continue
                                </button>
                            </div><!--.col-md-9-->
                        </div><!--.row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection