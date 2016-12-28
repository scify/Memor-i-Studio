var FormsIonRangeSlider = {

	basic: function () {
		$('.ionrange-basic').ionRangeSlider();
	},

	money1: function () {
		$('.ionrange-money1').ionRangeSlider({
			min: 0,
			max: 5000,
			type: 'double',
			prefix: '$',
			maxPostfix: '+',
			prettify: false,
			hasGrid: true,
			gridMargin: 7
		});
	},

	money2: function () {
		$('.ionrange-money2').ionRangeSlider({
			min: 1000,
			max: 100000,
			from: 30000,
			to: 90000,
			type: 'double',
			step: 500,
			postfix: ' €',
			hasGrid: true,
			gridMargin: 15
		});
	},

	carat: function () {
		$('.ionrange-carat').ionRangeSlider({
			min: 0,
			max: 10,
			type: 'single',
			step: 0.1,
			postfix: ' carats',
			prettify: false,
			hasGrid: true
		});
	},

	temperature: function () {
		$('.ionrange-temperature').ionRangeSlider({
			min: -50,
			max: 50,
			from: 0,
			postfix: '°',
			prettify: false,
			hasGrid: true
		});
	},

	month: function () {
		$('.ionrange-month').ionRangeSlider({
			values: [
				"January", "February",
				"March", "April",
				"May", "June",
				"July", "August",
				"September", "October",
				"November", "December"
			],
			type: 'single',
			hasGrid: true
		});
	},

	callbacks: function () {
		$('.ionrange-callbacks').ionRangeSlider({
			min: 1000000,
			max: 100000000,
			type: 'double',
			postfix: ' pounds',
			step: 10000,
			from: 25000000,
			to: 35000000,
			onChange: function(obj) {
				$('.ionrange-callbacks-value-min').text(obj.from);
				$('.ionrange-callbacks-value-max').text(obj.to);
			},
			onStart: function(obj) {
				$('.ionrange-callbacks-value-min').text(obj.from);
				$('.ionrange-callbacks-value-max').text(obj.to);
			}
		});
	},

	init: function () {
		this.basic();
		this.money1();
		this.money2();
		this.carat();
		this.temperature();
		this.month();
		this.callbacks();
	}
}




