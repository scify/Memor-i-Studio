var TablesDataTablesExtensions = {

	exportButtons: function () {
		var table = $('.datatables-tabletools-basic').DataTable({
			dom: 'T<"clearfix">lfrtip',
			'tableTools': {
				'sSwfPath': '../../assets/globals/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'
			}
		});
	},

	scroller: function () {
		var table = $('.datatables-scroller-basic').DataTable({
			dom: 'frtiS',
			scrollY: 200,
			scrollCollapse: true
		});
	},

	responsiveColumns: function () {
		var table = $('.datatables-responsive-column').DataTable( {
			responsive: {
						details: {
								type: 'column'
						}
				},
				columnDefs: [ {
						className: 'control',
						orderable: false,
						targets:   0
				} ],
				order: [ 1, 'asc' ]
		});
	},

	showHideColumns: function () {
		var colvis_table = $('.datatables-colvis-basic').DataTable();
		var colvis = new $.fn.dataTable.ColVis( colvis_table );
		$('.colvis-button-container').html(colvis.button());
	},

	columnReOrder: function () {
		$('.datatables-colreorder').DataTable({
			dom: 'Rlfrtip'
		});
	},

	init: function () {
		this.exportButtons();
		this.scroller();
		this.responsiveColumns();
		this.showHideColumns();
		this.columnReOrder();


		$.extend( $.fn.dataTable.defaults, {
			fnDrawCallback: function( oSettings ) {
				$('.dataTables_wrapper select, .dataTables_wrapper input').removeClass('input-sm');
			}
		});
		$.extend(true, $.fn.DataTable.TableTools.classes, {
			'container': 'btn-group tabletools-btn-group margin-bottom-10 pull-right',
			'buttons': {
				'normal': 'btn btn-sm btn-default',
				'disabled': 'btn btn-sm btn-default disabled'
			}
		});
	}
}

