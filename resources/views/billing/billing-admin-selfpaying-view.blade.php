@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
    </style>
@stop

@section('content')

<h2 class="text-center"><i class="fa fa-usd"></i> Self-Paying Stats View <i class="fa fa-usd"></i></h2>

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
  <div class="col-md-4 each-income-per-season">
	    
  </div>

  <div class="col-md-4 year-total">
    
  </div>
  
  <div class="col-md-4 sum-total-year">
    
  </div>
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
});

var myLineChart = [];

$('select#Term').change(function() {
	var year = $(this).val();
	var token = $("input[name='_token']").val();
	$.ajax({
		url: '{{ route('ajax-selfpaying-by-year-table') }}',
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

function createChart(data) {
	if (myLineChart.id != undefined) {
		myLineChart.chart.destroy();
	}

	$('p.total-year-product').remove();
	$('h4.total-year-sum').remove();
	$('div.each-income-per-season').children('p').remove();

	// Set new default font family and font color to mimic Bootstrap's default styling
	Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#292b2c';

	var seasons = [];
	var prices = [];
	var countPrices = 0;
	var pricesPerTerm = [];
	var arrPrices = [];
	
	$.each(data.data, function(index, val) {
		// console.log(index, val)
		$.each(val, function(k, v) {
			seasons.push(v.terms.Comments);		
			prices.push(v.courseschedules.prices.price_usd);	
			pricesPerTerm.push(v.courseschedules.prices.price_usd);	
		});

		arrPrices.push(pricesPerTerm);
		pricesPerTerm = [];

	});

	var uniqueSeasons = $.grep(seasons, function (name, index) {
        return $.inArray(name, seasons) === index;
    }); // Returns Unique seasons

	var basketItems = prices.sort();
	var countsYearTotal = {};

	// get number of duplicate values in array
	$.each(basketItems, function(key,value) {
	  if (!countsYearTotal.hasOwnProperty(value)) {
	    countsYearTotal[value] = 1;
	  } else {
	    countsYearTotal[value]++;
	  }
	});

	// console.log(countsYearTotal)

	var arrYearProduct = [];
	$.each(countsYearTotal, function(x, v) {
		var yearProduct = x * v;
		$('div.year-total').append('<p class="total-year-product"><strong>'+x+' USD x '+v+ ' = ' +yearProduct+ ' USD</strong></p>');
		arrYearProduct.push(yearProduct);
	});

	// console.log(arrYearProduct)

	// get the sum of 600 and 356 for the whole year
	var sumTotalYear = 0;
	$.each(arrYearProduct,function(){sumTotalYear+=parseFloat(this) || 0;});
	$('div.sum-total-year').append('<h4 class="total-year-sum"><strong>Total Income: ' +sumTotalYear+ ' USD</strong></h4>');

	// break down 600 and 356 per term
	var items = arrPrices;
	var	counts = {};
	var arrCountsPerTerm = [];

	$.each(items, function(key,value) {
		// console.log(value.sort())
		$.each(value, function(k, v) {
			  if (!counts.hasOwnProperty(v)) {
			    counts[v] = 1;
			  } else {
			    counts[v]++;
			  }
		});

		arrCountsPerTerm.push(counts);
		counts = {};
	});

	console.log(arrCountsPerTerm)

	var output = [];
	var product = [];
	var arrProduct = [];
	$.each(arrCountsPerTerm, function(x, y) {
		// console.log(y)
		$.each(y, function(a, b) {
			product.push(a * b);
		});
		arrProduct.push(product);
		// console.log(arrProduct)
		product =[];
		// $('div.enter-sum').append('<p>'+i+' USD x '+v+ ' = ' +product+ ' USD</p>');
	});

	console.log(arrProduct)

	var assocArray = {};
	var assocProduct = {};

	$.each(arrCountsPerTerm, function(index, val) {
		$.each(val, function(a, b) {
			assocProduct[a] = a * b;
		});

		assocArray[index] = assocProduct;
		assocProduct = {};
		
	});
	console.log(assocArray)

	var eachIncomePerSeason = {};
	$.each(uniqueSeasons, function(index, val) {
		eachIncomePerSeason[val] = assocArray[index];
	});

	console.log(eachIncomePerSeason)

	$.each(eachIncomePerSeason, function(index, val) {
		$('div.each-income-per-season').append('<p class="show-cash-'+index+'"><strong>'+index+': </strong></p>');
		$.each(val, function(i, v) {
			$('p.show-cash-'+index).append('<p class="show-cash-'+i+'">'+i+' USD: '+v+' </p>');
		});
	});

	var uniquePrices = $.grep(prices, function (name, index) {
        return $.inArray(name, prices) === index;
    }); // Returns Unique prices
	
	// create dynamic variables
	// var baseVariable = {};

	// for (var i = 0; i < uniquePrices.length; ++i) {
	//     baseVariable[i] = [];
	// }

	// $.each(baseVariable, function(e, f) { 
	// 	console.log(f)
	// });


	var arr1 = [];
	var arr2 = [];

	$.each(arrProduct, function(i, v) {
		
		if (v.length < uniquePrices.length) {
			$.each(v, function(index, val) {
				if (index == 0) {
					arr1.push(val);
				} 					
				var valNone = 0;
				arr2.push(valNone);
			});
		} else {
			$.each(v, function(index, val) {
				if (index == 0) {
					arr1.push(val);
				}
				if (index == 1) {
					arr2.push(val);
				}				
			});
		}
	});

	// get the sum of each array value
	var arrSum = [];
	$.each(arrProduct, function(index, val) {
		var r = 0;
		$.each(val, function(i, v) {
	        r += +v;
	    });
		// console.log(r)
	    // return r;
		arrSum.push(r);

	});

	// console.log(arrSum)

	// Area Chart 
	var ctx = document.getElementById("myAreaChart");
	myLineChart = new Chart(ctx, {
	  type: 'bar',
	  data: {
	    labels: uniqueSeasons,
	    datasets: [{
	      type: 'line',
	      fill: "false",
	      label: "USD Income",
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
	          unit: 'date'
	        },
	        gridLines: {
	          display: false
	        },
	        ticks: {
	          maxTicksLimit: 7
	        }
	      }],
	      yAxes: [{
	        ticks: {
	          // min: 0,
	          // max: 200000,
	          // maxTicksLimit: 5
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

</script>

@stop