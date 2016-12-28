var FormsRange = {

	create: function () {
		$('.rangeslider').rangeslider({
			polyfill: false
		});

		$('input[type="range"]').each(function () {
			$(this).parents('.form-group').find('.rangeslider-value').text( $(this).val() );
		});

		$('.rangeslider').on('change', function (e) {
			$(this).parents('.form-group').find('.rangeslider-value').text(e.target.value);
		});
	},

	init: function () {
		this.create();
	}
}

