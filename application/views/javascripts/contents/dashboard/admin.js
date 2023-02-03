$(function () {
	$('#daterange').daterangepicker({
		showDropdowns: true,
		locale: {
			format: 'Y-MM-DD',
			separator: " to "
		}
	});
	$('#monthrange').daterangepicker({
		showDropdowns: true,
		locale: {
			format: 'Y-MM',
			separator: " to "
		}
	});

	var ticksStyle = {
		fontColor: '#495057',
		fontStyle: 'bold'
	}
	var mode = 'index'

	var intersect = true
	// eslint-disable-next-line no-unused-vars
	var $monthChart = $('#month-chart')
	// eslint-disable-next-line no-unused-vars
	var monthChart = new Chart($monthChart, {
		type: 'bar',
		data: {
			labels: month.labels,
			datasets: [
				{
					backgroundColor: '#28a745',
					borderColor: '#28a745',
					data: month.datas
				}
			]
		},
		options: {
			maintainAspectRatio: false,
			tooltips: {
				mode: mode,
				intersect: intersect
			},
			hover: {
				mode: mode,
				intersect: intersect
			},
			legend: {
				display: false
			},
			scales: {
				yAxes: [{
					// display: false,
					gridLines: {
						display: true,
						lineWidth: '4px',
						color: 'rgba(0, 0, 0, .2)',
						zeroLineColor: 'transparent'
					},
					ticks: $.extend({
						beginAtZero: true,

						// Include a dollar sign in the ticks
						callback: function (value) {
							if (value >= 1000) {
								value /= 1000
								value += 'k'
							}

							return value
						}
					}, ticksStyle)
				}],
				xAxes: [{
					display: true,
					gridLines: {
						display: false
					},
					ticks: ticksStyle
				}]
			}
		}
	})

	// eslint-disable-next-line no-unused-vars
	var $dateChart = $('#date-chart')
	// eslint-disable-next-line no-unused-vars
	var dateChart = new Chart($dateChart, {
		type: 'bar',
		data: {
			labels: date.labels,
			datasets: [
				{
					backgroundColor: '#17a2b8',
					borderColor: '#17a2b8',
					data: date.datas
				}
			]
		},
		options: {
			maintainAspectRatio: false,
			tooltips: {
				mode: mode,
				intersect: intersect
			},
			hover: {
				mode: mode,
				intersect: intersect
			},
			legend: {
				display: false
			},
			scales: {
				yAxes: [{
					// display: false,
					gridLines: {
						display: true,
						lineWidth: '4px',
						color: 'rgba(0, 0, 0, .2)',
						zeroLineColor: 'transparent'
					},
					ticks: $.extend({
						beginAtZero: true,

						// Include a dollar sign in the ticks
						callback: function (value) {
							if (value >= 1000) {
								value /= 1000
								value += 'k'
							}

							return value
						}
					}, ticksStyle)
				}],
				xAxes: [{
					display: true,
					gridLines: {
						display: false
					},
					ticks: ticksStyle
				}]
			}
		}
	})
})