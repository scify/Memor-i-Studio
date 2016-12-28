var ChartsFlot = {

	basic: function () {
		var d1 = [];
		for (var i = 0; i < 14; i += 0.5) {
			d1.push([i, Math.sin(i)]);
		}
		var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];
		// A null signifies separate line segments
		var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];
		$.plot('.flot-basic', [ d1, d2, d3 ]);
	},

	categoriesTextual: function () {
		var data = [ ["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9] ];
		$.plot('.flot-categories', [ data ], {
			series: {
				bars: {
					show: true,
					barWidth: 0.6,
					align: 'center'
				}
			},
			xaxis: {
				mode: 'categories',
				tickLength: 0
			}
		});
	},

	settingOptions: function () {
		var d1 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.25) {
			d1.push([i, Math.sin(i)]);
		}

		var d2 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.25) {
			d2.push([i, Math.cos(i)]);
		}

		var d3 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.1) {
			d3.push([i, Math.tan(i)]);
		}

		$.plot('.flot-options', [
			{ label: "sin(x)", data: d1 },
			{ label: "cos(x)", data: d2 },
			{ label: "tan(x)", data: d3 }
		], {
			series: {
				lines: { show: true },
				points: { show: true }
			},
			xaxis: {
				ticks: [
					0, [ Math.PI/2, "\u03c0/2" ], [ Math.PI, "\u03c0" ],
					[ Math.PI * 3/2, "3\u03c0/2" ], [ Math.PI * 2, "2\u03c0" ]
				]
			},
			yaxis: {
				ticks: 10,
				min: -2,
				max: 2,
				tickDecimals: 3
			},
			grid: {
				backgroundColor: { colors: [ "#fff", "#eee" ] },
				borderWidth: {
					top: 1,
					right: 1,
					bottom: 2,
					left: 2
				}
			}
		});
	},

	stackingBarsLines: function () {
		var d1 = [];
		for (var i = 0; i <= 10; i += 1) {
			d1.push([i, parseInt(Math.random() * 30)]);
		}

		var d2 = [];
		for (var i = 0; i <= 10; i += 1) {
			d2.push([i, parseInt(Math.random() * 30)]);
		}

		var d3 = [];
		for (var i = 0; i <= 10; i += 1) {
			d3.push([i, parseInt(Math.random() * 30)]);
		}
		$.plot('.flot-stacking-bars', [ d1, d2, d3 ], {
			series: {
				stack: 0,
				lines: {
					show: false,
					fill: true,
					steps: false
				},
				bars: {
					show: true,
					barWidth: 0.6
				}
			}
		});

		$.plot('.flot-stacking-lines', [ d1, d2, d3 ], {
			series: {
				stack: 0,
				lines: {
					show: true,
					fill: true,
					steps: false
				},
				bars: {
					show: false,
					barWidth: 0.6
				}
			}
		});
	},

	realTime: function () {
		var data = [],
			totalPoints = 300;

		function getRandomData() {

			if (data.length > 0)
				data = data.slice(1);

			// Do a random walk

			while (data.length < totalPoints) {

				var prev = data.length > 0 ? data[data.length - 1] : 50,
					y = prev + Math.random() * 10 - 5;

				if (y < 0) {
					y = 0;
				} else if (y > 100) {
					y = 100;
				}

				data.push(y);
			}

			// Zip the generated y values with the x values

			var res = [];
			for (var i = 0; i < data.length; ++i) {
				res.push([i, data[i]])
			}

			return res;
		}

		var updateInterval = 30;
		var plot = $.plot('.flot-realtime', [ getRandomData() ], {
			series: {
				shadowSize: 0	// Drawing is faster without shadows
			},
			yaxis: {
				min: 0,
				max: 100
			},
			xaxis: {
				show: false
			}
		});

		function update() {

			plot.setData([getRandomData()]);

			// Since the axes don't change, we don't need to call plot.setupGrid()

			plot.draw();
			setTimeout(update, updateInterval);
		}
		update();
	},

	toggleSeries: function () {
		var datasets = {
			"usa": {
				label: "USA",
				data: [[1988, 483994], [1989, 479060], [1990, 457648], [1991, 401949], [1992, 424705], [1993, 402375], [1994, 377867], [1995, 357382], [1996, 337946], [1997, 336185], [1998, 328611], [1999, 329421], [2000, 342172], [2001, 344932], [2002, 387303], [2003, 440813], [2004, 480451], [2005, 504638], [2006, 528692]]
			},
			"russia": {
				label: "Russia",
				data: [[1988, 218000], [1989, 203000], [1990, 171000], [1992, 42500], [1993, 37600], [1994, 36600], [1995, 21700], [1996, 19200], [1997, 21300], [1998, 13600], [1999, 14000], [2000, 19100], [2001, 21300], [2002, 23600], [2003, 25100], [2004, 26100], [2005, 31100], [2006, 34700]]
			},
			"uk": {
				label: "UK",
				data: [[1988, 62982], [1989, 62027], [1990, 60696], [1991, 62348], [1992, 58560], [1993, 56393], [1994, 54579], [1995, 50818], [1996, 50554], [1997, 48276], [1998, 47691], [1999, 47529], [2000, 47778], [2001, 48760], [2002, 50949], [2003, 57452], [2004, 60234], [2005, 60076], [2006, 59213]]
			},
			"germany": {
				label: "Germany",
				data: [[1988, 55627], [1989, 55475], [1990, 58464], [1991, 55134], [1992, 52436], [1993, 47139], [1994, 43962], [1995, 43238], [1996, 42395], [1997, 40854], [1998, 40993], [1999, 41822], [2000, 41147], [2001, 40474], [2002, 40604], [2003, 40044], [2004, 38816], [2005, 38060], [2006, 36984]]
			},
			"denmark": {
				label: "Denmark",
				data: [[1988, 3813], [1989, 3719], [1990, 3722], [1991, 3789], [1992, 3720], [1993, 3730], [1994, 3636], [1995, 3598], [1996, 3610], [1997, 3655], [1998, 3695], [1999, 3673], [2000, 3553], [2001, 3774], [2002, 3728], [2003, 3618], [2004, 3638], [2005, 3467], [2006, 3770]]
			},
			"sweden": {
				label: "Sweden",
				data: [[1988, 6402], [1989, 6474], [1990, 6605], [1991, 6209], [1992, 6035], [1993, 6020], [1994, 6000], [1995, 6018], [1996, 3958], [1997, 5780], [1998, 5954], [1999, 6178], [2000, 6411], [2001, 5993], [2002, 5833], [2003, 5791], [2004, 5450], [2005, 5521], [2006, 5271]]
			},
			"norway": {
				label: "Norway",
				data: [[1988, 4382], [1989, 4498], [1990, 4535], [1991, 4398], [1992, 4766], [1993, 4441], [1994, 4670], [1995, 4217], [1996, 4275], [1997, 4203], [1998, 4482], [1999, 4506], [2000, 4358], [2001, 4385], [2002, 5269], [2003, 5066], [2004, 5194], [2005, 4887], [2006, 4891]]
			}
		};

		// hard-code color indices to prevent them from shifting as
		// countries are turned on/off

		var i = 0;
		$.each(datasets, function(key, val) {
			val.color = i;
			++i;
		});

		// insert checkboxes
		var choiceContainer = $("#choices");
		$.each(datasets, function(key, val) {
			choiceContainer.append("<br/><input type='checkbox' name='" + key +
				"' checked='checked' id='id" + key + "'></input>" +
				"<label for='id" + key + "'>"
				+ val.label + "</label>");
		});

		choiceContainer.find("input").click(plotAccordingToChoices);

		function plotAccordingToChoices() {

			var data = [];

			choiceContainer.find("input:checked").each(function () {
				var key = $(this).attr("name");
				if (key && datasets[key]) {
					data.push(datasets[key]);
				}
			});

			if (data.length > 0) {
				$.plot('.flot-toggle', data, {
					yaxis: {
						min: 0
					},
					xaxis: {
						tickDecimals: 0
					}
				});
			}
		}

		plotAccordingToChoices();
	},

	pieData: function () {
		// Randomly Generated Data
		var data = [],
			series = Math.floor(Math.random() * 6) + 3;

		for (var i = 0; i < series; i++) {
			data[i] = {
				label: "Series" + (i + 1),
				data: Math.floor(Math.random() * 100) + 1
			}
		}
		return data;
	},

	pieChart: function () {
		$.plot('.flot-pie-default', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true
				}
			}
		});
	},

	pieChartWithoutLegend: function () {
		$.plot('.flot-pie-without-legend', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true
				}
			},
			legend: {
				show: false
			}
		});
	},

	pieChartLabelFormatter: function () {
		$.plot('.flot-pie-label-formatter', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 1,
						formatter: ChartsFlot.labelFormatter,
						background: {
							opacity: 0.8
						}
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	pieChartLabelRadius: function () {
		$.plot('.flot-pie-label-radius', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 3/4,
						formatter: ChartsFlot.labelFormatter,
						background: {
							opacity: 0.5
						}
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	pieChartLabelStyle1: function () {
		$.plot('.flot-pie-label-style1', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 3/4,
						formatter: ChartsFlot.labelFormatter,
						background: {
							opacity: 0.5,
							color: "#000"
						}
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	pieChartLabelStyle2: function () {
		$.plot('.flot-pie-label-style2', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true,
					radius: 3/4,
					label: {
						show: true,
						radius: 3/4,
						formatter: ChartsFlot.labelFormatter,
						background: {
							opacity: 0.5,
							color: "#000"
						}
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	pieChartHiddenLabels: function () {
		$.plot('.flot-pie-hidden-labels', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 2/3,
						formatter: ChartsFlot.labelFormatter,
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	pieChartCombinedSlice: function () {
		$.plot('.flot-pie-combined-slice', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true,
					combine: {
						color: "#999",
						threshold: 0.05
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	rectangularPie: function () {
		$.plot('.flot-pie-rectangular', ChartsFlot.pieData(), {
		series: {
				pie: {
					show: true,
					radius: 500,
					label: {
						show: true,
						formatter: ChartsFlot.labelFormatter,
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	tiltedPie: function () {
		$.plot('.flot-pie-tilted', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true,
					radius: 1,
					tilt: 0.5,
					label: {
						show: true,
						radius: 1,
						formatter: ChartsFlot.labelFormatter,
						background: {
							opacity: 0.8
						}
					},
					combine: {
						color: "#999",
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			}
		});
	},

	donutHole: function () {
		$.plot('.flot-pie-donut', ChartsFlot.pieData(), {
			series: {
				pie: {
					innerRadius: 0.5,
					show: true
				}
			}
		});
	},

	interactivity: function () {
		$.plot('.flot-pie-interactivity', ChartsFlot.pieData(), {
			series: {
				pie: {
					show: true
				}
			},
			grid: {
				hoverable: true,
				clickable: true
			}
		});

		$('.flot-pie-interactivity').bind("plothover", function(event, pos, obj) {
			if (!obj) {
				return;
			}
			var percent = parseFloat(obj.series.percent).toFixed(2);
			$("#hover").html("<span style='font-weight:bold; color:" + obj.series.color + "'>" + obj.series.label + " (" + percent + "%)</span>");
		});

		$('.flot-pie-interactivity').bind("plotclick", function(event, pos, obj) {
			if (!obj) {
				return;
			}
			percent = parseFloat(obj.series.percent).toFixed(2);
			alert(""  + obj.series.label + ": " + percent + "%");
		});
	},

	labelFormatter: function (label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	},

	init: function () {
		this.basic();
		this.categoriesTextual();
		this.settingOptions();
		this.stackingBarsLines();
		this.realTime();
		this.toggleSeries();
		this.pieChart();
		this.pieChartWithoutLegend();
		this.pieChartLabelFormatter();
		this.pieChartLabelRadius();
		this.pieChartLabelStyle1();
		this.pieChartLabelStyle2();
		this.pieChartHiddenLabels();
		this.pieChartCombinedSlice();
		this.rectangularPie();
		this.tiltedPie();
		this.donutHole();
		this.interactivity();
	}
}

