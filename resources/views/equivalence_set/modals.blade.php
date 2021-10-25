<div class="modal fade full-height" id="cardOptions" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{!! __('messages.select_card_association') !!}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 cardAssociationBtnContainer">
                        <button class="btn btn-primary btn-ripple cardAssociationBtn" data-toggle="modal"
                                data-target="#cardSimpleModal" data-dismiss="modal">
                            {!! __('messages.identical_cards') !!}
                            <br>
                            <p style="font-size:x-large">{!! __('messages.identical_cards_example') !!}</p>
                        </button>
                    </div>
                    <div class="col-md-6 cardAssociationBtnContainer">
                        <button class="btn btn-primary btn-ripple cardAssociationBtn" data-toggle="modal"
                                data-target="#cardAdvancedModal" data-dismiss="modal">
                            {!! __('messages.associated_cards') !!}
                            <br>
                            <p style="font-size:x-large">{!! __('messages.associated_cards_example') !!}</p>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat-primary" data-dismiss="modal">{!! __('messages.cancel') !!}</button>
            </div>
        </div>
    </div>
</div><!--.modal-->
<div class="modal fade full-height bigModal" id="cardSimpleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form id="simpleCardForm" class=" height100x100" method="POST"
              action="{{route('createEquivalenceSet')}}"
              enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">{!! __('messages.create_new_card_set') !!}</h4>
                </div>
                <div class="modal-body">
                    @include('card.forms.create_edit', ['formTitle' => trans('messages.card'), 'formNum' => 1])
                    @include('equivalence_set.forms.create_edit')
                </div>
                <div class="modal-footer">
                    <button type="submit" id="cardSubmitBtn" class="pull-left btn btn-primary btn-ripple">
                        {!! __('messages.create') !!}
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">{!! __('messages.cancel') !!}</button>
                </div>
            </div>
        </form>

    </div>
</div><!--.modal-->
<div class="modal scale full-height fade bigModal" id="cardAdvancedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form class=" height100x100" method="POST"
              action="{{route('createEquivalenceSet')}}"
              enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">{!! __('messages.create_new_card_set') !!}</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="active"><a href="#first_card" data-toggle="tab">{!! __('messages.card') !!}</a></li>
                        <li><a href="#equivalent_card" data-toggle="tab">{!! __('messages.equivalent_card') !!}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="first_card">
                            @include('card.forms.create_edit', ['formTitle' => trans('messages.card'), 'formNum' => 1])
                        </div><!--.tab-pane-->
                        <div class="tab-pane" id="equivalent_card">
                            @include('card.forms.create_edit', ['formTitle' => trans('messages.equivalent_card'), 'formNum' => 2])
                        </div><!--.tab-pane-->
                    </div>
                    @include('equivalence_set.forms.create_edit')
                </div>
                <div class="modal-footer">
                    <button type="submit" id="cardSubmitBtnAdvanced" class="pull-left btn btn-primary btn-ripple">
                        {!! __('messages.create') !!}
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">{!! __('messages.cancel') !!}</button>
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
                {!! __('messages.delete_card_set_message') !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">{!! __('messages.cancel') !!}</button>
                <a href="" class="btn btn-flat btn-primary btn-danger submitLink">{!! __('messages.delete') !!}</a>
            </div>
        </div><!--.modal-content-->
    </div><!--.modal-dialog-->
</div><!--.modal-->
<div class="modal scale fade" id="editEquivalenceSetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 65%">
        <form class="" method="POST"
              action="{{route('editEquivalenceSet')}}"
              enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{!! __('messages.edit_card_set') !!}</h4>
                </div>
                <div class="modal-body">
                    @include('equivalence_set.forms.create_edit')
                    <input type="hidden" value="" id="equivalenceSetId" name="equivalence_set_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="pull-left btn btn-primary btn-ripple">
                        {!! __('messages.save') !!}
                    </button>
                    <button type="button" class="btn btn-flat-primary" data-dismiss="modal">{!! __('messages.cancel') !!}</button>
                </div>
            </div><!--.modal-content-->
        </form>
    </div><!--.modal-dialog-->
</div><!--.modal-->
