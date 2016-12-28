var JHtmlPlayer = {

	fixedPlayer: function () {
		$("#jplayer_1").jPlayer({
			ready: function (event) {
				$(this).jPlayer("setMedia", {
					title: "Paft Dunk - Let Gucky",
					m4a: "http://jplayer.org/audio/m4a/Miaow-07-Bubble.m4a",
					oga: "http://jplayer.org/audio/ogg/Miaow-07-Bubble.ogg"
				});
			},
			swfPath: "../../assets/globals/plugins/jplayer/jplayer",
			supplied: "m4a, oga",
			wmode: "window",
			useStateClassSkin: true,
			autoBlur: false,
			smoothPlayBar: true,
			keyEnabled: true,
			remainingDuration: true,
			toggleDuration: true
		});
	},

	init: function () {
		this.fixedPlayer();
	}
}


