@extends('common.layout')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group accordion">
                @foreach($resourceCategories as $index => $resourceCategory)
                    @include('game_resource.list', ['resources' => $resourceCategory->resources, 'index' => $index])
                @endforeach
            </div>
        </div>
    </div>
@endsection