var MapsVector = {

	createMap: function (type,selector) {
		options = {
			map: type+'_en',
			backgroundColor: '#fff',
			color: '#fff',
			hoverOpacity: 0.7,
			selectedColor: '#666666',
			enableZoom: true,
			showTooltip: true,
			values: sample_data,
			scaleColors: ['#C8EEFF', '#006491'],
		  normalizeFunction: 'polynomial',
			onRegionClick: function(element, code, region) {
				alert('You clicked '+region+' which has the code: '+code.toUpperCase());
			}
		};

		var map = $(selector);

		map.vectorMap(options);
	},

	world: function () {
		MapsVector.createMap('world','#vmaps_1');
	},

	europe: function () {
		MapsVector.createMap('europe','#vmaps_2');
	},

	usa: function () {
		MapsVector.createMap('usa','#vmaps_3');
	},

	germany: function () {
		MapsVector.createMap('germany','#vmaps_4');
	},

	init: function () {
		this.world();
		this.europe();
		this.usa();
		this.germany();
	}
}

