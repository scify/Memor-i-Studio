
    {{--<div class="panelContainer">--}}
    {{--<div class="panel">--}}
    {{--<div class="panel-heading">--}}
    {{--<div class="panel-title"><h5>{{$formTitle}}</h5></div>--}}
    {{--</div><!--.panel-heading-->--}}
    {{--<div class="panel-body">--}}
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="game_version_id" value="{{ $gameVersionId }}">
    <div class="row example-row">
        <div class="col-md-12">
            {{--<div class="row">--}}
                {{--<div class="requiredExpl"><span class="required">*</span> = required</div>--}}
                {{--<div class="form-group">--}}
                    {{--<div class="inputer">--}}
                        {{--Card label <span class="required">*</span>--}}
                        {{--<div class="input-wrapper">--}}
                            {{--<input name="name" type="text"--}}
                                   {{--class="maxlength maxlength-position form-control" maxlength="50"--}}
                                   {{--placeholder='e.g "horse" or "Paris"'--}}
                                   {{--value="{{ old('label') != '' ? old('label') : $card['label']}}">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="row">
            <div class="row">
                <div class="col-md-6">Card image</div><!--.col-md-3-->
                <div class="col-md-6">Card negative image</div><!--.col-md-3-->
            </div>

            <div class="col-md-6">
                <div class="fileinput  {{($card->image_id == null ? 'fileinput-new' : 'fileinput-exists')}}"
                     data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                         style="max-height: 200px; min-height: 150px; min-width: 200px">
                        @if($card->image_id != '')
                            <img class="coverImg"
                                 src="{{url('data/images/' . $card->image->imgCategory->category .  '/' . $card->image->file_path)}}">
                        @endif
                    </div>
                    <div>
                        <span class="btn btn-default btn-file">
                        <span class="fileinput-new">Select image</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="image"></span>
                        <a href="#"
                           class="btn btn-default {{($card->image_id == null ? 'fileinput-new' : 'fileinput-exists')}}"
                           data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
            </div><!--.col-md-9-->

            <div class="col-md-6">
                <div class="fileinput  {{($card->negative_image_id == null ? 'fileinput-new' : 'fileinput-exists')}}"
                     data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                         style="max-height: 200px; min-height: 150px; min-width: 200px">
                        @if($card->negative_image_id != '')
                            <img class="coverImg"
                                 src="{{url('data/images/' . $card->secondImage->imgCategory->category .  '/' . $card->secondImage->file_path)}}">
                        @endif
                    </div>
                    <div>
                        <span class="btn btn-default btn-file">
                        <span class="fileinput-new">Select image</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="negative_image"></span>
                        <a href="#"
                           class="btn btn-default {{($card->negative_image_id == null ? 'fileinput-new' : 'fileinput-exists')}}"
                           data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
            </div><!--.col-md-9-->
            </div>
            <div class="row example-row">
                <div class="col-md-6">
                    <div class="margin-bottom-10">Card sound</div>
                </div>
                <div class="col-md-12">
                    @if($card->sound_id != null)
                        <p class="margin-bottom-10">Current sound: {{$card->sound()->file_path}}</p>
                    @endif
                    <div class="form-group">
                        <input type="file" name="sound[]">
                        <p class="help-block">Maximum size: 3Mb.</p>
                    </div><!--.form-group-->
                    <div class="col-md-6">Remove audio</div><!--.col-md-3-->
                    <div class="col-md-6">
                    </div><!--.col-md-9-->
                </div><!--.col-md-9-->
            </div><!--.row-->
        </div>
        <div class="submitBtnContainer">

        </div>
    </div>
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

