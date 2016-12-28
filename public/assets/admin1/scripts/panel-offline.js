var PanelOffline = {

	checkStatus: function () {
		Offline.options = {
			checkOnLoad: true, // Should we check the connection status immediatly on page load.
			reconnect: {
				initialDelay: 3, // How many seconds should we wait before rechecking.
				delay: 10 // How long should we wait between retries.
			}
		}

		setInterval(function () {
			Offline.check(); // Check the current status of the connection.
		}, 3000);

		// A connection test has failed, fired even if the connection was already down
		Offline.on('down', function () {
			toastr.error('A connection test has failed, fired even if the connection was already down');
		});

		// A connection test has succeeded, fired even if the connection was already up
		Offline.on('up', function () {
			toastr.success('A connection test has succeeded, fired even if the connection was already up');
		});
	},

	init: function () {
		this.checkStatus();
	}
}

