@extends('common.layout')
@section('content')
    <div class="row margin-bottom-30">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title"><h4>Language</h4></div>
                </div><!--.panel-heading-->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">Language</div><!--.col-md-3-->
                        <div class="col-md-9">
                            <form id="gameVersion-handling-form" class="memoriForm" method="GET"
                                  action="{{route('showGameVersionResourcesForLanguage')}}"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="game_version_id" value="{{$gameVersionId}}">
                                <select class="selecter" name="lang_id" onchange="this.form.submit()">
                                    <option value="0">Select a language</option>
                                    @foreach($languages as $language)
                                        <option value="{{$language->id}}">{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div><!--.col-md-9-->
                    </div><!--.row-->
                </div>
            </div>
        </div>
    </div>
@endsection