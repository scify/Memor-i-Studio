var ExtendedModals = {

	ajaxModal: function () {
		var $modal = $('#ajax-modal');

		$('.bs-extended-ajax').on('click', function(){
			// create the backdrop and wait for next modal to be triggered
			$('body').modalmanager('loading');

			setTimeout(function(){
				 $modal.load('demo/extended-modals-ajax.html', '', function(){
					$modal.modal();
				});
			}, 1000);
		});

		$modal.on('click', '.update', function(){
			$modal.modal('loading');
			setTimeout(function(){
				$modal
					.modal('loading')
					.find('.modal-body')
						.prepend('<div class="alert alert-info fade in">' +
							'Updated!<button type="button" class="close" data-dismiss="alert">&times;</button>' +
						'</div>');
			}, 1000);
		});
	},

	init: function () {
		this.ajaxModal();
	}
}


