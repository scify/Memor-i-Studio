var FormsPickers = {

	colorPicker: function () {
		$('.bs-colorpicker').colorpicker();
	},

	colorPickerRgba: function () {
		$('.bs-colorpicker-rgba').colorpicker({
			format: 'rgba'
		});
	},

	colorPickerWheel: function () {
		$('.minicolors').each( function() {
			$(this).minicolors({
				control: $(this).attr('data-control') || 'hue',
				defaultValue: $(this).attr('data-defaultValue') || '',
				inline: $(this).attr('data-inline') === 'true',
				letterCase: $(this).attr('data-letterCase') || 'lowercase',
				opacity: $(this).attr('data-opacity'),
				position: $(this).attr('data-position') || 'bottom left',
				change: function(hex, opacity) {
					if( !hex ) return;
					if( opacity ) hex += ', ' + opacity;
					try {
						console.log(hex);
					} catch(e) {}
				},
				theme: 'bootstrap'
			});
		});
	},

	dateRangePicker: function () {
		$('.bootstrap-daterangepicker-basic').daterangepicker({
			singleDatePicker: true
			}, function(start, end, label) {
				console.log(start.toISOString(), end.toISOString(), label);
			}
		);
	},

	dateRangePickerRange: function () {
		$('.bootstrap-daterangepicker-basic-range').daterangepicker(null, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
	},

	dateRangePickerTime: function () {
		$('.bootstrap-daterangepicker-date-time').daterangepicker({
			timePicker: true,
			timePickerIncrement: 30,
			format: 'MM/DD/YYYY h:mm A'
			}, function(start, end, label) {
				console.log(start.toISOString(), end.toISOString(), label);
		});
	},

	dateRangePickerBootstrap: function () {
		$('.bootstrap-daterangepicker-dropdown span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
		$('.bootstrap-daterangepicker-dropdown').daterangepicker();
	},

	dateRangePickerSpecific: function () {
		$('.bootstrap-daterangepicker-specific').daterangepicker({
			startDate: moment().subtract(29, 'days'),
			endDate: moment(),
			opens: 'left',
			showDropdowns: true,
			showWeekNumbers: true,
			timePicker: false,
			timePickerIncrement: 1,
			timePicker12Hour: true,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		},
		function (start, end) {
				$('.bootstrap-daterangepicker-specific span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}
		);
	},

	clockface: function () {
		$('#clockface1').clockface();
	},

	clockfaceButton: function () {
		$('#clockface2').clockface({
			format: 'HH:mm',
			trigger: 'manual'
		});
		$('#clockface-toggle').click(function(e){
			e.stopPropagation();
			$('#clockface2').clockface('toggle');
		});
	},

	clockfaceInline: function () {
		$('#clockface3').clockface({
			format: 'H:mm'
		}).clockface('show', '14:30');
	},


	init: function () {
		this.colorPicker();
		this.colorPickerRgba();
		this.colorPickerWheel();

		this.dateRangePicker();
		this.dateRangePickerRange();
		this.dateRangePickerTime();
		this.dateRangePickerBootstrap();
		this.dateRangePickerSpecific();

		this.clockface();
		this.clockfaceButton();
		this.clockfaceInline();
	}
}

