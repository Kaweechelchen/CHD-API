require('./bootstrap');

var createGraph = function(obj) {
    var data = $(obj).data('data');
    new Chart(
        obj.getContext("2d"),
        {
            type: 'line',
            data: {
                labels: data,
                datasets: [{
                    backgroundColor: 'rgba(176,190,197 ,0)',
                    pointRadius: 0,
                    borderColor: 'rgba(255,255,255,0.5)',
                    data: data,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        drawOnChartArea: false,
                        gridLines: {
                            display: false
                        },
                        display: true,
                        scaleLabel: {
                            display: false,
                        },
                        ticks : {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            drawBorder: false
                        },
                        scaleLabel: {
                            display: false,
                            labelString: 'Signatures'
                        },
                        ticks: {
                            display: false,
                            suggestedMax: 4000
                        }
                    }]
                }
            }
        }
    );

}

$('.smallGraph').each(function(i, obj) {
    createGraph(obj);
});
