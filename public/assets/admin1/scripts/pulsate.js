var Pulsate = {

	repeatPulsate: function () {
		$('.pulsate-repeate').pulsate({
			color: Pleasure.colors.primary, // set the color of the pulse
			reach: 20, // how far the pulse goes in px
			speed: 500, // how long one pulse takes in ms
			pause: 0, // how long the pause between pulses is in ms
			glow: false, // if the glow should be shown too
			repeat: true, // will repeat forever if true, if given a number will repeat for that many times
			onHover: false // if true only pulsate if user hovers over the element
		});
	},

	pulsateOneTime: function () {
		$('.pulsate-one-time').click(function() {
			$(this).pulsate({
				color: Pleasure.colors.primary, // set the color of the pulse
				reach: 20, // how far the pulse goes in px
				speed: 500, // how long one pulse takes in ms
				pause: 0, // how long the pause between pulses is in ms
				glow: false, // if the glow should be shown too
				repeat: false, // will repeat forever if true, if given a number will repeat for that many times
				onHover: false // if true only pulsate if user hovers over the element
			});
		});
	},

	init: function () {
		this.repeatPulsate();
		this.pulsateOneTime();
	}
}




