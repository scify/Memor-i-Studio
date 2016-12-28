var Index = {

	createNotification: function () {
		setTimeout( function () {
			Pleasure.handleToastrSettings(true, 'toast-bottom-left', false, 'info', true, '', 'You have 3 notifications.');
		}, 3000);
	},

	createMap: function () {
		var map;
		var markers = [];

		var mapOptions = {
			center: new google.maps.LatLng(41.386469,2.175235),
			zoom: 12,
			zoomControl: false,
			mapTypeControl: false,
			scaleControl: false,
			scrollwheel: false,
			panControl: false,
			streetViewControl: false,
			draggable : true,
			overviewMapControl: false,
			overviewMapControlOptions: {
				opened: false,
			},
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			styles: [
				{"featureType": "all",
					"stylers":[
						{"saturation": 0},
						{"hue": "#e7ecf0"}
					]
				},
				{"featureType": "road",
					"stylers":[
						{"saturation": -70}
					]
				},
				{"featureType": "transit",
					"stylers":[
						{"visibility": "off"}
					]
				},
				{"featureType": "poi",
					"stylers":[
						{"visibility": "off"}
					]
				},
				{"featureType": "water",
					"stylers":[
						{"visibility": "simplified"},
						{"saturation": -60}
					]
				}
			]
		}

		var mapElement = document.getElementById('gmaps-dashboard');
		var map = new google.maps.Map(mapElement, mapOptions);
		var locations = [['Team Fox', 'undefined', 'undefined', 'undefined', 'undefined', 41.3850639, 2.1734034999999494, Pleasure.settings.paths.images+'/map-marker-ellipse-blue.png']];

		var input = (document.getElementById('pac-input'));
		map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
		var searchBox = new google.maps.places.SearchBox((input));

		google.maps.event.addListener(searchBox, 'places_changed', function() {
			var places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}
			for (var i = 0, marker; marker = markers[i]; i++) {
				marker.setMap(null);
			}

			// For each place, get the icon, place name, and location.
			markers = [];
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0, place; place = places[i]; i++) {
				var image = {
					url: place.icon,
					size: new google.maps.Size(71, 71),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(17, 34),
					scaledSize: new google.maps.Size(25, 25)
				};

				// Create a marker for each place.
				var marker = new google.maps.Marker({
					map: map,
					icon: Pleasure.settings.paths.images+'/map-marker-ellipse-blue.png',
					title: place.name,
					position: place.geometry.location
				});

				markers.push(marker);
				bounds.extend(place.geometry.location);
			}
			map.fitBounds(bounds);
		});

		google.maps.event.addListener(map, 'bounds_changed', function() {
			var bounds = map.getBounds();
			searchBox.setBounds(bounds);
		});

		for (i = 0; i < locations.length; i++) {
			if (locations[i][1] =='undefined'){ description ='';} else { description = locations[i][1]; }
			if (locations[i][2] =='undefined'){ telephone ='';} else { telephone = locations[i][2]; }
			if (locations[i][3] =='undefined'){ email ='';} else { email = locations[i][3]; }
				if (locations[i][4] =='undefined'){ web ='';} else { web = locations[i][4]; }
				if (locations[i][7] =='undefined'){ markericon ='';} else { markericon = locations[i][7]; }
					marker = new google.maps.Marker({
						icon: markericon,
						position: new google.maps.LatLng(locations[i][5], locations[i][6]),
						map: map,
						title: locations[i][0],
						desc: description,
						tel: telephone,
						email: email,
						web: web
					});
				link = '';
		}
	},

	createSalesChart: function () {
		var chartSalesData = [ [], [] ];
		var random = new Rickshaw.Fixtures.RandomData(25);
		for (var i = 0; i < 25; i++) {
			random.addData(chartSalesData);
		}

		var chartSales = new Rickshaw.Graph( {
			element: document.querySelector('.chart-sales'),
			series: [
				{
					name: 'Admin Templates',
					color: Pleasure.colors.blue_grey,
					data: chartSalesData[0]
				}, {
					name: 'Site Templates',
					color: Pleasure.colors.blue,
					data: chartSalesData[1]
				}
			]
		});

		chartSales.render();

		// Make it responsive
		var chartSalesResize = function() {
			chartSales.configure({
				width: $('.chart-sales').width()
			});
			chartSales.render();
		}
		Pleasure.callOnResize.push( chartSalesResize );

		// Hover Details
		var chartSalesHover = new Rickshaw.Graph.HoverDetail({
			graph: chartSales,
			formatter: function(series, x, y) {
				var content = series.name + ": " + parseInt(y);
				return content;
			}
		});
		var chartSalesLegend = new Rickshaw.Graph.Legend( {
			graph: chartSales,
			element: document.querySelector('.chart-sales-legend')
		});
		var chartSalesShelving = new Rickshaw.Graph.Behavior.Series.Toggle({
			graph: chartSales,
			legend: chartSalesLegend
		});
	},

	createBalanceChart: function () {
		var chartBalanceData = [ [] ];
		var random = new Rickshaw.Fixtures.RandomData(25);
		for (var i = 0; i < 25; i++) {
			random.addData(chartBalanceData);
		}

		var chartBalance = new Rickshaw.Graph( {
			element: document.querySelector('.chart-balance'),
			series: [{
				name: 'Balance',
				color: Pleasure.colors.light_green,
				data: chartBalanceData[0]
			}]
		});

		chartBalance.render();

		// Make it responsive
		var chartBalanceResize = function() {
			chartBalance.configure({
				width: $('.chart-balance').width()
			});
			chartBalance.render();
		}
		Pleasure.callOnResize.push( chartBalanceResize );

		var chartBalanceHover = new Rickshaw.Graph.HoverDetail({
			graph: chartBalance,
			formatter: function(series, x, y) {
				var content = series.name + ": " + y.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
				return content;
			}
		});
	},

	createFollowersChart: function () {
		var chartFollowers = [ [], [], [] ];
		var random = new Rickshaw.Fixtures.RandomData(25);
		for (var i = 0; i < 25; i++) {
			random.addData(chartFollowers);
		}

		var chartFollowers = new Rickshaw.Graph({
			element: document.querySelector('.chart-followers'),
			renderer: 'bar',
			series: [
				{
					name: 'Github',
					color: '#666',
					data: chartFollowers[0]
				}, {
					name: 'Twitter',
					color: '#55acee',
					data: chartFollowers[1]
				}, {
					name: 'Dribbble',
					color: '#ea4c89',
					data: chartFollowers[2]
				}
			]
		});

		chartFollowers.render();

		// Make it responsive
		var chartFollowersResize = function() {
			chartFollowers.configure({
				width: $('.chart-followers').width()
			});
			chartFollowers.render();
		}
		Pleasure.callOnResize.push( chartFollowersResize );

		var chartFollowersHover = new Rickshaw.Graph.HoverDetail({
			graph: chartFollowers,
			formatter: function(series, x, y) {
				var content = series.name + ": " + y.toFixed(3);
				return content;
			}
		});
		var chartFollowersLegend = new Rickshaw.Graph.Legend( {
			graph: chartFollowers,
			element: document.querySelector('.chart-followers-legend')
		});
		var chartFollowersShelving = new Rickshaw.Graph.Behavior.Series.Toggle({
			graph: chartFollowers,
			legend: chartFollowersLegend
		});
	},

	createStocksChart : function () {
		var chartStocks = new Rickshaw.Graph( {
			element: document.querySelector('.chart-stocks'),
			renderer: 'lineplot',
			padding: { top: 0.1 },
			series: [{
				name: 'Apple Inc',
				color: Pleasure.colors.white,
				data: [
					{ x: 1388527200, y: 11040 },
					{ x: 1391205600, y: 9239 },
					{ x: 1393624800, y: 10631 },
					{ x: 1396303200, y: 11543 },
					{ x: 1398895200, y: 12338 },
					{ x: 1401573600, y: 13354 },
					{ x: 1404165600, y: 16189 },
					{ x: 1406844000, y: 12325 },
					{ x: 1409522400, y: 11621 },
					{ x: 1412114400, y: 11913 } ]
			}]
		});

		chartStocks.render();

		// Make it responsive
		var chartStocksResize = function() {
			chartStocks.configure({
				width: $('.chart-stocks').width()
			});
			chartStocks.render();
		}
		Pleasure.callOnResize.push( chartStocksResize );

	},

	createSalesByYearChart : function () {
		var chartSalesByYear = new Rickshaw.Graph( {
			element: document.querySelector('.chart-sales-by-year'),
			renderer: 'lineplot',
			padding: { top: 0.1 },
			series: [{
				name: 'Sales',
				color: Pleasure.colors.blue,

				data: [
					{ x: 1388527200, y: 4859 },
					{ x: 1391205600, y: 6132 },
					{ x: 1393624800, y: 8650 },
					{ x: 1396303200, y: 14043 },
					{ x: 1398895200, y: 8938 },
					{ x: 1401573600, y: 7354 },
					{ x: 1404165600, y: 2189 },
					{ x: 1406844000, y: 1325 },
					{ x: 1409522400, y: 6321 },
					{ x: 1412114400, y: 9913 },
					{ x: 1414792800, y: 15699 },
					{ x: 1417384800, y: 13332 } ]
			}]
		});

		new Rickshaw.Graph.Axis.Time({
			graph: chartSalesByYear
		});
		new Rickshaw.Graph.HoverDetail({
			graph: chartSalesByYear,
			formatter: function(series, x, y) {
				var content = series.name + ": " + parseInt(y);
				return content;
			}
		});

		chartSalesByYear.render();

		// Make it responsive
		var chartSalesByYearResize = function() {
			chartSalesByYear.configure({
				width: $('.chart-sales-by-year').width()
			});
			chartSalesByYear.render();
		}
		Pleasure.callOnResize.push( chartSalesByYearResize );
	},

	createVisitors: function () {
		var opts = {
			lines: 12, // The number of lines to draw
			angle: 0.15, // The length of each line
			lineWidth: 0.44, // The line thickness
			pointer: {
				length: 0.9, // The radius of the inner circle
				strokeWidth: 0.035, // The rotation offset
				color: '#000000' // Fill color
			},
			limitMax: 'false',   // If true, the pointer will not go past the end of the gauge
			percentColors: [[0.0, "#a9d70b" ], [0.50, "#f9c802"], [1.0, "#ff0000"]],
			strokeColor: '#E0E0E0',   // to see which ones work best for you
			generateGradient: true
		};

		var target = document.getElementById('gauge'); // your canvas element
		var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
		gauge.setTextField(document.getElementById("preview-textfield"));
		gauge.maxValue = 600; // set max gauge value
		gauge.animationSpeed = 20; // set animation speed (32 is default value)
		gauge.set(400); // set actual value

		var randomVisitors = function () {
			var rand = Math.round( Math.random() * (8000-500)) + 500;
			setTimeout(function () {
				changeVisitors()
				randomVisitors();
			}, rand);
		}
		randomVisitors();

		function changeVisitors () {
			var randomData = Math.floor(Math.random() * 500) + 100;
			gauge.set(randomData);
		}
	},

	fakePageResponsive: function () {
		$(document).on('click', '.fake-page', function () {
			$(window).trigger('resize');
		});
	},

	init: function () {
		//this.createNotification();
		//this.createMap();
		//this.createSalesChart();
		//this.createBalanceChart();
		//this.createFollowersChart();
		//this.createStocksChart();
		//this.createSalesByYearChart();
		//this.createVisitors();

		//this.fakePageResponsive();
	}
}




