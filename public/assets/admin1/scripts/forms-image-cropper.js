var ImageCropper = {

	create: function () {
		var $cropper = $(".cropper"),
				$dataX = $("#dataX"),
				$dataY = $("#dataY"),
				$dataH = $("#dataH"),
				$dataW = $("#dataW"),
				cropper;

		$cropper.cropper({
			//aspectRatio: 16 / 9,
			data: {
				x: 250,
				y: 50,
				width: 340,
				height: 180
			},
			preview: ".preview",

			// autoCrop: false,
			// dragCrop: false,
			// modal: false,
			// moveable: false,
			// resizeable: false,

			// maxWidth: 480,
			// maxHeight: 270,
			// minWidth: 160,
			// minHeight: 90,

			done: function(data) {
				$dataX.val(data.x);
				$dataY.val(data.y);
				$dataH.val(data.height);
				$dataW.val(data.width);
			},
			build: function(e) {
				console.log(e.type);
			},
			built: function(e) {
				console.log(e.type);
			},
			dragstart: function(e) {
				console.log(e.type);
			},
			dragmove: function(e) {
				console.log(e.type);
			},
			dragend: function(e) {
				console.log(e.type);
			}
		});

		cropper = $cropper.data("cropper");

		$cropper.on({
			"build.cropper": function(e) {
				console.log(e.type);
				// e.preventDefault();
			},
			"built.cropper": function(e) {
				console.log(e.type);
				// e.preventDefault();
			},
			"dragstart.cropper": function(e) {
				console.log(e.type);
				// e.preventDefault();
			},
			"dragmove.cropper": function(e) {
				console.log(e.type);
				// e.preventDefault();
			},
			"dragend.cropper": function(e) {
				console.log(e.type);
				// e.preventDefault();
			}
		});

		$("#enable").click(function() {
			$cropper.cropper("enable");
		});

		$("#disable").click(function() {
			$cropper.cropper("disable");
		});

		$("#setAspectRatio").click(function() {
			$cropper.cropper("setAspectRatio", $("#aspectRatio").val());
		});

		$(".changeImage").click(function() {
			var data_img_source = $(this).attr('data-img-source');
			$cropper.cropper('replace', Pleasure.settings.paths.images+'/picjumbo/'+data_img_source+'.jpg');
			$('#sourcePath').val( Pleasure.settings.paths.images+'/picjumbo/'+data_img_source+'.jpg');
			$('#dataS').val(data_img_source);

			console.log(data_img_source);
		});

		$("#getData").click(function() {
			$("#showData").val(JSON.stringify($cropper.cropper("getData")));
		});
	},

	modalWindow: function () {
		var $modal = $("#bs-modal"),
				$image = $modal.find(".bs-modal-cropper img"),
				originalData = {};

		$modal.on("shown.bs.modal", function() {
			$image.cropper({
				multiple: true,
				data: originalData,
				done: function(data) {
					console.log(data);
				}
			});
		}).on("hidden.bs.modal", function() {
			originalData = $image.cropper("getData"); // Save the data on hide
			$image.cropper("destroy");
		});
	},

	init: function () {
		this.create();
		this.modalWindow();
	}
}




