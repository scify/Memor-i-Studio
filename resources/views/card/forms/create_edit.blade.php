<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="card[{{$formNum}}][game_flavor_id]" value="{{ $gameFlavor->id }}">
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
                <h3 class="col-md-12 text-purple">{{$formTitle}}</h3><!--.col-md-12-->
            </div>
            <div class="row">
                <div class="col-md-6">{{$formTitle}} image</div><!--.col-md-6-->
                <div class="col-md-6">{{$formTitle}} negative image</div><!--.col-md-6-->
            </div>

            <div class="col-md-6">
                <div class="cardImage fileinput fileinput-new"
                     data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                         style="max-height: 200px; min-height: 150px; min-width: 200px">
                    </div>
                    <div>
                        <span class="btn btn-default btn-file">
                        <span class="fileinput-new">Select image</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="card[{{$formNum}}][image]"></span>
                        {{--<a href="#"--}}
                           {{--class="btn btn-default fileinput-new"--}}
                           {{--data-dismiss="fileinput">Remove</a>--}}
                    </div>
                </div>
            </div><!--.col-md-9-->

            <div class="col-md-6">
                <div class="cardNegativeImage fileinput fileinput-new"
                     data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                         style="max-height: 200px; min-height: 150px; min-width: 200px">
                    </div>
                    <div>
                        <span class="btn btn-default btn-file">
                        <span class="fileinput-new">Select image</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="card[{{$formNum}}][negative_image]"></span>
                        {{--<a href="#"--}}
                           {{--class="btn btn-default fileinput-new"--}}
                           {{--data-dismiss="fileinput">Remove</a>--}}
                    </div>
                </div>
            </div><!--.col-md-9-->
        </div>
        <div class="row example-row">
            <div class="col-md-6">
                <div class="">{{$formTitle}} sound (.wav or .mp3 file)</a>)</div>
                <div class="cardAudioVal margin-bottom-10"></div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="file" name="card[{{$formNum}}][sound]">
                    <p class="help-block">Maximum size: 3Mb.</p>
                </div><!--.form-group-->
            </div><!--.col-md-9-->
        </div><!--.row-->
    </div>
</div>

