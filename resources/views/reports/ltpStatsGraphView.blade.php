@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<h2 class="text-center"><i class="fa fa-bar-chart"></i> {{ucwords('Evolution of registrations in language courses')}} </h2>

<div class="row">
	<div class="form-group">
      <label for="Term" class="col-md-12 control-label">Year Select:</label>
      <div class="form-group col-sm-12">
        <div class="dropdown">
          <select id="Term" name="Term" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required" autocomplete="off">
            @foreach($years as $value)
                <option></option>
                <option value="{{$value}}" @if ($value < 2019) disabled @endif>{{$value}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
</div> {{-- end filter div --}}

<div class="row">
  <div class="col-md-12">
    <canvas id="myAreaChart" width="1200" height="400"></canvas>
  </div>
</div>

<div class="row">

</div>

@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
$(document).ready(function() {
  $('.select2-basic-single').select2({
      placeholder: "Select Filter",
  });

  let myLineChart = [];

  $.ajax({
      url: '{{ route('get-ltp-stats-graph-view') }}',
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

  $('select#Term').change(function() {
    let year = $(this).val();
    const token = $("input[name='_token']").val();
    $.ajax({
      url: '{{ route('get-ltp-stats-graph-view-by-term') }}',
      type: 'GET',
      dataType: 'json',
      data: {_token:token, year:year},
    })
    .done(function(data) {
      // console.log(data.data);
      createChart(data);
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
  });

  let arr1 = [1,2,3];
  let arr2 = [1,2,3];

  function createChart(data) {
    let arrSum =  data.arrSum;
    // Area Chart 
    const ctx = document.getElementById("myAreaChart");
    myLineChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labelYears,
        datasets: [{
          type: 'line',
          fill: "false",
          label: "Registrations",
          lineTension: 0.3,
          backgroundColor: "rgba(2,117,216,0.2)",
          borderColor: "rgba(2,117,216,1)",
          pointRadius: 5,
          pointBackgroundColor: "rgba(2,117,216,1)",
          pointBorderColor: "rgba(255,255,255,0.8)",
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "rgba(2,117,216,1)",
          pointHitRadius: 50,
          pointBorderWidth: 2,
          data: arrSum,
        },
        {
            // Changes this dataset to become a line
            
            label: "USD 365 Income",
          data: arr1,
          backgroundColor: "rgba(142,94,162,0.2)",
          borderColor: "rgba(142,94,162)",
          borderWidth: "2",
          fill: "false",
        },
        {
          
            label: "USD 600 Income",
          data: arr2,
          backgroundColor: "rgba(68,186,81,0.2)", 
          borderColor: "rgba(68,186,81)",
          borderWidth: "2",
          fill: "false",
        }
        ],
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
              // maxTicksLimit: 7
            }
          }],
          yAxes: [{
            ticks: {
              min: 2000,
              max: 4000,
              maxTicksLimit: 5
            },
            gridLines: {
              color: "rgba(0, 0, 0, .125)",
            }
          }],
        },
        legend: {
          display: true
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