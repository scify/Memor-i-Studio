var ErrorPages = {

	outsideVideo: function () {
		var BV = new $.BigVideo();
		BV.init();
		if (Modernizr.touch) {
			BV.show('../../assets/globals/img/picjumbo/large/1.jpg');
		} else {
			BV.show([
				{ type: 'video/mp4',  src: 'http://vjs.zencdn.net/v/oceans.mp4' },
				{ type: 'video/webm', src: 'http://vjs.zencdn.net/v/oceans.webm' }
			], { ambient: true, doLoop: true });
		}
	}

}


