$(document).ready(function(){

});
// doc: http://api.highcharts.com/highcharts
var options = {
	chart: {
		renderTo: 'daily',
		type: 'line',
		plotBackgroundColor: '#FFFFFF',
		plotShadow: true,
		zoomType: 'x',
		animation: {
			duration: 2000
		},
		theme: {
			fill: 'white',stroke: '#bbbbbb',r: 5,states: {
				hover: {
					fill: '#41739D',style: {
						color: 'white'
					}
				}
			}
		}
	},
	yAxis: {
		min: 0,
		startOnTick: false
	},
	title: {
		text: 'Registration tracking'
	},
	exporting: {
		filename: 'ikon-chart'
	},
	plotOptions: {
		series: {
			events: {
				legendItemClick: function(event) {
					if (!this.visible)
					return true;

					var seriesIndex = this.index;
					var series = this.chart.series;

					for (var i = 0; i < series.length; i++)
					{
						if (series[i].index != seriesIndex)
						{

							series[i].visible ? series[i].hide() : series[i].show();
						}
					}

					return false;
				}
			}
		}
	},
	tooltip: {
		formatter: function() {
			return '<b>'+ this.series.name +'</b><br/>'+
			Highcharts.dateFormat('%e. %b %Y', this.x) +': '+ this.y +' registrations';
		}
	},
	series:[],
	xAxis: {
		type: 'datetime',
		labels: {
			rotation: -45,
			align: 'right',
			style: {
				font: 'normal 13px Verdana, sans-serif'
			}
		},
		dateTimeLabelFormats: { // don't display the dummy year
		month: '%e. %b',
		year: '%b'
	}
},
navigation: {
	menuStyle: {
		background: '#eee',
		padding: '5px'
	},
	menuItemStyle:{
		padding:'5px 2px'
	}
},
credits: {
	enabled: false
},
yAxis: {
	title: {
		text: 'Registrations'
	},
	plotLines: [{
		value: 0,
		width: 1,
		color: '#808080'
		}]
	}
};

charts = new Highcharts.Chart(options)
