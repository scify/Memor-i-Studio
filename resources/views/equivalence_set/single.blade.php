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
    </div>
    </div>
</div>

