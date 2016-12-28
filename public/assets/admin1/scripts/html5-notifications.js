var Html5Notifications = {

	requestPermission: function () {
		notify.requestPermission(function() {
			permissionLevel = permissionLevels[notify.permissionLevel()];
			console.log( Html5Notifications.getPermissionLevel() );
		});
	},

	getPermissionLevel: function () {
		if (permissionLevel === 0) {
				return "allowed"
		} else if (permissionLevel === 1) {
				return "default"
		} else {
				return "denied"
		}
	},

	createNotification: function (dataTitle, dataBody, dataIcon, dataHref) {

		if(permissionLevel === 0) {

			varTitle = (!dataTitle) ? 'TeamFox' : dataTitle;
			varBody = (!dataTitle) ? 'Sample body text' : dataTitle;
			varIcon = (!dataTitle) ? Pleasure.settings.paths.images+'/icons/favicon.ico' : dataTitle;
			varHref = (!dataHref) ? '#' : dataHref;


			notify.createNotification(
				varTitle,
				{
					body: dataBody,
					icon: dataIcon,
					onclick: dataHref
				}
			)
		} else {
			Html5Notifications.requestPermission();
		}

	},

	listenButtonEvents: function () {
		$('.html5-request-permission').on('click', function () {
			Html5Notifications.requestPermission();
		});

		$('.html5-create-notification').on('click', function () {
			var dataTitle = $(this).data('notification-title'),
					dataBody = $(this).data('notification-body'),
					dataIcon = $(this).data('notification-icon'),
					dataHref = $(this).data('notification-href');

			Html5Notifications.createNotification(dataTitle, dataBody, dataIcon, dataHref);
		});
	},

	init: function () {

		permissionLevels = {};
		isSupported = '';
		permissionLevel = '';

		permissionLevels[notify.PERMISSION_GRANTED] = 0;
		permissionLevels[notify.PERMISSION_DEFAULT] = 1;
		permissionLevels[notify.PERMISSION_DENIED] = 2;

		isSupported = notify.isSupported;
		permissionLevel = permissionLevels[notify.permissionLevel()];

		this.listenButtonEvents();
	}
}




