var FormsPowerange = {

	minMax: function () {
		var vals = document.querySelector('.powerange-minmax');
		var initVals = new Powerange(vals, { min: 16, max: 256, start: 128 });
	},

	decimal: function () {
		var dec = document.querySelector('.powerange-decimals');
		var initDec = new Powerange(dec, { decimal: true, max: 50, start: 19.12 });
	},

	step: function () {
		var stp = document.querySelector('.powerange-step');
		var initStp = new Powerange(stp, { start: 50, step: 10 });
	},

	disabled: function () {
		var disabled = document.querySelector('.powerange-disabled');
		var initDisabled = new Powerange(disabled, { disable: true, disabledOpacity: 0.75, start: 30 });
	},

	vertical: function () {
		var vert = document.querySelector('.powerange-vertical');
		var initVert = new Powerange(vert, { start: 80, vertical: true });
	},

	interacting: function () {
		var opct = document.querySelector('.powerange-opacity');
		var initOpct = new Powerange(opct, { callback: setOpacity, decimal: true, min: 0, max: 1, start: 1 });

		function setOpacity() {
			$('#powerange-opacity-target').css({ opacity: opct.value });
		}

		$('.powerange-callback').change(function () {
			$(this).parents('.form-group').find('.powerange-callback-value').text($(this).val());
		})
	},

	init: function () {
		this.minMax();
		this.decimal();
		this.step();
		this.disabled();
		this.vertical();
		this.interacting();
	}
}




