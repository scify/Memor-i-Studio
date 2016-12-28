var SweetAlert = {

	basic: function () {
		$('.swal-basic').click(function () {
			swal('Here\'s a message!');
		});
	},

	withTitle: function () {
		$('.swal-with-text').click(function () {
			swal('Here\'s a message!', 'It\'s pretty, isn\'t it?');
		});
	},

	successMessage: function () {
		$('.swal-success').click(function () {
			swal('Good job!', 'You clicked the button!', 'success');
		});
	},

	warningMessage: function () {
		$('.swal-warning-confirmation').click(function () {
			swal({
				title: 'Are you sure?',
				text: 'Your will not be able to recover this imaginary file!',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, delete it!',
				closeOnConfirm: false
			}, function(){
				swal('Deleted!', 'Your imaginary file has been deleted.', 'success');
			});
		});
	},

	warningCancelAndConfirmation: function () {
		$('.swal-warning-functions').click(function () {
			swal({
				title: 'Are you sure?',
				text: 'Your will not be able to recover this imaginary file!',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, delete it!',
				cancelButtonText: 'No, cancel plx!',
				closeOnConfirm: false,
				closeOnCancel: false
			}, function(isConfirm){
				if (isConfirm) {
					swal('Deleted!', 'Your imaginary file has been deleted.', 'success');
				} else {
					swal('Cancelled', 'Your imaginary file is safe :)', 'error');
				}
			});
		});
	},

	withCustomIcon: function () {
		$('.swal-custom-icon').click(function () {
			swal({
				title: 'Sweet!',
				text: 'Here\'s a custom image.',
				imageUrl: Pleasure.settings.paths.plugins+'sweetalert/thumbs-up.jpg'
			});
		});
	},

	init: function () {
		this.basic();
		this.withTitle();
		this.successMessage();
		this.warningMessage();
		this.warningCancelAndConfirmation();
		this.withCustomIcon();
	}
}




