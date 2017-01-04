<form id="gameVersion-handling-form" class="memoriForm" method="POST" action="{{($gameVersion->id == null ? route('createGameVersion') : route('editGameVersion', $gameVersion->id))}}" enctype="multipart/form-data">
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
                                        <input name="name" type="text"
                                               class="maxlength maxlength-position form-control" maxlength="50"
                                               placeholder="Game title"
                                               value="{{ old('name') != '' ? old('name') : $gameVersion['name']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            Description
                            <div class="form-group">
                                <div class="inputer">
                                    <div class="input-wrapper">
                                        <input name="description" type="text"
                                               class="form-control" placeholder="Game description"
                                               value="{{ old('description') != '' ? old('description') : $gameVersion['description']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">Game Language</div><!--.col-md-3-->
                            <div class="col-md-9">
                                <select class="form-control selecter" name="lang_id">
                                    @foreach($languages as $language)
                                        <option value="{{$language->id}}" {{ old('lang_id') == $language->id || $gameVersion['lang_id'] == $language->id ? 'selected' : ''}}>{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </div><!--.col-md-9-->
                        </div><!--.row-->
                        <div class="row">
                            <div class="col-md-3">Game cover image</div><!--.col-md-3-->
                            <div class="col-md-9">
                                <div class="fileinput  {{($gameVersion->cover_img_id == null ? 'fileinput-new' : 'fileinput-exists')}}"
                                     data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                         style="max-height: 200px; min-height: 150px; min-width: 200px">
                                        @if($gameVersion->cover_img_id != '')
                                            <img class="coverImg" src="{{url('data/images/' . $gameVersion->coverImg->imageCategory->category .  '/' . $gameVersion->coverImg->file_path)}}">
                                        @endif
                                    </div>
                                    <div>
										<span class="btn btn-default btn-file">
                                            @if($gameVersion->cover_img_id == null)
                                                <span class="fileinput-new">Select image</span>
                                            @else
                                                <span class="fileinput-exists">Change</span>
                                            @endif
                                            <input type="file" name="cover_img"></span>
                                        <a href="#"
                                           class="btn btn-default {{($gameVersion->cover_img_id == null ? 'fileinput-new' : 'fileinput-exists')}}"
                                           data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div><!--.col-md-9-->
                        </div>
                        <div class="submitBtnContainer">
                            {{--<button type="button" class="btn btn-flat-primary"><a class="cancelTourCreateBtn" href="{{ URL::route('home') }}">Cancel</a> </button>--}}
                            <button type="submit" id="gameVersionSubmitBtn" class="btn btn-primary btn-ripple">
                                {{($gameVersion->id == null ? 'Create' : 'Edit')}}
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
