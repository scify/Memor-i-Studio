var FormsNoUISlider = {

	basic: function () {
		$('.nouislider-basic').noUiSlider({
			start: [25, 75],
			connect: true,
			range: {
				'min': 0,
				'max': 100
			}
		});
	},

	range: function () {
		$('.nouislider-range').noUiSlider({
			start: [4000],
			range: {
				'min': 2000,
				'max': 10000
			}
		});
		$('.nouislider-range').Link('lower').to($('.nouislider-range-value'));
	},

	step: function () {
		$('.nouislider-step').noUiSlider({
			start: [4000],
			step: 1000,
			range: {
				'min': 2000,
				'max': 10000
			}
		});
		$('.nouislider-step').Link('lower').to($('.nouislider-step-value'));
	},

	handle: function () {
		$('.nouislider-handle').noUiSlider({
			start: [80],
			range: {
				'min': 0,
				'max': 100
			}
		});
		// Set the slider value to 20
		$('#nouislider-handle-set').click(function(){
			$('.nouislider-handle').val( 20 );
		});

		// Read the slider value.
		$('#nouislider-handle-read').click(function(){
			alert( $('.nouislider-handle').val() );
		});
	},

	formatting: function () {
		$('.nouislider-formatting').noUiSlider({
			start: [ 45750 ],
			step: 250,
			range: {
				'min': [ 10000 ],
				'max': [ 80000 ]
			},
				format: wNumb({
				decimals: 3,
				thousand: '.',
				postfix: ' (US $)',
			})
		});
		$('.nouislider-formatting').Link('lower').to($('.nouislider-formatting-value'));
	},

	limits: function () {
		$('.nouislider-limit').noUiSlider({
			start: [ 10, 120 ],
			limit: 40,
			behaviour: 'drag-fixed',
			connect: true,
			range: {
				'min': 0,
				'max': 100
			}
		});
		$('.nouislider-limit').Link('lower').to( $('.nouislider-limit-value-min') )
		$('.nouislider-limit').Link('upper').to( $('.nouislider-limit-value-max') );
	},

	rtl: function () {
		$('.nouislider-rtl').noUiSlider({
			start: 20,
			direction: "rtl",
			range: {
				'min': 0,
				'max': 100
			}
		});
		$('.nouislider-rtl').Link('lower').to($('.nouislider-rtl-value'));
	},

	toggle: function () {
		function toggle( value ){
			$(this).toggleClass('off', value === '0');
		}
		$('.nouislider-toggle').noUiSlider({
			orientation: 'vertical',
			start: 0,
			range: {
				'min': [0, 1],
				'max': 1
			},
			format: wNumb({
				decimals: 0
			})
		})
		$('.nouislider-toggle').addClass('nouiToggle');
		$('.nouislider-toggle').Link('lower').to(toggle);
		$('.nouislider-toggle').Link('lower').to($('.nouislider-toggle-value'));
	},

	vertical: function () {
		$('.nouislider-vertical').noUiSlider({
			start: 40,
			orientation: 'vertical',
			range: {
				'min': 0,
				'max': 100
			}
		});
		$('.nouislider-vertical').Link('lower').to($('.nouislider-vertical-value'));
	},

	date: function () {
		// Create a new date from a string, return as a timestamp.
		function timestamp(str){
			return new Date(str).getTime();
		}
		// The nth function was borrowed from this StackOverflow question. http://stackoverflow.com/questions/15397372/javascript-new-date-ordinal-st-nd-rd-th
		// Create a list of day and monthnames.
		var
		weekdays = [
			"Sunday", "Monday", "Tuesday",
			"Wednesday", "Thursday", "Friday",
			"Saturday"
		],
		months = [
			"January", "February", "March",
			"April", "May", "June", "July",
			"August", "September", "October",
			"November", "December"
		];

		// Append a suffix to dates.
		// Example: 23 => 23rd, 1 => 1st.
		function nth (d) {
			if(d>3 && d<21) return 'th';
			switch (d % 10) {
				case 1:  return "st";
				case 2:  return "nd";
				case 3:  return "rd";
				default: return "th";
			}
		}

		// Create a string representation of the date.
		function formatDate ( date ) {
			return weekdays[date.getDay()] + ", " +
			date.getDate() + nth(date.getDate()) + " " +
			months[date.getMonth()] + " " +
			date.getFullYear();
		}

		// Write a date as a pretty value.
		function setDate( value ){
			$(this).html(formatDate(new Date(+value)));
		}

		// Setup
		$('.nouislider-date').noUiSlider({
			// Create two timestamps to define a range.
			range: {
				min: timestamp('2013'),
				max: timestamp('2017')
			},

			// Steps of one week
			step: 7 * 24 * 60 * 60 * 1000,

			// Two more timestamps indicate the handle starting positions.
			start: [ timestamp('2014'), timestamp('2015') ],

			// No decimals
			format: wNumb({
				decimals: 0
			})
		});
		// Slider Control
		$('.nouislider-date').Link('lower').to($('.nouislider-date-value-min'), setDate);
		$('.nouislider-date').Link('upper').to($('.nouislider-date-value-max'), setDate);
	},

	getPipsRange: function () {
		var rangeAllSliders = {
			'min': [     0 ],
			'10%': [   500,  500 ],
			'50%': [  4000, 1000 ],
			'max': [ 10000 ]
		};
		return rangeAllSliders;
	},

	setPipsRange: function () {
		$('.nouislider-pips-range').noUiSlider_pips({
			mode: 'range',
			density: 3
		});
	},

	pips: function () {
		$('.nouislider-pips-range-basic').noUiSlider({
			range: FormsNoUISlider.getPipsRange(),
			start: 1500
		});
		// FormsNoUISlider.setPipsRange(); // Called on init() function
	},

	pipsRtl: function () {
		$('.nouislider-pips-range-rtl').noUiSlider({
			range: FormsNoUISlider.getPipsRange(),
			start: 1500,
			direction: 'rtl'
		});
		// FormsNoUISlider.setPipsRange(); // Called on init() function
	},

	pipsVertical: function () {
		$('.nouislider-pips-range-vertical').noUiSlider({
			range: FormsNoUISlider.getPipsRange(),
			start: 1500,
			orientation: 'vertical'
		});
		// FormsNoUISlider.setPipsRange(); // Called on init() function
	},

	pipsRtlVertical: function () {
		$('.nouislider-pips-range-vertical-rtl').noUiSlider({
			range: FormsNoUISlider.getPipsRange(),
			start: 1500,
			orientation: 'vertical',
			direction: 'rtl'
		});
		// FormsNoUISlider.setPipsRange(); // Called on init() function
	},

	pipsValues: function () {
		$('.nouislider-pips-value-basic').noUiSlider({
			range: FormsNoUISlider.getPipsRange(),
			start: 2500
		});
		$('.nouislider-pips-value').noUiSlider_pips({
			mode: 'values',
			values: [50, 552, 2651, 3952, 5000, 7080, 9000],
			density: 4,
			stepped: true
		});
	},

	pipsPositions: function () {
		$('.nouislider-pips-positions-basic').noUiSlider({
			range: FormsNoUISlider.getPipsRange(),
			start: 3500
		});
		$('.nouislider-pips-positions').noUiSlider_pips({
			mode: 'positions',
			values: [0,25,50,75,100],
			density: 4,
			stepped: true
		});
	},

	init: function () {
		this.basic();
		this.range();
		this.step();
		this.handle();
		this.formatting();
		this.limits();
		this.rtl();
		this.toggle();
		this.vertical();
		this.date();
		this.pips();
		this.pipsRtl();
		this.pipsVertical();
		this.pipsRtlVertical();
		this.pipsValues();
		this.pipsPositions();

		this.setPipsRange(); // initilized for all Pips functions
	}
}

