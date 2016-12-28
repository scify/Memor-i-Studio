var TreeView = {

	sampleTreeData: function () {
		var data = [{
			"text": "All Files",
			"state": {"opened": true},
			"children": [
				{
					"text": "Wordpress",
					"icon" : "ion-social-wordpress",
					"state": {"opened": true},
					"children": ["Portfolio","Education","Blog"]
				},
				{
				"text": "Site Templates",
				"icon": "ion-monitor",
				"state": { "opened": true },
				"children": ["Creative","Admin Templates"]
				},
				{
					"text": "Plugins (disabled)",
					"state": {"disabled": true}
				}
			]
		}]
		return data;
	},

	simple: function () {
		$('.treejs-simple').jstree();
	},

	contextTree: function () {
		$('.treejs-context').jstree({
			"plugins": ["contextmenu"],
			"core": {
				"themes" :{"responsive": false },
				"check_callback": true,
				"data": this.sampleTreeData()
			}
		});
	},

	dragDropTree: function () {
		$('.treejs-dragdrop').jstree({
			"plugins": ["dnd"],
			"core": {
				"themes" :{"responsive": false },
				"check_callback": true,
				"data": this.sampleTreeData()
			}
		});
	},

	checkable: function () {
		$('.treejs-checkable').jstree({
			"plugins": ["checkbox"],
			"core": {
				"themes" :{"responsive": false },
				"check_callback": true,
				"data": this.sampleTreeData()
			}
		});
	},

	sortable: function () {
		$('.treejs-sortable').jstree({
			"plugins": ["sort"],
			"core": {
				"themes" :{"responsive": false },
				"check_callback": true,
				"data": this.sampleTreeData()
			}
		});
	},

	ajaxTree: function () {
		$('.treejs-ajax').jstree({
			"plugins": ["wholerow","state"],
			"state": { "key": "demo3" },
			"core": {
				"themes" :{"responsive": false },
				"check_callback": true,
				"data": {
					"url": function(node) {
						return "demo/ajaxtree.json";
					},
					"data": function(node) {
						return { "parent": node.id };
					}
				}
			}
		});
	},

	init: function () {
		this.simple();
		this.contextTree();
		this.dragDropTree();
		this.checkable();
		this.sortable();
		this.ajaxTree();

		$('.treejs').on('click','li.jstree-node a',function(){
			if( $(this).attr('target') == '_blank' ){
				window.open(this,'_blank');
			} else {
				document.location.href = this;
			}
		});
	}
}




