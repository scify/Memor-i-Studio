<div class="modal fade full-height" id="cardOptions" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Card association</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 cardAssociationBtnContainer">
                        <button class="btn btn-primary btn-ripple cardAssociationBtn" data-toggle="modal"
                                data-target="#cardSimpleModal" data-dismiss="modal">
                            Simple
                        </button>
                    </div>
                    <div class="col-md-6 cardAssociationBtnContainer">
                        <button class="btn btn-primary btn-ripple cardAssociationBtn" data-toggle="modal"
                                data-target="#cardAdvancedModal" data-dismiss="modal">
                            Advanced
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat-primary" data-dismiss="modal">CANCEL</button>
            </div>
        </div>
    </div>
</div><!--.modal-->
<div class="modal fade full-height" id="cardSimpleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form id="gameVersion-handling-form" class="memoriForm height100x100" method="POST"
              action="{{route('createEquivalenceSet')}}"
              enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Create a new card set</h4>
                </div>
                <div class="modal-body">
                    @include('card.forms.create_edit', ['formTitle' => 'Single card', 'formNum' => 1])
                </div>
                <div class="modal-footer">
                    <button type="submit" id="gameVersionSubmitBtn" class="pull-left btn btn-primary btn-ripple">
                        Create
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </form>

    </div>
</div><!--.modal-->
<div class="modal fade full-height" id="cardAdvancedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form id="gameVersion-handling-form" class="memoriForm height100x100" method="POST"
              action="{{route('createEquivalenceSet')}}"
              enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Create a new card set</h4>
                </div>
                <div class="modal-body">
                    @include('card.forms.create_edit', ['formTitle' => 'Advanced card', 'formNum' => 1])
                    @include('card.forms.create_edit', ['formTitle' => 'Advanced card', 'formNum' => 2])
                </div>
                <div class="modal-footer">
                    <button type="submit" id="gameVersionSubmitBtn" class="pull-left btn btn-primary btn-ripple">
                        Create
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </form>
    </div>
</div><!--.modal-->
<div class="modal scale fade" id="deleteEquivalenceSetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this card set?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cancel</button>
                <a href="" class="btn btn-flat btn-primary btn-danger submitLink">Delete</a>
            </div>
        </div><!--.modal-content-->
    </div><!--.modal-dialog-->
</div><!--.modal-->