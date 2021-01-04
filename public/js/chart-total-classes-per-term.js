$(document).ready(function () {
    let myLineChart = [];

    $.ajax({
        url: '/admin/total-classes-per-term',
        type: 'GET',
        dataType: 'json',
    })
    .done(function (data) {
        console.log(data.data);
        createChart(data.data);
    })
    .fail(function () {
        console.log("error");
    })
    .always(function () {
        console.log("complete");
    });

    function createChart(data) {
        // Area Chart 
        const ctx = document.getElementById("myAreaChart3");
        myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labelYears,
                datasets: [{
                    data: data.regSum,
                    type: 'line',
                    fill: "false",
                    label: "Classrooms",
                    lineTension: 0.2,
                    backgroundColor: "rgba(2,117,216,0.2)",
                    borderColor: "rgba(2,117,216,0.5)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(2,117,216,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    datalabels: {
                        color: "rgba(2,117,216,1)",
                        align: "-45",
                        offset: "10"
                    }
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'year'
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            autoSkip: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: 150,
                            maxTicksLimit: 10
                        },
                        gridLines: {
                            color: "rgba(0, 0, 0, .125)",
                        }
                    }],
                },
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: data.title,
                    fontSize: 22
                }
            }
        });
        myLineChart.chart.update({
            duration: 800,
            easing: 'easeInOutBounce'
        });
    }
});