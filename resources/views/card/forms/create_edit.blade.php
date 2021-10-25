<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="card[{{$formNum}}][game_flavor_id]" value="{{ $gameFlavor->id }}">
<div class="row example-row">
    <div class="col-md-12">
        <div class="row">
            <div class="row">
                <h3 class="col-md-12 text-purple">{{$formTitle}}</h3><!--.col-md-12-->
            </div>
            <div class="row">
                <div class="col-md-6">{{$formTitle}} {!! __('messages.image_label') !!}</div><!--.col-md-6-->
                <div class="col-md-6">{{$formTitle}} {!! __('messages.negative_image_label') !!}</div><!--.col-md-6-->
            </div>

            <div class="col-md-6">
                <div class="cardImage fileinput fileinput-new"
                     data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                         style="max-height: 200px; min-height: 150px; min-width: 200px">
                    </div>
                    <div>
                        <span class="btn btn-default btn-file">
                        <span class="fileinput-new">{!! __('messages.select_image') !!}</span>
                        <span class="fileinput-exists">{!! __('messages.change') !!}</span>
                        <input type="file" name="card[{{$formNum}}][image]"></span>
                        <small><p class="help-block">{!! __('messages.suggested_dimensions') !!}</p></small>
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
                        <span class="fileinput-new">{!! __('messages.select_image') !!}</span>
                        <span class="fileinput-exists">{!! __('messages.change') !!}</span>
                        <input type="file" name="card[{{$formNum}}][negative_image]"></span>
                        <small><p class="help-block">{!! __('messages.suggested_dimensions') !!}</p></small>
                    </div>
                </div>
            </div><!--.col-md-9-->
        </div>
        <div class="row example-row">
            <div class="col-md-6">
                <div class="">{{$formTitle}} {!! __('messages.sound_label') !!}</a>)</div>
                <div class="cardAudioVal margin-bottom-10"></div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="file" name="card[{{$formNum}}][sound]">
                    <small><p class="help-block">{!! __('messages.sound_max_size') !!}</p></small>
                </div><!--.form-group-->
            </div><!--.col-md-9-->
        </div><!--.row-->
    </div>
</div>

