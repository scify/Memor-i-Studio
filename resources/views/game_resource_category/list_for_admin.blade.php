@extends('layouts.app')
@section('content')
    <div class="padding-left-30">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><h4>{!! __('messages.language') !!}</h4></div>
            </div><!--.panel-heading-->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">{!! __('messages.language') !!}</div><!--.col-md-3-->
                    <div class="col-md-9">
                        <form id="gameVersion-handling-form" class="memoriForm" method="GET"
                              action="{{route('showGameVersionResourcesForLanguage')}}"
                              enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="game_version_id" value="{{$gameVersionId}}">
                            <select class="selecter" name="lang_id" onchange="this.form.submit()">
                                @foreach($languages as $language)
                                    <option value="{{$language->id}}" {{$langId == $language->id ? 'selected' : ''}}>{{$language->name}}</option>
                                @endforeach
                            </select>
                        </form>
                    </div><!--.col-md-9-->
                </div><!--.row-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($resourceCategories as $resourceCategory)
                    @include('game_resource.list_for_admin', ['resources' => $resourceCategory->resources])
                @endforeach
            </div>
        </div>
    </div>
@endsection
