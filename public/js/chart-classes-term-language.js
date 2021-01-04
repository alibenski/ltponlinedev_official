$(document).ready(function () {
    let myLineChart = [];

    $.ajax({
        url: '/admin/classes-term-language',
        type: 'GET',
        dataType: 'json',
    })
    .done(function (data) {
        console.log(data.data);
        createChart2(data.data);
    })
    .fail(function () {
        console.log("error");
    })
    .always(function () {
        console.log("complete");
    });

function randomColor() {
    const rand = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'];
    let color = '#' + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)];

    return color;
}

function createChart2(data) {
    Chart.plugins.unregister(ChartDataLabels);
    let myDataSet2 = [];
    let x = [];
    $.each(data.labelTerms, function (indexInArray, valueOfElement) {
        x = {
            label: valueOfElement,
            data: data.classesPerTerm[indexInArray],
            backgroundColor: randomColor(),
            fill: "true",
        }
        myDataSet2.push(x);
    });

    const ctx = document.getElementById("myAreaChart2");
    myLineChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.xAxis,
            datasets: myDataSet2,
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
                        // maxTicksLimit: 10
                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, .125)",
                    }
                }],
            },
            legend: {
                display: true,
                position: 'bottom',
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
        easing: 'easeInCubic'
    });
}
});