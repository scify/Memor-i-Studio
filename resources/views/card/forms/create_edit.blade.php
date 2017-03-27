<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="card[{{$formNum}}][game_flavor_id]" value="{{ $gameFlavor->id }}">
<div class="row example-row">
    <div class="col-md-12">
        <div class="row">
            <div class="row">
                <h3 class="col-md-12 text-purple">{{$formTitle}}</h3><!--.col-md-12-->
            </div>
            <div class="row">
                <div class="col-md-6">{{$formTitle}} image</div><!--.col-md-6-->
                <div class="col-md-6">{{$formTitle}} negative image (optional) <h6>To help people with low vision</h6></div><!--.col-md-6-->
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

