<form id="reportGameFlavorForm" class="memoriForm" method="POST"
      action="{{route('reportGameFlavor')}}"
      enctype="multipart/form-data">
    <input type="hidden" id="gameFlavorIdInput" name="game_flavor_id" value="">
    <div class="panelContainer">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"></div>
            </div><!--.panel-heading-->
            <div class="panel-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="requiredExpl"><span class="required">*</span> = {{ __('messages.required') }}</div>
                    <div class="form-group">
                        <div class="inputer">
                            {{ __('messages.your_name') }} <span class="required">*</span>
                            <div class="input-wrapper">
                                <input name="name" type="text"
                                       class="maxlength maxlength-position form-control" maxlength="50"
                                       placeholder="{{ __('messages.your_name') }}"
                                       value="{{$loggedInUser != null ? $loggedInUser->name : ''}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="requiredExpl"><span class="required">*</span> = {{ __('messages.required') }}</div>
                    <div class="form-group">
                        <div class="inputer">
                            {{ __('messages.your_email') }} <span class="required">*</span>
                            <div class="input-wrapper">
                                <input name="name" type="email"
                                       class="maxlength maxlength-position form-control" maxlength="50"
                                       placeholder="{{ __('messages.your_email') }}"
                                       value="{{$loggedInUser != null ? $loggedInUser->email : ''}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            {{ __('messages.why_game_should_be_reported') }}
                            <div class="form-group">
                                <div class="inputer">
                                    <div class="input-wrapper">
                                        <textarea name="user_comment" class="form-control"
                                                  placeholder='{{ __('messages.why_game_should_be_reported_example') }}'
                                                  rows="5" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submitBtnContainer">
                            <button type="submit" id="gameFlavorSubmitBtn" class="btn btn-primary btn-ripple">
                                {{ __('messages.submit') }}
                            </button>
                            <button type="button" class="btn btn-default btn-ripple"
                                    data-dismiss="modal">{{ __('messages.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
