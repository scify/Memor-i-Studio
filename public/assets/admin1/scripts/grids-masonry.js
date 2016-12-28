var GridsMasonry = {

	create: function () {
		var $masonryContainer = $('.masonry');

		setTimeout( function () {
			$masonryContainer.masonry({
				/* 'isOriginLeft': false // RTL support */
			});
		}, 300);

	},

	init: function () {
		this.create();
		Pleasure.callOnResize.push( this.create );
	}
}

