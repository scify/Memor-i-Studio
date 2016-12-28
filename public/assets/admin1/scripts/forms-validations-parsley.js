var FormsValidationsParsley = {

	default: function () {
		var parsleyOptionsDefault = {
			errorMessage: 'This field is required.',
			successClass: 'has-success',
			errorClass: 'has-error',
			errorsWrapper: '<span class="help-block"></span>',
			errorTemplate: '<span></span>',
			classHandler : function( _el ) {
				return _el.$element.closest('.form-group');
			},
			errorsContainer: function (_el) {
				return _el.$element.closest('.inputer');
			},
		};
		$('.parsley-validate').parsley( parsleyOptionsDefault );
	},

	onlyIcon: function () {
		var parsleyOptionsIcon = {
			errorMessage: 'This field is required.',
			successClass: 'has-success',
			errorClass: 'has-error',
			errorsWrapper: '<span class="help-block hide"></span>',
			errorTemplate: '<span></span>',
			classHandler : function( _el ){
				return _el.$element.closest('.form-group');
			}
		};
		$('.parsley-validate-icon').parsley( parsleyOptionsIcon );

		$('.parsley-validate-icon').parsley().subscribe('parsley:field:validate', function (field) {
			field.$element.closest('.form-group').addClass('has-feedback');
		});
		$('.parsley-validate-icon').parsley().subscribe('parsley:field:error', function (field) {
			field.$element.next('.glyphicon').remove();
			field.$element.siblings('span').remove();
			field.$element.after('<span class="ion-android-alert tooltips form-control-feedback" data-toggle="tooltip" data-placement="top" title="'+field.options.errorMessage+'"></span>');
		});
		$('.parsley-validate-icon').parsley().subscribe('parsley:field:success', function (field) {
			field.$element.next('.glyphicon').remove()
			field.$element.siblings('span').remove();;
			field.$element.after('<span class="ion-android-done form-control-feedback"></span>');
		});
	},

	init: function () {
		this.default();
		this.onlyIcon();

		$('.summernote-default').summernote(); // Summernote WYSIWYG Plugin
	}
}




