var FormsTools = {

	inputMaskDate1: function () {
		$('.inputmask-date1').inputmask('d/m/y', {
			autoUnmask: true
		});
	},

	inputMaskDate2: function () {
		$('.inputmask-date2').inputmask('m/d/y', {
			autoUnmask: true
		});
	},

	inputMaskDate3: function () {
		$('.inputmask-date3').inputmask('d/m/y', {
			'placeholder': 'dd/mm/yyyy'
		});
	},

	inputMaskCurrency: function () {
		$('.inputmask-currency').inputmask('€ 999.999.999,99', {
			numericInput: true
		});
	},

	inputMaskDecimal: function () {
		$('.inputmask-decimal').inputmask('decimal', {
			radixPoint: ',',
			autoGroup: true,
			groupSeparator: '.',
			groupSize: 3,
			rightAlign: false
		});
	},

	inputMaskDecimalRight: function () {
		$('.inputmask-decimal-right').inputmask('decimal', {
			radixPoint: ',',
			autoGroup: true,
			groupSeparator: '.',
			groupSize: 3
		});
	},

	inputMaskPhone: function () {
		$('.inputmask-phone').inputmask('mask', {
			'mask': '(999) 999-9999'
		});
	},

	inputMaskEmail: function () {
		$('.inputmask-email').inputmask({
			mask: '*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]',
			greedy: false,
			definitions: {
				'*': {
					validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
					cardinality: 1,
					casing: "lower"
				}
			}
		});
	},

	inputMaskIpV4: function () {
		$('.inputmask-ipv4').inputmask('ip');
	},

	/*inputMaskIpV6: function () {
		$('.inputmask-ipv6').ipAddress({
			v:6
		});
	},*/

	paymentNumber: function () {
		$('.payment-number').payment('formatCardNumber');
	},

	paymentExp: function () {
		$('.payment-exp').payment('formatCardExpiry');
	},

	paymentCvc: function () {
		$('.payment-cvc').payment('formatCardCVC');
	},

	paymentRestrict: function () {
		$('.payment-restrict').payment('restrictNumeric');
	},

	googleCaptcha: function () {
		Recaptcha.create("6LcwjPoSAAAAAH4UDgFWXETiMZcarsXRydCmpj6v", 'recaptcha_div', {
			theme: "red",
			callback: Recaptcha.focus_response_field
		});
	},

	passwordStrength: function () {
		$('#password-strength').indicator();
	},

	passwordStrength2: function () {
		var options = {};
		options.ui = {
			container: '.pwd-container',
			showVerdictsInsideProgressBar: false,
			viewports: {
				progress: '.pwstrength_viewport_progress',
				verdict: '.pwstrength_viewport_verdict'
			},
			showStatus: true
		};
		$('.password-strength2').pwstrength(options);
	},

	maxLength: function () {
		$('.maxlength-default').maxlength();
	},

	maxLengthThreshold: function () {
		$('.maxlength-threshold').maxlength();
	},

	maxLengthOptions: function () {
		$('.maxlength-options').maxlength({
			alwaysShow: true,
			threshold: 10,
			warningClass: "label label-success",
			limitReachedClass: "label label-danger",
			separator: ' of ',
			preText: 'You have ',
			postText: ' chars remaining.',
			validate: true
		});
	},

	maxLengthTextarea: function () {
		$('.maxlength-textarea').maxlength({
			alwaysShow: true
		});
	},

	maxLengthPositions: function () {
		$('.maxlength-position').maxlength({
			alwaysShow: true,
			placement: 'centered-right'
		});
	},

	spinnerPostFix: function () {
		$('.touchspin-postfix').TouchSpin({
			min: 0,
			max: 100,
			step: 0.1,
			decimals: 2,
			boostat: 5,
			maxboostedstep: 10,
			postfix: '%'
		});
	},

	spinnerPreFix: function () {
		$('.touchspin-prefix').TouchSpin({
			min: 0,
			max: 20,
			stepinterval: 50,
			maxboostedstep: 10000000,
			prefix: '$'
		});
	},

	spinnerVertical: function () {
		$('.touchspin-vertical').TouchSpin({
			verticalbuttons: true
		});
	},

	spinnerStep: function () {
		$('.touchspin-step').TouchSpin({
			min: -10,
			max: 30,
			step: 10
		});
	},

	spinnerIcons: function () {
		$('.touchspin-icons').TouchSpin({
			verticalbuttons: true,
			verticalupclass: 'ion-chevron-up',
			verticaldownclass: 'ion-chevron-down'
		});
	},

	spinnerClass: function () {
		$('.touchspin-class').TouchSpin({
			buttondown_class: 'btn btn-primary',
			buttonup_class: 'btn btn-danger'
		});
	},

	typeahead: function () {
		// instantiate the bloodhound suggestion engine
		var numbers = new Bloodhound({
			datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.num); },
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: [
				{ num: 'one' },
				{ num: 'two' },
				{ num: 'three' },
				{ num: 'four' },
				{ num: 'five' },
				{ num: 'six' },
				{ num: 'seven' },
				{ num: 'eight' },
				{ num: 'nine' },
				{ num: 'ten' }
			]
		});

		// initialize the bloodhound suggestion engine
		numbers.initialize();

		// Typeahead example 1
		$('.typeahead-basic').typeahead(null, {
			displayKey: 'num',
			source: numbers.ttAdapter()
		});
	},

	typeaheadPrefetch: function () {
		var countries = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 10,
			prefetch: {
				// url points to a json file that contains an array of country names, see
				// https://github.com/twitter/typeahead.js/blob/gh-pages/countries.json
				url: 'demo/typeahead/countries.json',
				// the json file contains an array of strings, but the Bloodhound
				// suggestion engine expects JavaScript objects so this converts all of
				// those strings
				filter: function(list) {
					return $.map(list, function(country) { return { name: country }; });
				}
			}
		});
		// kicks off the loading/processing of `local` and `prefetch`
		countries.initialize();
		// passing in `null` for the `options` arguments will result in the default
		// options being used
		$('.typeahead-prefetch').typeahead(null, {
			name: 'countries',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: countries.ttAdapter()
		});
	},

	typeaheadCustom: function () {
		var bestPictures = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: 'demo/typeahead/post_1960.json',
			remote: 'demo/typeahead/query.php?query=%QUERY'
		});

		bestPictures.initialize();

		// custom templates
		// ----------------

		$('.typeahead-custom').typeahead(null, {
			name: 'best-pictures',
			displayKey: 'value',
			source: bestPictures.ttAdapter(),
			templates: {
				empty: [
					'<div class="empty-message">',
					'unable to find any oscar winners that match the current query',
					'</div>'
				].join('\n'),
				suggestion: Handlebars.compile('<p><strong>{{value}}</strong> – {{year}}</p>')
			}
		});
	},

	typeaheadMultiple: function () {
		var nbaTeams = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: 'demo/typeahead/nba.json'
		});

		var nhlTeams = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: 'demo/typeahead/nhl.json'
		});

		nbaTeams.initialize();
		nhlTeams.initialize();

		$('.typeahead-data-sets').typeahead({
			highlight: true
		},
		{
			name: 'nba-teams',
			displayKey: 'team',
			source: nbaTeams.ttAdapter(),
			templates: {
				header: '<h3 class="league-name">NBA Teams</h3>'
			}
		},
		{
			name: 'nhl-teams',
			displayKey: 'team',
			source: nhlTeams.ttAdapter(),
			templates: {
				header: '<h3 class="league-name">NHL Teams</h3>'
			}
		});
	},

	typeaheadScrollable: function () {
		var countries = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 10,
			prefetch: {
				// url points to a json file that contains an array of country names, see
				// https://github.com/twitter/typeahead.js/blob/gh-pages/countries.json
				url: 'demo/typeahead/countries.json',
				// the json file contains an array of strings, but the Bloodhound
				// suggestion engine expects JavaScript objects so this converts all of
				// those strings
				filter: function(list) {
					return $.map(list, function(country) { return { name: country }; });
				}
			}
		});
		// kicks off the loading/processing of `local` and `prefetch`
		countries.initialize();

		$('.typeahead-scrollable').typeahead(null, {
			name: 'countries',
			displayKey: 'name',
			source: countries.ttAdapter()
		});
	},

	typeaheadRtl: function () {
		var arabicPhrases = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('word'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: [
				{ word: "الإنجليزية" },
				{ word: "نعم" },
				{ word: "لا" },
				{ word: "مرحبا" },
				{ word: "أهلا" }
			]
		});

		arabicPhrases.initialize();

		$('.typeahead-rtl').typeahead({
				hint: false
			},
			{
			name: 'arabic-phrases',
			displayKey: 'word',
			source: arabicPhrases.ttAdapter()
		});
	},

	textComplete: function () {
		$('.text-complete-basic').textcomplete([
			{ // emoji strategy
				match: /\B:([\-+\w]*)$/,
				search: function (term, callback) {
						callback($.map(emojies, function (emoji) {
								return emoji.indexOf(term) === 0 ? emoji : null;
						}));
				},
				template: function (value) {
						return '<img src="assets/plugins/emojify/images/emoji/' + value + '.png">' + value;
				},
				replace: function (value) {
						return ':' + value + ': ';
				},
				index: 1
			},
			{ // tech companies
				words: ['apple', 'google', 'facebook', 'github'],
				match: /\b(\w{2,})$/,
				search: function (term, callback) {
						callback($.map(this.words, function (word) {
								return word.indexOf(term) === 0 ? word : null;
						}));
				},
				index: 1,
				replace: function (word) {
						return word + ' ';
				}
			}
		]);
	},

	textCompleteHtml: function () {
		var htmlelements = ['span', 'div', 'h1', 'h2', 'h3'];
		$('.text-complete-html').textcomplete([
			{ // html
				match: /<(\w*)$/,
				search: function (term, callback) {
					callback($.map(htmlelements, function (element) {
						return element.indexOf(term) === 0 ? element : null;
					}));
				},
				index: 1,
				replace: function (element) {
					return ['<' + element + '>', '</' + element + '>'];
				}
			}
		]);
	},

	textCompleteOverlay: function () {
		$('.text-complete-overlay').textcomplete([
			{ // html
				mentions: ['yuku_t'],
				match: /\B@(\w*)$/,
				search: function (term, callback) {
					callback($.map(this.mentions, function (mention) {
						return mention.indexOf(term) === 0 ? mention : null;
					}));
				},
				index: 1,
				replace: function (mention) {
					return '@' + mention + ' ';
				}
			}
			], { appendTo: 'body' }).overlay([
				{
					match: /\B@\w+/g,
					css: {
						'background-color': '#d8dfea'
					}
			}
		]);
	},

	textCompleteContentEditable: function () {
		$('.text-complete-contenteditable').textcomplete([
			{ // tech companies
				words: ['stunning', 'amazing', 'incredible', 'fantastic', 'fantabulous'],
				match: /\b(\w{2,})$/,
				search: function (term, callback) {
					callback($.map(this.words, function (word) {
						return word.indexOf(term) === 0 ? word : null;
					}));
				},
				index: 1,
				replace: function (word) {
					return word + ' ';
				}
			}
		]);
	},

	geoComplete: function () {
		$('.geocomplete-basic').geocomplete().bind('geocode:result', function(event, result){
			$(this).parents('.form-group').find('.logger').text('Result: ' + result.formatted_address);
		}).bind('geocode:error', function(event, status){
			$(this).parents('.form-group').find('.logger').text('ERROR: ' + status);
		}).bind('geocode:multiple', function(event, results){
			$(this).parents('.form-group').find('.logger').text('Multiple: ' + results.length + ' results found');
		});
	},

	geoCompleteMap: function () {
		$('.geocomplete-basic-map').geocomplete({
			map: '.geocomplete-basic-map-target'
		}).bind('geocode:result', function(event, result){
			$(this).parents('.form-group').find('.logger').text('Result: ' + result.formatted_address);
		}).bind('geocode:error', function(event, status){
			$(this).parents('.form-group').find('.logger').text('ERROR: ' + status);
		}).bind('geocode:multiple', function(event, results){
			$(this).parents('.form-group').find('.logger').text('Multiple: ' + results.length + ' results found');
		});
	},

	geoCompleteInitial: function () {
		$('.geocomplete-basic-map-initial').geocomplete({
			map: '.geocomplete-basic-map-initial-target',
			location: 'NYC'
		}).bind('geocode:result', function(event, result){
			$(this).parents('.form-group').find('.logger').text('Result: ' + result.formatted_address);
		}).bind('geocode:error', function(event, status){
			$(this).parents('.form-group').find('.logger').text('ERROR: ' + status);
		}).bind('geocode:multiple', function(event, results){
			$(this).parents('.form-group').find('.logger').text('Multiple: ' + results.length + ' results found');
		});
	},

	geoCompleteData: function () {
		$('.geocomplete-map-data').geocomplete({
			map: '.geocomplete-map-data-target',
			details: '.geocomplete-map-data-logger',
			detailsAttribute : 'data-geo',
			types: ['geocode', 'establishment']
		});
	},

	geoCompleteDraggable: function () {
		$('.geocomplete-map-draggable').geocomplete({
			map: '.geocomplete-map-draggable-target',
			location: '111 Broadway, New York, NY',
			details: '.geocomplete-map-draggable-logger',
			detailsAttribute : 'data-geo',
			markerOptions: {
				draggable: true
			}
		});
		$('.geocomplete-map-draggable').bind('geocode:dragged', function(event, latLng){
			$('.geocomplete-map-draggable-logger span[data-geo=lat]').text(latLng.lat());
			$('.geocomplete-map-draggable-logger span[data-geo=lng]').text(latLng.lng());
		});
	},

	init: function () {
		this.inputMaskDate1();
		this.inputMaskDate2();
		this.inputMaskDate3();
		this.inputMaskCurrency();
		this.inputMaskDecimal();
		this.inputMaskDecimalRight();
		this.inputMaskPhone();
		this.inputMaskEmail();
		this.inputMaskIpV4();
		//this.inputMaskIpV6();

		this.paymentNumber();
		this.paymentExp();
		this.paymentCvc();
		this.paymentRestrict();

		//this.googleCaptcha();

		this.passwordStrength();
		this.passwordStrength2();

		this.maxLength();
		this.maxLengthThreshold();
		this.maxLengthOptions();
		this.maxLengthTextarea();
		this.maxLengthPositions();

		this.spinnerPostFix();
		this.spinnerPreFix();
		this.spinnerVertical();
		this.spinnerStep();
		this.spinnerIcons();
		this.spinnerClass();

		this.typeahead();
		this.typeaheadPrefetch();
		this.typeaheadCustom();
		this.typeaheadMultiple();
		this.typeaheadScrollable();
		this.typeaheadRtl();

		this.textComplete();
		this.textCompleteHtml();
		this.textCompleteOverlay();
		this.textCompleteContentEditable();

		this.geoComplete();
		this.geoCompleteMap();
		this.geoCompleteInitial();
		this.geoCompleteData();
		this.geoCompleteDraggable();
	}
}




