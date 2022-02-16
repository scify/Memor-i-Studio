<div class="col-md-6">
    <div class="equivalenceSetContainer">
        <div class="equivalenceSet">
            @if($gameFlavor->accessed_by_user)
                <div class="margin-bottom-10">
                    <button class="btn btn-primary btn-ripple deleteSetBtn" data-equivalenceSetId="{{$equivalenceSet->id}}">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                    <div style="float: right">
                        <button class="btn btn-primary btn-ripple editSetBtn" data-equivalenceSetId="{{$equivalenceSet->id}}">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            @endif
            <div class="cardList">
                @include('card.list', ['cards' => $equivalenceSet->cards()->get()])
            </div>
            <div class="equivalenceSetDescriptionSound">
                @if($equivalenceSet->descriptionSound != null)
                    <div class="col-md-9 p-0">
                        <audio controls>
                            <source src="{{route('resolveDataPath', ['filePath' => $equivalenceSet->descriptionSound->file->file_path])}}" type="audio/mpeg">
                            <source src="{{route('resolveDataPath', ['filePath' => $equivalenceSet->descriptionSound->file->file_path])}}" type="audio/wav">
                            {!! __('messages.sound_browser_no_support') !!}
                        </audio>
                    </div>
                    @if($equivalenceSet->description_sound_probability != null)
                        <div class="col-md-3">
                            <h6 class=descriptionSoundProbability">{!! __('messages.sound_probability') !!}: {{$equivalenceSet->description_sound_probability}} %</h6>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

