<div class="card card-user card-clickable card-clickable-over-content gameVersionItem">
    <div class="card-heading heading-full">
        <div class="user-image coverImgContainer">
            @if($gameVersion->cover_img_path == null)
                <img class="coverImg" src="{{asset('assets/img/memori.png')}}">
            @else
                <img class="coverImg" src="{{route('resolveDataPath', ['filePath' => $gameVersion->cover_img_path])}}">
            @endif
        </div>
        <div class="clickable-button">
            <div class="layer bg-green"></div>
            <a class="btn btn-floating btn-green initial-position floating-open"><i class="fa fa-cog" aria-hidden="true"></i></a>
        </div>

        <div class="layered-content bg-green">
            <div class="overflow-content">
                <ul class="borderless">
                    <li><a href="{{route('editGameVersion', $gameVersion->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                    <li><a href="{{route('addGameVersionLanguage', ['id' => $gameVersion->id])}}" class="btn btn-flat btn-ripple">
                            <i class="fa fa-globe" aria-hidden="true"></i> Add Language
                        </a>
                    </li>
                    <li><a href="{{route('showGameVersionResources', ['id' => $gameVersion->id])}}" class="btn btn-flat btn-ripple">
                            <i class="fa fa-table" aria-hidden="true"></i> Translate resources
                        </a>
                    </li>
                </ul>
            </div><!--.overflow-content-->
            <div class="clickable-close-button">
                <a class="btn btn-floating initial-position floating-close"><i class="fa fa-times" aria-hidden="true"></i></a>
            </div>
        </div>
    </div><!--.card-heading-->

    <div class="card-body">
        <h3 class="gameVersionTitle">
        <a href="#"> {{$gameVersion->name}}</a> <small>{{$gameVersion->version_code}}</small>
        </h3>
        <p>{{$gameVersion->description}}</p>

    </div><!--.card-body-->


</div><!--.card-->