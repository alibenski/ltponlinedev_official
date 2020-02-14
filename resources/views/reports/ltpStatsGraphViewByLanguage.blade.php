@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="row">
  <div class="col-md-12">
    <canvas id="myAreaChart" width="1200" height="400"></canvas>
  </div>
</div>

@stop

@section('java_script')

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script>
$(document).ready(function() {
  let myLineChart = [];

  $.ajax({
      url: '{{ route('get-ltp-stats-graph-view-by-language') }}',
      type: 'GET',
      dataType: 'json',
    })
    .done(function(data) {
      console.log(data.data);
      createChart(data.data);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  function randomColor() {  
    const rand = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f' ];
    let color = '#' + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)] + rand[Math.ceil(Math.random() * 15)];

    return color;
  }

  function createChart(data) {
    Chart.plugins.unregister(ChartDataLabels);
    let myDataSet = [];
    let x = [];
    $.each(data.years, function (indexInArray, valueOfElement) {
        x = {            
            label: valueOfElement,
            data: data.registrationsPerYearPerLanguage[indexInArray],
            backgroundColor: randomColor(),
            fill: "true",
          } 
        myDataSet.push(x);
    }); 

    const ctx = document.getElementById("myAreaChart");
    myLineChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.xAxis,
        datasets: myDataSet,
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
</script>

@stop