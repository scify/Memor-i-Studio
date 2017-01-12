<div class="card card-user card-clickable card-clickable-over-content cardItem">
    <div class="card-heading heading-full">
        <div class="user-image coverImgContainer">
            @if($card->image_id == null)
                <img class="coverImg" src="{{asset('assets/img/memori.png')}}">
            @else
                <img class="coverImg" src="{{url('data/' . $gameFlavor->id . '/img/' . $card->image->imageCategory->category .  '/' . $card->image->file_path)}}">
            @endif
        </div>
        @if($user != null)
            @if($gameFlavor->accessed_by_user)
                <div class="clickable-button">
                    <div class="layer bg-green"></div>
                    <a class="btn btn-floating btn-green initial-position floating-open"><i class="fa fa-cog" aria-hidden="true"></i></a>
                </div>

                <div class="layered-content bg-green">
                    <div class="overflow-content">
                        <ul class="borderless">
                            <li><a data-cardId="{{$card->id}}" class="btn btn-flat btn-ripple editCardBtn"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                        </ul>
                    </div><!--.overflow-content-->
                    <div class="clickable-close-button">
                        <a class="btn btn-floating initial-position floating-close"><i class="fa fa-times" aria-hidden="true"></i></a>
                    </div>
                </div>
            @endif
        @endif
    </div><!--.card-heading-->

    <div class="card-footer">
        <div class="cardSound">
            @if($card->sound != null)
                <audio controls>
                    <source src="{{url('data/' . $gameFlavor->id . '/audios/' . $card->sound->soundCategory->category .  '/' . $card->sound->file_path)}}" type="audio/mpeg">
                    <source src="{{url('data/' . $gameFlavor->id . '/audios/' . $card->sound->soundCategory->category .  '/' . $card->sound->file_path)}}" type="audio/wav">
                    Your browser does not support the audio element.
                </audio>
            @endif
        </div>
    </div><!--.card-footer-->

</div><!--.card-->