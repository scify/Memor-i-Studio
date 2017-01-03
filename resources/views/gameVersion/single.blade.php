<div class="card card-user card-clickable card-clickable-over-content gameVersionItem">
    <div class="card-heading heading-full">
        <div class="user-image coverImgContainer">
            @if($gameVersion->cover_img_id == null)
                <img class="coverImg" src="{{asset('assets/img/memori.png')}}">
            @else
                <img class="coverImg" src="{{url('data/images/' . $gameVersion->coverImg->imageCategory->category .  '/' . $gameVersion->coverImg->file_path)}}">
            @endif
            <img class="langImg" src="{{asset('assets/img/' . $gameVersion->language->flag_img_path)}}">
        </div>
        @if($user->isAdmin())
            <div class="clickable-button">
                <div class="layer bg-green"></div>
                <a class="btn btn-floating btn-green initial-position floating-open"><i class="fa fa-cog" aria-hidden="true"></i></a>
            </div>

            <div class="layered-content bg-green">
                <div class="overflow-content">
                    <ul class="inline-list social-list borderless">
                        <li><a class="btn btn-flat pull-left btn-ripple">Publish</a></li>
                    </ul>
                </div><!--.overflow-content-->
                <div class="clickable-close-button">
                    <a class="btn btn-floating initial-position floating-close"><i class="fa fa-times" aria-hidden="true"></i></a>
                </div>
            </div>
        @endif
    </div><!--.card-heading-->

    <div class="card-body">
        <h3 class="gameVersionTitle">{{$gameVersion->name}}
            @if($user->isAdmin())
                @if(!$gameVersion->published)
                    <i class="fa fa-exclamation-triangle statusIcon" aria-hidden="true" style="color: orangered" title="This game is not published yet."></i>
                @else
                    <i class="fa fa-check-circle statusIcon" aria-hidden="true" style="color: forestgreen" title="Published game."></i>
                @endif
            @endif
        </h3>
        <p>{{$gameVersion->description}}</p>
        {{--<ul class="social-links">--}}
            {{--<li><a href="#"><i class="fa fa-linkedin"></i></a></li>--}}
            {{--<li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
            {{--<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
        {{--</ul>--}}
    </div><!--.card-body-->

    <div class="card-footer">
        {{--<a href="#" class="pull-left"><small>8 friends in common</small></a>--}}
        <button class="btn btn-xs btn-flat pull-left" style="color: #337ab7"><i class="fa fa-download" aria-hidden="true"></i> Download</button>
    </div><!--.card-footer-->

</div><!--.card-->