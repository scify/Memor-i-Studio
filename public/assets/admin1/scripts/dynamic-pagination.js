var DynamicPagination = {

	basic: function () {
		$('.demo1').bootpag({
			total: 5
		}).on("page", function(event, num){
		$('.content1').html('Page ' + num); // or some ajax content loading...
			// ... after content load -> change total to 10
			$(this).bootpag({total: 10, maxVisible: 10});
		});
	},

	advanced: function () {
		$('.demo2').bootpag({
			total: 23,
			page: 1,
			maxVisible: 10
		}).on('page', function(event, num){
			$('.content2').html('Page ' + num); // or some ajax content loading...
		});
	},

	init: function () {
		this.basic();
		this.advanced();
	}
}




