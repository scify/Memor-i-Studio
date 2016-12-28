var FormsSelect = {

	chosenSelect: function () {
		$('.chosen-select').chosen({
			width: '100%'
		});
	},

	chosenSelectNoSingle: function () {
		$('.chosen-select-no-single').chosen({
			width: '100%',
			disable_search_threshold: 10 // If there are fewer than (n) options
		});
	},

	selectize: function () {
		$('.selectize-default').selectize({
			persist: false,
			createOnBlur: true,
			create: true
		});
	},

	selectizeRemove: function () {
		$('.selectize-remove-button').selectize({
			plugins: ['remove_button'],
			persist: false,
			create: true,
			onDelete: function(values) {
				return confirm(values.length > 1 ? 'Are you sure you want to remove these ' + values.length + ' items?' : 'Are you sure you want to remove "' + values[0] + '"?');
			}
		});
	},

	selectizeSortable: function () {
		$('.selectize-sortable').selectize({
			plugins: ['drag_drop'],
			persist: false,
			create: true
		});
	},

	selectizeHeader: function () {
		$('.selectize-header').selectize({
			sortField: 'text',
			hideSelected: false,
			plugins: {
				'dropdown_header': {
					title: 'Languages'
				}
			}
		});
	},

	selectizeProgrammatic: function () {
		$('.selectize-programmatic').selectize({
			options: [
				{class: 'mammal', value: "dog", name: "Dog" },
				{class: 'mammal', value: "cat", name: "Cat" },
				{class: 'mammal', value: "horse", name: "Horse" },
				{class: 'mammal', value: "kangaroo", name: "Kangaroo" },
				{class: 'bird', value: 'duck', name: 'Duck'},
				{class: 'bird', value: 'chicken', name: 'Chicken'},
				{class: 'bird', value: 'ostrich', name: 'Ostrich'},
				{class: 'bird', value: 'seagull', name: 'Seagull'},
				{class: 'reptile', value: 'snake', name: 'Snake'},
				{class: 'reptile', value: 'lizard', name: 'Lizard'},
				{class: 'reptile', value: 'alligator', name: 'Alligator'},
				{class: 'reptile', value: 'turtle', name: 'Turtle'}
			],
			optgroups: [
				{value: 'mammal', label: 'Mammal', label_scientific: 'Mammalia'},
				{value: 'bird', label: 'Bird', label_scientific: 'Aves'},
				{value: 'reptile', label: 'Reptile', label_scientific: 'Reptilia'}
			],
			optgroupField: 'class',
			labelField: 'name',
			searchField: ['name'],
			render: {
				optgroup_header: function(data, escape) {
					return '<div class="optgroup-header">' + escape(data.label) + ' <span class="scientific">' + escape(data.label_scientific) + '</span></div>';
				}
			}
		});
	},

	selectizeColumns: function () {
		$('.selectize-columns').selectize({
			options: [
				{id: 'avenger', make: 'dodge', model: 'Avenger'},
				{id: 'caliber', make: 'dodge', model: 'Caliber'},
				{id: 'caravan-grand-passenger', make: 'dodge', model: 'Caravan Grand Passenger'},
				{id: 'challenger', make: 'dodge', model: 'Challenger'},
				{id: 'ram-1500', make: 'dodge', model: 'Ram 1500'},
				{id: 'viper', make: 'dodge', model: 'Viper'},
				{id: 'a3', make: 'audi', model: 'A3'},
				{id: 'a6', make: 'audi', model: 'A6'},
				{id: 'r8', make: 'audi', model: 'R8'},
				{id: 'rs-4', make: 'audi', model: 'RS 4'},
				{id: 's4', make: 'audi', model: 'S4'},
				{id: 's8', make: 'audi', model: 'S8'},
				{id: 'tt', make: 'audi', model: 'TT'},
				{id: 'avalanche', make: 'chevrolet', model: 'Avalanche'},
				{id: 'aveo', make: 'chevrolet', model: 'Aveo'},
				{id: 'cobalt', make: 'chevrolet', model: 'Cobalt'},
				{id: 'silverado', make: 'chevrolet', model: 'Silverado'},
				{id: 'suburban', make: 'chevrolet', model: 'Suburban'},
				{id: 'tahoe', make: 'chevrolet', model: 'Tahoe'},
				{id: 'trail-blazer', make: 'chevrolet', model: 'TrailBlazer'},
			],
			optgroups: [
				{id: 'dodge', name: 'Dodge'},
				{id: 'audi', name: 'Audi'},
				{id: 'chevrolet', name: 'Chevrolet'}
			],
			labelField: 'model',
			valueField: 'id',
			optgroupField: 'make',
			optgroupLabelField: 'name',
			optgroupValueField: 'id',
			optgroupOrder: ['chevrolet', 'dodge', 'audi'],
			searchField: ['model'],
			plugins: ['optgroup_columns']
		});

	},

	selectizeContacts: function () {
		var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
													'(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

		$('.selectize-contacts').selectize({
			persist: false,
			maxItems: null,
			valueField: 'email',
			labelField: 'name',
			searchField: ['first_name', 'last_name', 'email'],
			sortField: [
				{field: 'first_name', direction: 'asc'},
				{field: 'last_name', direction: 'asc'}
			],
			options: [
				{email: 'nikola@tesla.com', first_name: 'Nikola', last_name: 'Tesla'},
				{email: 'brian@thirdroute.com', first_name: 'Brian', last_name: 'Reavis'},
				{email: 'someone@gmail.com'}
			],
			render: {
				item: function(item, escape) {
					var name = FormsSelect.formatName(item);
					return '<div>' +
						(name ? '<span class="name">' + escape(name) + '</span>' : '') +
						(item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
					'</div>';
				},
				option: function(item, escape) {
					var name = FormsSelect.formatName(item);
					var label = name || item.email;
					var caption = name ? item.email : null;
					return '<div>' +
						'<span class="label">' + escape(label) + '</span>' +
						(caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
					'</div>';
				}
			},
			createFilter: function(input) {
				var regexpA = new RegExp('^' + REGEX_EMAIL + '$', 'i');
				var regexpB = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
				return regexpA.test(input) || regexpB.test(input);
			},
			create: function(input) {
				if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
					return {email: input};
				}
				var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
				if (match) {
					var name       = $.trim(match[1]);
					var pos_space  = name.indexOf(' ');
					var first_name = name.substring(0, pos_space);
					var last_name  = name.substring(pos_space + 1);

					return {
						email: match[2],
						first_name: first_name,
						last_name: last_name
					};
				}
				alert('Invalid email address.');
				return false;
			}
		});

	},

	formatName: function (item) {
		return $.trim((item.first_name || '') + ' ' + (item.last_name || ''));
	},

	selectizeCustomization: function () {
		$('.selectize-customization').selectize({
			theme: 'links',
			maxItems: null,
			valueField: 'id',
			searchField: 'name',
			options: [
				{id: 1, name: 'Tolga Ergin', email: 'tolgaergin@gmail.com', photo: Pleasure.settings.paths.images+'/faces/1.jpg'},
				{id: 2, name: 'Gökhun Güneyhan', email: 'gokhun@mail.org', photo: Pleasure.settings.paths.images+'/faces/2.jpg'},
				{id: 3, name: 'Michelle Myers', email: 'mymichelle@mymichelle.org', photo: Pleasure.settings.paths.images+'/faces/3.jpg'},
			],
			render: {
				option: function(data, escape) {
					return '<div class="option">' +
							'<div class="photo"><img src="' + escape(data.photo) + '"></div>' +
							'<span class="name">' + escape(data.name) + '</span>' +
							'<span class="email">' + escape(data.email) + '</span>' +
						'</div>';
				},
				item: function(data, escape) {
					return '<div class="item clearfix"><div class="photo"><img src="'+ escape(data.photo) +'"></div> <span class="name">'+ escape(data.name) + '</span></div>';
				}
			},
			create: false
		});
	},

	selectizeState: function () {
		var xhr;
		var select_state, $select_state;
		var select_city, $select_city;

		$select_state = $('.selectize-state').selectize({
			onChange: function(value) {
				if (!value.length) return;
				select_city.disable();
				select_city.clearOptions();
				select_city.load(function(callback) {
					xhr && xhr.abort();
					xhr = $.ajax({
						url: 'http://www.corsproxy.com/api.sba.gov/geodata/primary_city_links_for_state_of/' + value + '.json',
						success: function(results) {
							select_city.enable();
							callback(results);
						},
						error: function() {
							callback();
						}
					})
				});
			}
		});

		$select_city = $('.selectize-city').selectize({
			valueField: 'name',
			labelField: 'name',
			searchField: ['name']
		});

		select_city  = $select_city[0].selectize;
		select_state = $select_state[0].selectize;

		select_city.disable();
	},

	selectizeRemote: function () {
		$('.selectize-remote').selectize({
			theme: 'repositories',
			valueField: 'url',
			labelField: 'name',
			searchField: 'name',
			options: [],
			create: false,
			render: {
				option: function(item, escape) {
					return '<div>' +
						'<span class="title">' +
							'<span class="name"><i class="icon ' + (item.fork ? 'fork' : 'source') + '"></i>' + escape(item.name) + '</span>' +
							'<span class="by">' + escape(item.username) + '</span>' +
						'</span>' +
						'<span class="description">' + escape(item.description) + '</span>' +
						'<ul class="meta">' +
							(item.language ? '<li class="language">' + escape(item.language) + '</li>' : '') +
							'<li class="watchers"><span>' + escape(item.watchers) + '</span> watchers</li>' +
							'<li class="forks"><span>' + escape(item.forks) + '</span> forks</li>' +
						'</ul>' +
					'</div>';
				}
			},
			score: function(search) {
				var score = this.getScoreFunction(search);
				return function(item) {
					return score(item) * (1 + Math.min(item.watchers / 100, 1));
				};
			},
			load: function(query, callback) {
				if (!query.length) return callback();
				$.ajax({
					url: 'https://api.github.com/legacy/repos/search/' + encodeURIComponent(query),
					type: 'GET',
					error: function() {
						callback();
					},
					success: function(res) {
						callback(res.repositories.slice(0, 10));
					}
				});
			}
		});
	},

	selectizeDisabled: function () {
		$('.selectize-locked').selectize({
			create: true
		});
		$('.selectize-locked')[0].selectize.lock();
	},

	selectizeRtl: function () {
		$('.selectize-rtl').selectize({
			persist: false,
			create: true
		});
	},

	multiSelect: function () {
		$('.multiselect-preselected').multiSelect();
	},

	multiSelectCallbacks: function () {
		$('.multiselect-callbacks').multiSelect({
			afterSelect: function(values){
					alert("Select value: "+ values);
			},
			afterDeselect: function(values){
				alert("Deselect value: "+ values);
			}
		});
	},

	multiSelectOptionGroups: function () {
		$('.multiselect-optgroup').multiSelect({
			selectableOptgroup: true
		});
	},

	multiSelectHeaders: function () {
		$('.multiselect-headers').multiSelect({
			selectableHeader: '<div class="custom-header">Selectable items</div>',
			selectionHeader: '<div class="custom-header">Selection items</div>',
			selectableFooter: '<div class="custom-footer">Selectable footer</div>',
			selectionFooter: '<div class="custom-footer">Selection footer</div>'
		});
	},

	multiSelectSearchable: function () {
		$('.multiselect-searchable').multiSelect({
			selectableHeader: '<input type="text" class="form-control input-sm" autocomplete="off" placeholder="try \'12\'">',
			selectionHeader: '<input type="text" class="form-control input-sm" autocomplete="off" placeholder="try \'4\'">',
			afterInit: function(ms){
				var that = this,
				$selectableSearch = that.$selectableUl.prev(),
				$selectionSearch = that.$selectionUl.prev(),
				selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
				selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

				that.qs1 = $selectableSearch.quicksearch(selectableSearchString).on('keydown', function(e){
					if (e.which === 40){
						that.$selectableUl.focus();
						return false;
					}
				});

				that.qs2 = $selectionSearch.quicksearch(selectionSearchString).on('keydown', function(e){
					if (e.which == 40){
						that.$selectionUl.focus();
						return false;
					}
				});
			},
			afterSelect: function(){
				this.qs1.cache();
				this.qs2.cache();
			},
			afterDeselect: function(){
				this.qs1.cache();
				this.qs2.cache();
			}
		});
	},

	multiSelectMethods: function () {
		$('.multiselect-methods').multiSelect();
		$('#multiselect-selectall').click(function(){
			$('.multiselect-methods').multiSelect('select_all');
			return false;
		});
		$('#multiselect-deselectall').click(function(){
			$('.multiselect-methods').multiSelect('deselect_all');
			return false;
		});
	},

	bootstrapSelect: function () {
		$('.selectpicker').selectpicker();
	},

	init: function () {
		this.chosenSelect();
		this.chosenSelectNoSingle();

		this.selectize();
		this.selectizeRemove();
		this.selectizeSortable();
		this.selectizeHeader();
		this.selectizeProgrammatic();
		this.selectizeColumns();
		this.selectizeContacts();
		this.selectizeCustomization();
		this.selectizeState();
		this.selectizeRemote();
		this.selectizeDisabled();
		this.selectizeRtl();

		this.multiSelect();
		this.multiSelectCallbacks();
		this.multiSelectOptionGroups();
		this.multiSelectHeaders();
		this.multiSelectSearchable();
		this.multiSelectMethods();

		this.bootstrapSelect();
	}
}

