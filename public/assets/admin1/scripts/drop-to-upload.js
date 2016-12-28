var dropToUpload = {

	create: function () {
		Dropzone.autoDiscover = false;

		$('.dropzone').dropzone({
			url: 'demo/dropzone/upload.php',
			success: function(file) {
				console.log(file);
				if (file.previewElement) {
					//
						$('.dropzone-table').find('tbody').append(
						'<tr>'+
						'<td>'+file.name+'</td>'+
						'<td>'+file.type+'</td>'+
						'<td>'+file.size+'</td>'+
						'<td><button class="btn btn-danger btn-sm remove-dropzone-item">remove</button></td>'+
						'</tr>');
					return file.previewElement.classList.add("dz-success");
				}
			}
		});

		$('.remove-dropzone-item').on('click', function () {
			$(this).parents('tr').remove();
		});
	},

	init: function () {
		this.create();
	}
}




