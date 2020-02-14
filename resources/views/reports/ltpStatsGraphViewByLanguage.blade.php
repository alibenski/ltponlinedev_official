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
  
  let arr1 = [1600,2000,5000, 4500, 3000, 2500];

  function createChart(data) {
    let myDataSet = [];
    let x = [];
    $.each(data.years, function (indexInArray, valueOfElement) {
        console.log(data.registrationsPerYearPerLanguage)
        x = {            
            label: valueOfElement,
            data: arr1,
            backgroundColor: "rgba(142,94,162,0.2)",
            borderColor: "rgba(142,94,162)",
            borderWidth: "2",
            fill: "false",
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
</script>

@stop