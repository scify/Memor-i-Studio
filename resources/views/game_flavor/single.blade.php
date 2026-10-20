<div class="card card-user card-clickable card-clickable-over-content gameFlavorItem">
    <div class="card-heading heading-full">
        <div class="user-image coverImgContainer">

            @if($gameFlavor->cover_img_file_path == null)
                <img loading="lazy" class="coverImg" src="{{asset('assets/img/memori.png')}}">
            @else
                <img loading="lazy" class="coverImg"
                     src="{{route('resolveDataPath', ['filePath' => $gameFlavor->cover_img_file_path])}}">
            @endif
            @if($loggedInUser != null && $gameFlavor->allow_clone)
                <form method="POST" action="{{route('cloneGameFlavor', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="actionBtn btn btn-purple btn-ripple"><i
                            class="fa fa-files-o" aria-hidden="true"></i> {!! __('messages.clone') !!}</button></form>
            @endif
            @if($gameFlavor->accessed_by_user && $gameFlavor->is_built)
                @if(!$gameFlavor->published)
                    <form method="POST" action="{{route('publishGameFlavor', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="actionBtn btn btn-green btn-ripple" style="top:45px;">
                        <i class="fa fa-globe" aria-hidden="true"></i> {!! __('messages.make_it_public') !!}
                    </button></form>
                @else
                    <form method="POST" action="{{route('unPublishGameFlavor', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="actionBtn btn btn-danger btn-ripple" style="top:45px;">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i> {!! __('messages.make_it_private') !!}
                    </button></form>
                @endif
            @endif
            @if($gameFlavor->internationally_available)
                <img loading="lazy" class="langImg"
                     src="{{asset('assets/img/world.png')}}" style="height: 42px" alt="World image">
            @else
                <img loading="lazy" class="langImg"
                     src="{{asset('assets/img/' . $gameFlavor->language_flag_img_path)}}" alt="Language image">
            @endif
        </div>
        @if($loggedInUser != null)
            @if($gameFlavor->accessed_by_user)
                <div class="clickable-button">
                    <div class="layer bg-green"></div>
                    <a class="btn btn-floating btn-green initial-position floating-open"><i class="fa fa-cog"
                                                                                            aria-hidden="true"></i></a>
                </div>

                <div class="layered-content bg-green">
                    <div class="overflow-content">
                        <ul class="borderless row">
                            <div class="col-md-6">
                                <li><a href="{{url('gameFlavor/edit', $gameFlavor->id)}}"
                                       class="btn btn-flat btn-ripple"><i class="fa fa-pencil" aria-hidden="true"></i>
                                        {!! __('messages.edit') !!}</a></li>
                                <li><form method="POST" action="{{url('gameFlavor/delete', $gameFlavor->id)}}" style="display: inline">@csrf @method('DELETE')<button type="submit" class="btn btn-flat btn-ripple"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                        {!! __('messages.delete') !!}</button></form></li>
                                @if($loggedInUser->isAdmin() && $gameFlavor->is_built)
                                    @if(!$gameFlavor->published)
                                        <li><form method="POST" action="{{url('gameFlavor/publish', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="btn btn-flat btn-ripple"><i class="fa fa-check"
                                                                                  aria-hidden="true"></i> {!! __('messages.publish') !!}
                                            </button></form>
                                        </li>
                                    @else
                                        <li><form method="POST" action="{{url('gameFlavor/unpublish', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="btn btn-flat btn-ripple"><i class="fa fa-ban"
                                                                                  aria-hidden="true"></i> {!! __('messages.unpublish') !!}
                                            </button></form>
                                        </li>
                                    @endif
                                @endif
                                @if($gameFlavor->is_built)
                                    @if(!$gameFlavor->published)
                                        <li><form method="POST" action="{{url('gameFlavor/publish', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="btn btn-flat btn-ripple"><i class="fa fa-globe"
                                                                                  aria-hidden="true"></i> {!! __('messages.make_it_public') !!}
                                            </button></form></li>
                                    @else
                                        <li><form method="POST" action="{{url('gameFlavor/unpublish', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="btn btn-flat btn-ripple"><i class="fa fa-eye-slash"
                                                                                  aria-hidden="true"></i> {!! __('messages.make_it_private') !!}
                                            </button></form></li>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($loggedInUser->isAdmin())
                                    <li><form method="POST" action="{{url('gameFlavor/buildTest', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="btn btn-flat btn-ripple"><i class="fa fa-cogs" aria-hidden="true"></i>
                                            {!! __('messages.test_build') !!}</button></form></li>
                                    <li><form method="POST" action="{{url('gameFlavor/build', $gameFlavor->id)}}" style="display: inline">@csrf<button type="submit" class="btn btn-flat btn-ripple"><i class="fa fa-cogs" aria-hidden="true"></i>
                                            {!! __('messages.build') !!}</button></form></li>
                                    <li><a href="{{url('gameFlavor/changeVersionIndex', $gameFlavor->id)}}"
                                           class="btn btn-flat btn-ripple"><i class="fa fa-wrench"
                                                                              aria-hidden="true"></i> {!! __('messages.change_base_version') !!}
                                        </a>
                                    </li>
                                @endif
                            </div>
                        </ul>
                    </div><!--.overflow-content-->
                    <div class="clickable-close-button">
                        <a class="btn btn-floating initial-position floating-close"><i class="fa fa-times"
                                                                                       aria-hidden="true"></i></a>
                    </div>
                </div>
            @endif
        @endif
    </div><!--.card-heading-->

    <div class="card-body padding-bottom-10">
        <div class="conainer-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="margin-bottom-10">
                        <a href="{{route('showEquivalenceSetsForGameFlavor', $gameFlavor->id)}}"> {{$gameFlavor->name}}</a>
                        @if($loggedInUser != null)
                            @if($loggedInUser->isAdmin())
                                @if(!$gameFlavor->published)
                                    <i class="fa fa-exclamation-triangle statusIcon" aria-hidden="true"
                                       style="color: #f44336"
                                       title="{{ __('messages.game_not_public') }}"></i>
                                @else
                                    <i class="fa fa-check-circle statusIcon" aria-hidden="true" style="color: #4caf50"
                                       title="{{ __('messages.game_public') }}"></i>
                                @endif
                            @endif
                        @endif
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="description">{{$gameFlavor->description}}
                        @if($gameFlavor->copyright_link != null)
                            <h6><a target="_blank"
                                   href="{{$gameFlavor->copyright_link}}">{{ __('messages.copyright_link') }}</a>
                            </h6>
                        @endif
                        <h6>Created by: {{$gameFlavor->user_creator_name}}
                            @if($loggedInUser != null)
                                @if($loggedInUser->isAdmin())
                                    ({{$gameFlavor->user_creator_email}})
                                @endif
                            @endif
                        </h6>

                    </div>
                    <div class="extraInfo row">
                        <div class="col-md-6">
                            @if($gameFlavor->is_online)
                                <div class="item-left"> {{ __('messages.pvp_supported') }} <i class="fa fa-check"
                                                                                              aria-hidden="true"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="item-right">
                                <h6><a data-gameFlavorId="{{$gameFlavor->id}}"
                                       class="reportGameFlavorBtn"
                                       style="top:75px;" href="javascript: void(0)">{{ __('messages.report') }}
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--.card-body-->

    <div class="card-footer">
        @if($gameFlavor->published || (!$gameFlavor->published && $gameFlavor->is_built))
            <h6 class="margin-bottom-1">{{ __('messages.download_the_game') }}:</h6>
            <ul class="justified-list">
                <li><a data-gameFlavorId="{{$gameFlavor->id}}" class="downloadBtnWindows"
                       id="tooltip-{{$gameFlavor->id}}" title="{{ __('messages.download_windows_instructions') }}"
                       href="{{route('downloadGameFlavorWindows', $gameFlavor->id)}}">
                        <button class="btn btn-xs btn-flat" style="color: #337ab7"><i class="fa fa-windows"
                                                                                      aria-hidden="true"></i> Windows
                        </button>
                    </a></li>
                <li><a data-gameFlavorId="{{$gameFlavor->id}}" class="downloadBtnLinux" id="tooltip-{{$gameFlavor->id}}"
                       title="{{ __('messages.download_linux_instructions') }}"
                       href="{{route('downloadGameFlavorLinux', $gameFlavor->id)}}">
                        <button class="btn btn-xs btn-flat" style="color: #337ab7"><i class="fa fa-linux"
                                                                                      aria-hidden="true"></i> Linux
                        </button>
                    </a></li>
            </ul>
            <h6 class="installJavaMsg">{!! __('messages.java_message') !!}</h6>
        @else
            <small class="pull-left"><h6>{{ __('messages.game_available_when_published') }}</h6></small>
        @endif
    </div><!--.card-footer-->
</div><!--.card-->
