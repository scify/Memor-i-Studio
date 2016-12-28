var ChartsC3 = {

	simpleXYLineChart: function () {
		var chart = c3.generate({
			bindto: '.c3-line',
			data: {
				x: 'x',
				columns: [
					['x', 30, 50, 100, 230, 300, 310],
					['data1', 30, 200, 100, 400, 150, 250],
					['data2', 130, 300, 200, 300, 250, 450]
				]
			}
		});
	},

	barChart: function () {
		var chart = c3.generate({
			bindto: '.c3-bar',
			data: {
				columns: [
					['data1', 30, 200, 100, 400, 150, 250],
					['data2', 130, 100, 140, 200, 150, 50]
				],
				type: 'bar'
			},
			bar: {
				width: {
					ratio: 0.5 // this makes bar width 50% of length between ticks
				}
				// or
				//width: 100 // this makes bar width 100px
			}
		});
	},

	pieChart: function () {
		var chart = c3.generate({
			bindto: '.c3-pie',
			data: {
				// iris data from R
				columns: [
					['data1', 30],
					['data2', 120],
				],
				type : 'pie',
				onclick: function (d, i) { console.log("onclick", d, i); },
				onmouseover: function (d, i) { console.log("onmouseover", d, i); },
				onmouseout: function (d, i) { console.log("onmouseout", d, i); }
			}
		});
	},

	donutChart: function () {
		var chart = c3.generate({
			bindto: '.c3-donut',
			data: {
				columns: [
					['data1', 30],
					['data2', 120],
				],
				type : 'donut',
				onclick: function (d, i) { console.log("onclick", d, i); },
				onmouseover: function (d, i) { console.log("onmouseover", d, i); },
				onmouseout: function (d, i) { console.log("onmouseout", d, i); }
			},
			donut: {
				title: "Iris Petal Width"
			}
		});
	},

	gaugeChart: function () {
		var chart = c3.generate({
			bindto: '.c3-gauge',
			data: {
				columns: [
					['data', 85.4]
				],
				type: 'gauge',
				onclick: function (d, i) { console.log("onclick", d, i); },
				onmouseover: function (d, i) { console.log("onmouseover", d, i); },
				onmouseout: function (d, i) { console.log("onmouseout", d, i); }
			},
			color: {
				pattern: ['#FF0000', '#F97600', '#F6C600', '#60B044'], // the three color levels for the percentage values.
				threshold: {
					values: [30, 60, 90, 100]
				}
			}
		});
	},

	splineChart: function () {
		var chart = c3.generate({
			bindto: '.c3-spline',
			data: {
				columns: [
					['data1', 30, 200, 100, 400, 150, 250],
					['data2', 130, 100, 140, 200, 150, 50]
				],
				type: 'spline'
			}
		});
	},

	areaChart: function () {
		var chart = c3.generate({
			bindto: '.c3-area-chart',
			data: {
				columns: [
					['data1', 300, 350, 300, 0, 0, 0],
					['data2', 130, 100, 140, 200, 150, 50]
				],
				types: {
					data1: 'area',
					data2: 'area-spline'
				}
			}
		});
	},

	combinationChart: function () {
		var chart = c3.generate({
			bindto: '.c3-combination',
			data: {
				columns: [
					['data1', 30, 20, 50, 40, 60, 50],
					['data2', 200, 130, 90, 240, 130, 220],
					['data3', 300, 200, 160, 400, 250, 250],
					['data4', 200, 130, 90, 240, 130, 220],
					['data5', 130, 120, 150, 140, 160, 150],
					['data6', 90, 70, 20, 50, 60, 120],
				],
				type: 'bar',
				types: {
					data3: 'spline',
					data4: 'line',
					data6: 'area',
				},
				groups: [
					['data1','data2']
				]
			}
		});
	},

	init: function () {
		this.simpleXYLineChart();
		this.barChart();
		this.pieChart();
		this.donutChart();
		this.gaugeChart();
		this.splineChart();
		this.areaChart();
		this.combinationChart();
	}
}




