require('./bootstrap');



var createGraph = function(obj) {
    var data = $(obj).data('data');
    var ctx = obj.getContext("2d");
    var gradient = ctx.createLinearGradient(0, 0, 0, 100);
        gradient.addColorStop(0, 'rgba(156,204,101 ,1)');
        gradient.addColorStop(1, 'rgba(220,237,200 ,0.5)');

    new Chart(
        ctx,
        {
            type: 'line',
            data: {
                labels: data,
                datasets: [{
                    pointRadius: 0,
                    borderColor: gradient,
                    data: data,
                    fill: false
                }]
            },
            options: {
                animation : false,
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
                            display: false,
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
