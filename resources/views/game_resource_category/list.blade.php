@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group accordion padding-left-30">
                @foreach($resourceCategories as $index => $resourceCategory)
                    @include('game_resource.list', ['resources' => $resourceCategory->resources, 'index' => $index])
                @endforeach
            </div>
        </div>
    </div>
@endsection