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
                <div class="row example-row">
                    <div class="col-md-12">
                        <div class="row">
                            Why this game should be reported?
                            <div class="form-group">
                                <div class="inputer">
                                    <div class="input-wrapper">
                                        <textarea name="user_comment" class="form-control" placeholder='Eg "This game contains inappropriate language"'
                                                  rows="5" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submitBtnContainer">
                            {{--<button type="button" class="btn btn-flat-primary"><a class="cancelTourCreateBtn" href="{{ URL::route('home') }}">Cancel</a> </button>--}}
                            <button type="submit" id="gameFlavorSubmitBtn" class="btn btn-primary btn-ripple">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
