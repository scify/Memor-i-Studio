var MapsGoogle = {

	basic: function () {
		var map = new GMaps({
			el: '#gmaps_1',
			lat: 40.712675,
			lng: -74.006277,
			zoom: 10,
			zoomControl : true,
			zoomControlOpt: {
				style : 'SMALL',
				position: 'TOP_LEFT'
			},
			panControl : false,
			streetViewControl : false,
			mapTypeControl: false,
			overviewMapControl: false
		});
	},

	contextual: function () {
		var map = new GMaps({
			el: '#gmaps_2',
			lat: 48.856614,
			lng: 2.352222
		});
		map.setContextMenu({
			control: 'map',
			options: [{
			title: 'Add marker',
			name: 'add_marker',
			action: function(e){
				console.log(e.latLng.lat());
				console.log(e.latLng.lng());
				this.addMarker({
					lat: e.latLng.lat(),
					lng: e.latLng.lng(),
					title: 'New marker'
				});
				this.hideContextMenu();
			}
			}, {
				title: 'Center here',
				name: 'center_here',
				action: function(e){
					this.setCenter(e.latLng.lat(), e.latLng.lng());
				}
			}]
		});
		map.setContextMenu({
			control: 'marker',
			options: [{
				title: 'Center here',
				name: 'center_here',
				action: function(e){
					this.setCenter(e.latLng.lat(), e.latLng.lng());
				}
			}]
		});
	},

	geocoding: function () {
		var map = new GMaps({
			el: '#gmaps_3',
			lat: 37.774929,
			lng: -122.419416,
			zoom: 11
		});
		$('#geocoding_form').submit(function(e){
			e.preventDefault();
			GMaps.geocode({
				address: $('#address').val().trim(),
				callback: function(results, status){
					if(status==='OK'){
						var latlng = results[0].geometry.location;
						map.setCenter(latlng.lat(), latlng.lng());
						map.addMarker({
							lat: latlng.lat(),
							lng: latlng.lng()
						});
					}
				}
			});
		});
	},

	geolocation: function () {
		var map = new GMaps({
			el: '#gmaps_4',
			lat: 40.712675,
			lng: -74.006277
		});

		GMaps.geolocate({
			success: function(position){
				map.setCenter(position.coords.latitude, position.coords.longitude);
			},
			error: function(error){
				alert('Geolocation failed: '+error.message);
			},
			not_supported: function(){
				alert("Your browser does not support geolocation");
			},
			always: function(){
				//alert("Done!");
			}
		});
	},

	cloudLayer: function () {
		var map = new GMaps({
			el: "#gmaps_7",
			lat: 52.520007,
			lng: 13.404954,
			zoom: 3
		});

		map.addLayer('weather', {
			clickable: false
		});
	},

	trafficLayer: function () {
		var map = new GMaps({
			el: "#gmaps_8",
			lat: 35.689487,
			lng: 139.691706,
			zoom: 10
		});

		map.addLayer('traffic', {
			clickable: false
		});
	},

	events: function () {
		var map = new GMaps({
			el: '#gmaps_9',
			zoom: 11,
			lat: 51.507351,
			lng: -0.127758,
			click: function(e){
				alert('click');
			},
			dragend: function(e){
				alert('dragend');
			}
		});
	},

	styled: function () {
		var map = new GMaps({
			el: "#gmaps_10",
			lat: 41.895465,
			lng: 12.482324,
			zoom: 5,
			zoomControl : true,
			zoomControlOpt: {
				style : "SMALL",
				position: "TOP_LEFT"
			},
			panControl : true,
			streetViewControl : false,
			mapTypeControl: false,
			overviewMapControl: false
		});

		var styles = [
				{
					stylers: [
						{ hue: "#00ffe6" },
						{ saturation: -20 }
					]
				}, {
						featureType: "road",
						elementType: "geometry",
						stylers: [
								{ lightness: 100 },
								{ visibility: "simplified" }
					]
				}, {
						featureType: "road",
						elementType: "labels",
						stylers: [
								{ visibility: "off" }
					]
				}
		];

		map.addStyle({
				styledMapName:"Styled Map",
				styles: styles,
				mapTypeId: "map_style"
		});

		map.setStyle("map_style");
	},

	init: function () {
		this.basic();
		this.contextual();
		this.geocoding();
		this.geolocation();
		this.cloudLayer();
		this.trafficLayer();
		this.events();
		this.styled();
	}
}




