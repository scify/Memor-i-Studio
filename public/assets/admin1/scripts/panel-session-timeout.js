var PanelSessionTimeout = {

	checkSessionTimeout: function () {
		$.sessionTimeout({
			keepAlive: true, // If true, the plugin keeps pinging the keepAliveUrl for as long as the user is active. The time between two pings is set by the keepAliveInterval option. If you have no server-side session timeout to worry about, feel free to set this one to false to prevent unnecessary network activity.
			keepAliveUrl: 'demo/keep-alive.html', // URL to ping via AJAX POST to keep the session alive. This resource should do something innocuous that would keep the session alive, which will depend on your server-side platform.

			message: 'Your session will be locked in one minute.',
			logoutUrl: 'user-login.html',
			redirUrl: 'user-lock-screen.html',
			warnAfter: 3000, // 3 seconds later modal will shown
			redirAfter: 6000 // 6 seconds later redirect user-locked page
		});
	},

	init: function () {
		this.checkSessionTimeout();
	}
}

