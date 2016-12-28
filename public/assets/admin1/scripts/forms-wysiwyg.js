var FormsWysiwyg = {

	summernoteDefault: function () {
		$('.summernote-default').summernote();
	},

	summernoteClickToEdit: function () {
		$('#summernote-edit').click(function () {
			$('.summernote-click2edit').summernote({focus: true});
		});
		$('#summernote-save').click(function () {
			var aHTML = $('.summernote-click2edit').code(); //save HTML If you need(aHTML: array).
			$('.summernote-click2edit').destroy();
		});
	},

	summernoteAirMode: function () {
		$('.summernote-airmode').summernote({
			airMode: true
		});
	},

	summernoteRtl: function () {
		$('.summernote-rtl').summernote({
			direction: 'rtl'
		});
	},

	summernoteModal: function () {
		$('#bs-summernote-modal').on('shown.bs.modal', function() {
			$('.summernote-modal').summernote({ height: 300, focus: true });
		}).on('hidden.bs.modal', function () {
			$('.summernote-modal').destroy();
		});
	},

	wysihtml5Editor: function () {
		$('.bs-wysihtml5').wysihtml5({
			'stylesheets': ['../../assets/globals/plugins/bootstrap3-wysihtml5-bower/dist/editor.css'],
			'font-styles': true, //Font styling, e.g. h1, h2, etc. Default true
			'emphasis': true, //Italics, bold, etc. Default true
			'lists': true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
			'html': true, //Button which allows you to edit the generated HTML. Default false
			'link': true, //Button to insert a link. Default true
			'image': true, //Button to insert an image. Default true,
			'color': true, //Button to change color of font
			'blockquote': true, //Blockquote
			'size': 'sm' //default: none, other options are xs, sm, lg
		});
	},

	mediumEditor: function () {
		var medium_basic = new MediumEditor('.medium-editor-basic');
	},

	init: function () {
		this.summernoteDefault();
		this.summernoteClickToEdit();
		this.summernoteAirMode();
		this.summernoteRtl();
		this.summernoteModal();

		this.wysihtml5Editor();

		this.mediumEditor();
	}
}




