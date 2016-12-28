var Invoice = {

	createBarcode: function () {
		var date = new Date();
		$('#barcode').JsBarcode('Barcode code is here', { width: 1, height: 40});
	},

	print: function () {
		window.print();
	},

	init: function () {
		this.createBarcode();
		$('.print-trigger').click(function () {
			Invoice.print();
		});
	}
}




