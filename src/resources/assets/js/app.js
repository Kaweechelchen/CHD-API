require('./bootstrap');

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

var createGraph = function(obj, pointRadius) {
    var data = $(obj).data('data');
    var label = $(obj).data('labels');
    if (typeof (label) === 'undefined') {
        label = data;
    }
    if (typeof (pointRadius) === 'undefined') {
        pointRadius = 0;
    }
    var ctx = obj.getContext("2d");
    var gradient = ctx.createLinearGradient(0, 0, 0, 100);
        gradient.addColorStop(0, 'rgba(156,204,101 ,1)');
        gradient.addColorStop(1, 'rgba(220,237,200 ,0.5)');

    new Chart(
        ctx,
        {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    label: 'Signatures',
                    pointRadius: pointRadius,
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
                        display: false,
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
                            display: false
                        }
                    }]
                },
                tooltips: {
                    intersect: false,
                    mode: 'x'
                }
            }
        }
    );

}

$('.graph').each(function(i, obj) {
    createGraph(obj);
});

$('.smallGraph').each(function(i, obj) {
    createGraph(obj, 3);
});
