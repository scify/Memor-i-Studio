<div class="card card-user card-clickable card-clickable-over-content gameFlavorItem">
    <div class="card-heading heading-full">
        <div class="user-image coverImgContainer">

            @if($gameFlavor->cover_img_file_path == null)
                <img class="coverImg" src="{{asset('assets/img/memori.png')}}">
            @else
                <img class="coverImg" src="{{route('resolveDataPath', ['filePath' => $gameFlavor->cover_img_file_path])}}">
            @endif
            @if($loggedInUser != null && $gameFlavor->allow_clone)
                <a class="cloneBtn btn btn-green btn-ripple" href="{{route('cloneGameFlavor', $gameFlavor->id)}}"><i class="fa fa-files-o" aria-hidden="true"></i> Clone</a>
            @endif
            @if($gameFlavor->accessed_by_user && $gameFlavor->is_built)
                @if(!$gameFlavor->published)
                    <a class="cloneBtn btn btn-green btn-ripple" style="top:45px;" href="{{route('publishGameFlavor', $gameFlavor->id)}}">
                        <i class="fa fa-globe" aria-hidden="true"></i> Make public
                    </a>
                @else
                    <a class="cloneBtn btn btn-danger btn-ripple" style="top:45px;" href="{{route('unPublishGameFlavor', $gameFlavor->id)}}">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i> Make private
                    </a>
                @endif
            @endif
            <img class="langImg" src="{{asset('assets/img/' . $gameFlavor->language->flag_img_path)}}">
        </div>
        @if($loggedInUser != null)
            @if($gameFlavor->accessed_by_user)
                <div class="clickable-button">
                    <div class="layer bg-green"></div>
                    <a class="btn btn-floating btn-green initial-position floating-open"><i class="fa fa-cog" aria-hidden="true"></i></a>
                </div>

                <div class="layered-content bg-green">
                    <div class="overflow-content">
                        <ul class="borderless row">
                            <div class="col-md-6">
                                <li><a href="{{url('gameFlavor/edit', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                                <li><a href="{{url('gameFlavor/delete', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a></li>
                                @if($loggedInUser->isAdmin())
                                    @if(!$gameFlavor->published)
                                        <li><a href="{{url('gameFlavor/publish', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-check" aria-hidden="true"></i> Publish</a></li>
                                    @else
                                        <li><a href="{{url('gameFlavor/unpublish', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-ban" aria-hidden="true"></i> Unpublish</a></li>
                                    @endif
                                @endif
                                @if($gameFlavor->is_built)
                                    @if(!$gameFlavor->published)
                                        <li><a href="{{url('gameFlavor/publish', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-globe" aria-hidden="true"></i> Make public</a></li>
                                    @else
                                        <li><a href="{{url('gameFlavor/unpublish', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-eye-slash" aria-hidden="true"></i> Make private</a></li>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($loggedInUser->isAdmin())
                                    <li><a href="{{url('gameFlavor/buildTest', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-cogs" aria-hidden="true"></i> Test Build</a></li>
                                    <li><a href="{{url('gameFlavor/build', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-cogs" aria-hidden="true"></i> Build</a></li>
                                    <li><a href="{{url('gameFlavor/changeVersionIndex', $gameFlavor->id)}}" class="btn btn-flat btn-ripple"><i class="fa fa-wrench" aria-hidden="true"></i> Change Version</a></li>
                                @endif
                            </div>
                        </ul>
                    </div><!--.overflow-content-->
                    <div class="clickable-close-button">
                        <a class="btn btn-floating initial-position floating-close"><i class="fa fa-times" aria-hidden="true"></i></a>
                    </div>
                </div>
            @endif
        @endif
    </div><!--.card-heading-->

    <div class="card-body padding-bottom-10">
        <h4 class="margin-bottom-5">
        <a href="{{route('showEquivalenceSetsForGameFlavor', $gameFlavor->id)}}"> {{$gameFlavor->name}}</a>
            @if($loggedInUser != null)
              @if($loggedInUser->isAdmin())
                    @if(!$gameFlavor->published)
                        <i class="fa fa-exclamation-triangle statusIcon" aria-hidden="true" style="color: #f44336" title="This game is not publicly available."></i>
                    @else
                        <i class="fa fa-check-circle statusIcon" aria-hidden="true" style="color: #4caf50" title="Publicly available game."></i>
                    @endif
                @endif
            @endif
        </h4>
        <div class="description">{{$gameFlavor->description}}
            @if($gameFlavor->copyright_link != null)
                <h6><a target="_blank" href="{{$gameFlavor->copyright_link}}">Copyright link</a></h6>
            @endif
            <h6>Created by: {{$gameFlavor->creator->name}}
                @if($loggedInUser != null)
                    @if($loggedInUser->isAdmin())
                         ({{$gameFlavor->creator->email}})
                    @endif
                @endif
            </h6>

        </div>
        <div class="extraInfo row">
            <div class="col-md-6">
                @if($gameFlavor->gameVersion->online)
                    <div class="item-left"> Player V Player Online supported <i class="fa fa-check" aria-hidden="true"></i></div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="item-right">
                    @if($loggedInUser == null)
                        <h6><a data-gameFlavorId="{{$gameFlavor->id}}"
                               class="reportGameFlavorBtn"
                               style="top:75px;" href="javascript: void(0)">Report
                            </a>
                        </h6>
                    @else
                        <h6><a data-gameFlavorId="{{$gameFlavor->id}}"
                               class="reportGameFlavorBtn"
                               style="top:75px;" href="javascript: void(0)">Report
                            </a>
                        </h6>
                    @endif
                </div>
            </div>
        </div>
    </div><!--.card-body-->

    <div class="card-footer">
        @if($gameFlavor->published || (!$gameFlavor->published && $gameFlavor->is_built))
            <h6 class="margin-bottom-1">Download the game:</h6>
            <ul class="justified-list">
                <li><a data-gameFlavorId="{{$gameFlavor->id}}" class="downloadBtnWindows" id = "tooltip-{{$gameFlavor->id}}" title = "Run the installer .exe file to install the game" href="{{route('downloadGameFlavorWindows', $gameFlavor->id)}}"><button class="btn btn-xs btn-flat" style="color: #337ab7"><i class="fa fa-windows" aria-hidden="true"></i> Windows</button></a></li>
                <li><a data-gameFlavorId="{{$gameFlavor->id}}" class="downloadBtnLinux" id = "tooltip-{{$gameFlavor->id}}" title = "Right click -> Open with -> Oracle Java 8" href="{{route('downloadGameFlavorLinux', $gameFlavor->id)}}"><button class="btn btn-xs btn-flat" style="color: #337ab7"><i class="fa fa-linux" aria-hidden="true"></i> Linux</button></a></li>
            </ul>
            <h6 class="installJavaMsg">In order to play the game, you need <a href="http://www.oracle.com/technetwork/java/javase/downloads/jre8-downloads-2133155.html">Java 8</a> installed.</h6>
        @else
            <small class="pull-left"><h6>This game will be available for downloading when it is published by an admin.</h6></small>
        @endif
            {{--<small class="pull-right">Created by: {{$gameFlavor->creator->name}}</small>--}}
    </div><!--.card-footer-->

</div><!--.card-->