var FormsSwitchery = {

	default: function () {
		var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery-default'));
		elems.forEach(function(html) {
			var switchery = new Switchery(html);
		});
	},

	primary: function () {
		var elemsPrimary = Array.prototype.slice.call(document.querySelectorAll('.switchery-primary'));
		elemsPrimary.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.primary });
		});
	},

	info: function () {
		var elemsInfo = Array.prototype.slice.call(document.querySelectorAll('.switchery-info'));
		elemsInfo.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.info });
		});
	},

	success: function () {
		var elemsSuccess = Array.prototype.slice.call(document.querySelectorAll('.switchery-success'));
		elemsSuccess.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.success });
		});
	},

	warning: function () {
		var elemsWarning = Array.prototype.slice.call(document.querySelectorAll('.switchery-warning'));
		elemsWarning.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.warning });
		});
	},

	danger: function () {
		var elemsDanger = Array.prototype.slice.call(document.querySelectorAll('.switchery-danger'));
		elemsDanger.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.danger });
		});
	},

	red: function () {
		var elemsRed = Array.prototype.slice.call(document.querySelectorAll('.switchery-red'));
		elemsRed.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.red });
		});
	},

	pink: function () {
		var elemsPink = Array.prototype.slice.call(document.querySelectorAll('.switchery-pink'));
		elemsPink.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.pink });
		});
	},

	purple: function () {
		var elemsPurple = Array.prototype.slice.call(document.querySelectorAll('.switchery-purple'));
		elemsPurple.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.purple });
		});
	},

	deepPurple: function () {
		var elemsDeepPurple = Array.prototype.slice.call(document.querySelectorAll('.switchery-deep-purple'));
		elemsDeepPurple.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.deep_purple });
		});
	},

	indigo: function () {
		var elemsIndigo = Array.prototype.slice.call(document.querySelectorAll('.switchery-indigo'));
		elemsIndigo.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.indigo });
		});
	},

	blue: function () {
		var elemsBlue = Array.prototype.slice.call(document.querySelectorAll('.switchery-blue'));
		elemsBlue.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.blue });
		});
	},

	lightBlue: function () {
		var elemsLightBlue = Array.prototype.slice.call(document.querySelectorAll('.switchery-light-blue'));
		elemsLightBlue.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.light_blue });
		});
	},

	cyan: function () {
		var elemsCyan = Array.prototype.slice.call(document.querySelectorAll('.switchery-cyan'));
		elemsCyan.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.cyan });
		});
	},

	teal: function () {
		var elemsTeal = Array.prototype.slice.call(document.querySelectorAll('.switchery-teal'));
		elemsTeal.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.teal });
		});
	},

	green: function () {
		var elemsGreen = Array.prototype.slice.call(document.querySelectorAll('.switchery-green'));
		elemsGreen.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.green });
		});
	},

	lightGreen: function () {
		var elemsLightGreen = Array.prototype.slice.call(document.querySelectorAll('.switchery-light-green'));
		elemsLightGreen.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.light_green });
		});
	},

	lime: function () {
		var elemsLime = Array.prototype.slice.call(document.querySelectorAll('.switchery-lime'));
		elemsLime.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.lime });
		});
	},

	yellow: function () {
		var elemsYellow = Array.prototype.slice.call(document.querySelectorAll('.switchery-yellow'));
		elemsYellow.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.yellow });
		});
	},

	amber: function () {
		var elemsAmber = Array.prototype.slice.call(document.querySelectorAll('.switchery-amber'));
		elemsAmber.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.amber });
		});
	},

	orange: function () {
		var elemsOrange = Array.prototype.slice.call(document.querySelectorAll('.switchery-orange'));
		elemsOrange.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.orange });
		});
	},

	deepOrange: function () {
		var elemesDeepOrange = Array.prototype.slice.call(document.querySelectorAll('.switchery-deep-orange'));
		elemesDeepOrange.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.deep_orange });
		});
	},

	brown: function () {
		var elemsBrown = Array.prototype.slice.call(document.querySelectorAll('.switchery-brown'));
		elemsBrown.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.brown });
		});
	},

	grey: function () {
		var elemsGrey = Array.prototype.slice.call(document.querySelectorAll('.switchery-grey'));
		elemsGrey.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.grey });
		});
	},

	blueGrey: function () {
		var elemsBlueGrey = Array.prototype.slice.call(document.querySelectorAll('.switchery-blue-grey'));
		elemsBlueGrey.forEach(function(html) {
			var switchery = new Switchery(html, { color: Pleasure.colors.blue_grey });
		});
	},

	init: function () {
		this.default();

		this.primary();
		this.info();
		this.success();
		this.warning();
		this.danger();

		this.red();
		this.pink();
		this.purple();
		this.deepPurple();
		this.indigo();
		this.blue();
		this.lightBlue();
		this.cyan();
		this.teal();
		this.green();
		this.lightGreen();
		this.lime();
		this.yellow();
		this.amber();
		this.orange();
		this.deepOrange();
		this.brown();
		this.grey();
		this.blueGrey();

	}
}

