var UserPages = {

	lockScreen: function () {
		$('.bg-blur').addClass('active');
		$('#user-password').focus();
	},

	login: function () {
		$('.bg-blur').addClass('active');

		$('.show-pane-forgot-password').click(function() {
			$('.panel-body').hide();
			$('#pane-forgot-password').fadeIn(1000);
			$('.login-screen').addClass('forgot-password');
		});
		$('.show-pane-create-account').click(function() {
			$('.panel-body').hide();
			$('#pane-create-account').fadeIn(1000);
			$('.login-screen').addClass('create-account');
		});
		$('.show-pane-login').click(function() {
			$('.panel-body').hide();
			$('#pane-login').fadeIn(1000);
			$('.login-screen').removeClass('forgot-password create-account');
		});
	},

	profile: function () {
		var $masonryContainer = $('.masonry');

		setTimeout( function () {
			$masonryContainer.masonry({
				/* 'isOriginLeft': false // RTL support */
			});
		}, 500);

		$('.toggle-sidebar-menu').click(function () {
			setTimeout( function () {
				$masonryContainer.masonry();
			}, 100);
		});

		var gallery = $('#blueimp-gallery').data('gallery');
	}

}


