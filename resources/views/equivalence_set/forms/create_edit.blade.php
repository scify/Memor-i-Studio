<div class="descriptionSoundContainer">
    <h4>{!! __('messages.set_description_sound') !!}</h4>
    <div class="row example-row">
        <div class="col-md-6">
            <div class="form-group">
                {!! __('messages.set_description_sound_message') !!}
                <input type="file" name="equivalence_set_description_sound" class="margin-top-5">
                <p class="help-block">{!! __('messages.sound_max_size') !!}</p>
            </div><!--.form-group-->
        </div><!--.col-md-6-->
        <div class="col-md-6">
            <div class="form-group">
                <div class="inputer">
                    {!! __('messages.sound_probability') !!}
                    <div class="input-wrapper margin-top-5">
                        <input name="equivalence_set_description_sound_probability" type="number"
                               placeholder="range: 0 to 100" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
