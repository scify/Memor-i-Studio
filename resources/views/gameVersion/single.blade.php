<div class="card card-user card-clickable card-clickable-over-content">

    <div class="card-heading heading-full">
        <div class="user-image coverImg">
            <img src="{{url('data/images/' . $gameVersion->coverImg->imageCategory->category .  '/' . $gameVersion->coverImg->file_path)}}">
        </div>

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

    </div><!--.card-heading-->

    <div class="card-body">
        <h3 class="gameVersionTitle">{{$gameVersion->name}}</h3>
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