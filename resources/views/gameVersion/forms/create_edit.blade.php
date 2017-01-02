<form id="gameVersion-handling-form" class="memoriForm" method="POST" action="#" enctype="multipart/form-data">
    <div class="panelContainer">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><h4>Create new Memor-i version</h4></div>
            </div><!--.panel-heading-->
            <div class="panel-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row example-row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="requiredExpl"><span class="required">*</span> = required</div>
                            <div class="form-group">
                                <div class="inputer">
                                    Version name <span class="required">*</span>
                                    <div class="input-wrapper">
                                        <input name="tour_name" type="text"
                                               class="maxlength maxlength-position form-control
                                                            tourNameInput" maxlength="50"
                                               placeholder="Game title">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            Description
                            <div class="form-group">
                                <div class="inputer">
                                    <div class="input-wrapper">
                                        <input name="tour_by" type="text"
                                               class="form-control" placeholder="Game description">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">Game Language</div><!--.col-md-3-->
                            <div class="col-md-9">
                                <select class="form-control" name="lang_id">
                                    @foreach($languages as $language)
                                        <option value="{{$language->id}}">{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </div><!--.col-md-9-->
                        </div><!--.row-->
                        <div class="row">
                            <div class="col-md-3">Game cover image</div><!--.col-md-3-->
                            <div class="col-md-9">
                                <div class="fileinput  {{($gameVersion->cover_img_id == '' ? 'fileinput-new' : 'fileinput-exists')}}" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="max-height: 200px; min-height: 150px; min-width: 200px">
                                        @if($gameVersion->cover_img_id != '')
                                            <img src="{{$gameVersion->cover_img_id}}" style="max-height: 140px;">
                                        @endif
                                    </div>
                                    <div>
										<span class="btn btn-default btn-file">
										<span class="fileinput-new">Select image</span>
										<span class="fileinput-exists">Change</span>
										<input class="tourFile " type="file" name="website_img"  ></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div><!--.col-md-9-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
