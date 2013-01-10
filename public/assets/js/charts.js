$(document).ready(function(){

});
// doc: http://api.highcharts.com/highcharts
var charts;
var options = {
	chart: {
		renderTo: 'daily',
		type: 'line',
		plotBackgroundColor: '#FFFFFF',
		plotShadow: true,
		zoomType: 'x',
		plotBorderWidth: 0,
		height: 700,
		animation: {
			duration: 1000
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
			cursor:'pointer',
			events: {
				click: function(event) {
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
			Date.parse(Highcharts.dateFormat('%B %Y', this.x)).add(1).month().toString('MMMM yyyy') +': '+ this.y +' registrations';
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
$.ajax({
	url:'/ajax/charts/daily',
	success:function(data){

		$.each(data, function(key, form){
			options.series.push(form)
		});
		charts = new Highcharts.Chart(options);
	}
})
$('.')
