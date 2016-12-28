var ChartsChartJs = {

	lineChart: function () {
		var ctx = $('.chartjs-line').get(0).getContext("2d");
		var myLineChart = new Chart(ctx).Line( ChartsChartJs.sampleData() );
	},

	barChart: function () {
		var ctxBar = $('.chartjs-bar').get(0).getContext("2d");
		var myBarChart = new Chart(ctxBar).Bar( ChartsChartJs.sampleData() );
	},

	radarChart: function () {
		var ctxRadar = $('.chartjs-radar').get(0).getContext("2d");
		var myRadarChart = new Chart(ctxRadar).Radar( ChartsChartJs.radarData() );
	},

	polarAreaChart: function () {
		var ctxPolar = $('.chartjs-polar').get(0).getContext("2d");
		var myPolarChart = new Chart(ctxPolar).PolarArea( ChartsChartJs.polarData() );
	},

	pieChart: function () {
		var ctxPie = $('.chartjs-pie').get(0).getContext("2d");
		var myPieChart = new Chart(ctxPie).Pie( ChartsChartJs.pieData() );
	},

	doughnutChart: function () {
		var ctxDoughnut = $('.chartjs-doughnut').get(0).getContext("2d");
		var myDoughnutChart = new Chart(ctxDoughnut).Doughnut( ChartsChartJs.pieData() );
	},

	sampleData: function () {
		var data = {
			labels: ["January", "February", "March", "April", "May", "June", "July"],
			datasets: [
				{
					label: "My First dataset",
					fillColor: "rgba(220,220,220,0.2)",
					strokeColor: "rgba(220,220,220,1)",
					pointColor: "rgba(220,220,220,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: [65, 59, 80, 81, 56, 55, 40]
				},
				{
					label: "My Second dataset",
					fillColor: "rgba(151,187,205,0.2)",
					strokeColor: "rgba(151,187,205,1)",
					pointColor: "rgba(151,187,205,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: [28, 48, 40, 19, 86, 27, 90]
				}]
		};
		return data;
	},

	radarData: function () {
		var dataRadar = {
			labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
			datasets: [
				{
					label: "My First dataset",
					fillColor: "rgba(220,220,220,0.2)",
					strokeColor: "rgba(220,220,220,1)",
					pointColor: "rgba(220,220,220,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
					label: "My Second dataset",
					fillColor: "rgba(151,187,205,0.2)",
					strokeColor: "rgba(151,187,205,1)",
					pointColor: "rgba(151,187,205,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: [28, 48, 40, 19, 96, 27, 100]
				}
			]
		};
		return dataRadar;
	},

	polarData: function () {
		var dataPolar = [
			{
				value: 300,
				color:"#F7464A",
				highlight: "#FF5A5E",
				label: "Red"
			},
			{
				value: 50,
				color: "#46BFBD",
				highlight: "#5AD3D1",
				label: "Green"
			},
			{
				value: 100,
				color: "#FDB45C",
				highlight: "#FFC870",
				label: "Yellow"
			},
			{
				value: 40,
				color: "#949FB1",
				highlight: "#A8B3C5",
				label: "Grey"
			},
			{
				value: 120,
				color: "#4D5360",
				highlight: "#616774",
				label: "Dark Grey"
			}
		];
		return dataPolar;
	},

	pieData: function () {
		var dataPie = [
			{
				value: 300,
				color:"#F7464A",
				highlight: "#FF5A5E",
				label: "Red"
			},
			{
				value: 50,
				color: "#46BFBD",
				highlight: "#5AD3D1",
				label: "Green"
			},
			{
				value: 100,
				color: "#FDB45C",
				highlight: "#FFC870",
				label: "Yellow"
			}
		];
		return dataPie;
	},

	init: function () {
		this.lineChart();
		this.barChart();
		this.radarChart();
		this.polarAreaChart();
		this.pieChart();
		this.doughnutChart();
	}
}


