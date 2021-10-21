<form id="gameVersion-handling-form" class="memoriForm" method="POST"
      action="{{($gameVersion->id == null ? route('createGameVersion') : route('editGameVersion', $gameVersion->id))}}"
      enctype="multipart/form-data">

    <div class="panelContainer">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><h4>Create new Game Version</h4></div>
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
                                        <input name="name"
                                               class="maxlength maxlength-position form-control" maxlength="50"
                                               placeholder='eg "Noahs Ark"'
                                               value="{{ old('name') != '' ? old('name') : $gameVersion['name']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="inputer">
                                    Version code
                                    <div class="input-wrapper">
                                        <input name="version_code"
                                               class="maxlength maxlength-position form-control" maxlength="50"
                                               placeholder="eg 1.0"
                                               value="{{ old('version_code') != '' ? old('version_code') : $gameVersion['version_code']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            Description
                            <div class="form-group">
                                <div class="inputer">
                                    <div class="input-wrapper">
                                        <input name="description" class="form-control" placeholder='eg "Noahs Ark" is a game with animals'
                                               value="{{ old('description') != '' ? old('description') : $gameVersion['description']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">Version cover image</div><!--.col-md-3-->
                            <div class="col-md-9">
                                <div class="fileinput  {{($gameVersion->cover_img_path == null ? 'fileinput-new' : 'fileinput-exists')}}"
                                     data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                         style="max-height: 200px; min-height: 150px; min-width: 200px">
                                        @if($gameVersion->cover_img_path != '')
                                            <img loading="lazy" class="coverImg"
                                                 src="{{route('resolveDataPath', ['filePath' => $gameVersion->cover_img_path])}}">
                                        @endif
                                    </div>
                                    <div>
										<span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" name="cover_img"></span>
                                        <a href="#"
                                           class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div><!--.col-md-9-->
                        </div>
                        <div class="row margin-top-10">
                            <div class="icheckbox">
                                <label>
                                    <input type="checkbox" name="online" {{$gameVersion['online'] == true ? 'checked' : ''}}>
                                    This game has online functionality
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                Version .jar file <a href="https://docs.oracle.com/javase/tutorial/deployment/jar/signing.html">
                                    <p class="signedJarMsg">(must be signed)</p></a>
                            </div><!--.col-md-3-->
                            <div class="col-md-9">
                                <div class="form-group">
                                    {{--<label for="exampleInputFile">File input</label>--}}
                                    <input type="file" id="gameResPack" name="gameResPack">
                                    <p class="help-block">Upload the game .jar or .zip file.</p>
                                </div><!--.form-group-->
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
