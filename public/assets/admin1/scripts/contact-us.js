var ContactUs = {

	prepareMap: function () {
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
		this.prepareMap();
	}
}


