<div class="col-md-5 equivalenceSet">
    <div class="margin-bottom-10">
        <button class="btn btn-primary btn-ripple deleteSetBtn">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
    </div>
    @include('card.list', ['cards' => $equivalenceSet->cards()->get()])
</div>