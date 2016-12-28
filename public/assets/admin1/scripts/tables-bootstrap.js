var TablesBootstrap = {

	priceSorter: function (a, b) {
		a = +a.substring(1);
		b = +b.substring(1);
		if (a > b) return 1;
		if (a < b) return -1;
		return 0;
	},

	nameFormatter: function (value, row) {
		var icon = row.id % 2 === 0 ? 'glyphicon-star' : 'glyphicon-star-empty'
		return '<i class="glyphicon ' + icon + '"></i> ' + value;
	},

	priceFormatter: function (value) {
		// 16777215 == ffffff in decimal
		var color = '#'+Math.floor(Math.random() * 6777215).toString(16);
		return '<div  style="color: ' + color + '">' +
		'<i class="glyphicon glyphicon-usd"></i>' +
		value.substring(1) +
		'</div>';
	},

	operateFormatter: function (value, row, index) {
		return [
		'<a class="like" href="javascript:void(0)" title="Like">',
		'<i class="glyphicon glyphicon-heart"></i>',
		'</a>',
		'<a class="edit ml10" href="javascript:void(0)" title="Edit">',
		'<i class="glyphicon glyphicon-edit"></i>',
		'</a>',
		'<a class="remove ml10" href="javascript:void(0)" title="Remove">',
		'<i class="glyphicon glyphicon-remove"></i>',
		'</a>'
		].join('');
	},

	events: function () {
		var $result = $('#events-result');
		$('.bs-table-events').bootstrapTable({
				/*
				onAll: function (name, args) {
						console.log('Event: onAll, data: ', args);
				}
				onClickRow: function (row) {
						$result.text('Event: onClickRow, data: ' + JSON.stringify(row));
				},
				onDblClickRow: function (row) {
						$result.text('Event: onDblClickRow, data: ' + JSON.stringify(row));
				},
				onSort: function (name, order) {
						$result.text('Event: onSort, data: ' + name + ', ' + order);
				},
				onCheck: function (row) {
						$result.text('Event: onCheck, data: ' + JSON.stringify(row));
				},
				onUncheck: function (row) {
						$result.text('Event: onUncheck, data: ' + JSON.stringify(row));
				},
				onCheckAll: function () {
						$result.text('Event: onCheckAll');
				},
				onUncheckAll: function () {
						$result.text('Event: onUncheckAll');
				}
				*/
		}).on('all.bs.table', function (e, name, args) {
				console.log('Event:', name, ', data:', args);
		}).on('click-row.bs.table', function (e, row, $element) {
				$result.text('Event: click-row.bs.table, data: ' + JSON.stringify(row));
		}).on('dbl-click-row.bs.table', function (e, row, $element) {
				$result.text('Event: dbl-click-row.bs.table, data: ' + JSON.stringify(row));
		}).on('sort.bs.table', function (e, name, order) {
				$result.text('Event: sort.bs.table, data: ' + name + ', ' + order);
		}).on('check.bs.table', function (e, row) {
				$result.text('Event: check.bs.table, data: ' + JSON.stringify(row));
		}).on('uncheck.bs.table', function (e, row) {
				$result.text('Event: uncheck.bs.table, data: ' + JSON.stringify(row));
		}).on('check-all.bs.table', function (e) {
				$result.text('Event: check-all.bs.table');
		}).on('uncheck-all.bs.table', function (e) {
				$result.text('Event: uncheck-all.bs.table');
		});
	},

	viaJs: function () {
		window.operateEvents = {
			'click .like': function (e, value, row, index) {
				alert('You click like icon, row: ' + JSON.stringify(row));
				console.log(value, row, index);
			},
			'click .edit': function (e, value, row, index) {
				alert('You click edit icon, row: ' + JSON.stringify(row));
				console.log(value, row, index);
			},
			'click .remove': function (e, value, row, index) {
				alert('You click remove icon, row: ' + JSON.stringify(row));
				console.log(value, row, index);
			}
		};

		$('.bs-table-viajs').bootstrapTable({
			cardView: false,
			method: 'get',
			url: 'demo/bootstrap-table/data2.json',
			cache: false,
			height: 400,
			striped: true,
			pagination: true,
			pageSize: 50,
			pageList: [10, 25, 50, 100, 200],
			search: true,
			showColumns: true,
			showRefresh: true,
			minimumCountColumns: 2,
			clickToSelect: true,
			columns: [{
				field: 'state',
				checkbox: true
			}, {
			field: 'id',
			title: 'Item ID',
			align: 'right',
			valign: 'bottom',
			sortable: true
			}, {
				field: 'name',
				title: 'Item Name',
				align: 'center',
				valign: 'middle',
				sortable: true,
				formatter: TablesBootstrap.nameFormatter
			}, {
				field: 'price',
				title: 'Item Price',
				align: 'left',
				valign: 'top',
				sortable: true,
				formatter: TablesBootstrap.priceFormatter,
				sorter: TablesBootstrap.priceSorter
			}, {
				field: 'operate',
				title: 'Item Operate',
				align: 'center',
				valign: 'middle',
				clickToSelect: false,
				formatter: TablesBootstrap.operateFormatter,
				events: operateEvents
			}]
		});
	},

	init: function () {
		this.events();
		this.viaJs();
	}
}

