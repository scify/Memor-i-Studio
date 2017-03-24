<div class="modal fade full-height" id="cardOptions" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Card association</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 cardAssociationBtnContainer">
                        <button class="btn btn-primary btn-ripple cardAssociationBtn" data-toggle="modal"
                                data-target="#cardSimpleModal" data-dismiss="modal">
                            Identical cards
                            <br>
                            <p style="font-size:x-large">e.g. Cat - Cat</p>
                        </button>
                    </div>
                    <div class="col-md-6 cardAssociationBtnContainer">
                        <button class="btn btn-primary btn-ripple cardAssociationBtn" data-toggle="modal"
                                data-target="#cardAdvancedModal" data-dismiss="modal">
                            Associated cards
                            <br>
                            <p style="font-size:x-large">e.g. Paris - France</p>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div><!--.modal-->
<div class="modal fade full-height" id="cardSimpleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%">
        <form id="simpleCardForm" class=" height100x100" method="POST"
              action="{{route('createEquivalenceSet')}}"
              enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Create a new card set</h4>
                </div>
                <div class="modal-body">
                    @include('card.forms.create_edit', ['formTitle' => 'Card', 'formNum' => 1])
                    @include('equivalence_set.forms.create_edit')
                </div>
                <div class="modal-footer">
                    <button type="submit" id="cardSubmitBtn" class="pull-left btn btn-primary btn-ripple">
                        Create
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>

    </div>
</div><!--.modal-->
<div class="modal scale full-height fade" id="cardAdvancedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%">
        <form class=" height100x100" method="POST"
              action="{{route('createEquivalenceSet')}}"
              enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Create a new card set</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="active"><a href="#first_card" data-toggle="tab">Card</a></li>
                        <li><a href="#equivalent_card" data-toggle="tab">Equivalent Card</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="first_card">
                            @include('card.forms.create_edit', ['formTitle' => 'First card', 'formNum' => 1])
                        </div><!--.tab-pane-->
                        <div class="tab-pane" id="equivalent_card">
                            @include('card.forms.create_edit', ['formTitle' => 'Equivalent card', 'formNum' => 2])
                        </div><!--.tab-pane-->
                    </div>
                    @include('equivalence_set.forms.create_edit')
                </div>
                <div class="modal-footer">
                    <button type="submit" id="cardSubmitBtnAdvanced" class="pull-left btn btn-primary btn-ripple">
                        Create
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">Cancel</button>
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
<div class="modal scale fade" id="editEquivalenceSetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form class="" method="POST"
              action="{{route('editEquivalenceSet')}}"
              enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit equivalence set</h4>
                </div>
                <div class="modal-body">
                    @include('equivalence_set.forms.create_edit')
                    <input type="hidden" value="" id="equivalenceSetId" name="equivalence_set_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="pull-left btn btn-primary btn-ripple">
                        Save
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div><!--.modal-content-->
        </form>
    </div><!--.modal-dialog-->
</div><!--.modal-->