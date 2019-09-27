@extends('admin.admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
    </style>
@stop

@section('content')

<h2 class="text-center"><i class="fa fa-usd"></i> Self-Paying Students <i class="fa fa-usd"></i></h2>
<div class="row">
	<div class="form-group">
	<label for="Term" class="col-md-12 control-label">Select Term:</label>
	<div class="form-group col-sm-12">
	    <div class="dropdown">
	      <select id="Term" name="Term" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
	        @foreach($terms as $value)
	            <option></option>
	            <option value="{{$value->Term_Code}}">{{$value->Term_Code}} - {{$value->Comments}} - {{$value->Term_Name}}</option>
	        @endforeach
	      </select>
	    </div>
	  </div>
	</div>
</div>

<div class="billing-section hidden">
	<div class="preloader2 hidden"><p><strong>Please wait... Fetching data from the database...</strong></p></div>
	
	<div class="row">
		<div class="col-sm-12 alert enter-sum">
			
		</div>
	</div>

	<table id="sampol" class="table table-striped no-wrap" width="100%">
		<thead>
			<tr>
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				<th>Price USD</th>
				<th>Duration</th>
				<th>Organization</th>
				<th>Name</th>
				<th>RESULT</th>
				<th>Cancel Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				<th>Price USD</th>
				<th>Duration</th>
				<th>Organization</th>
				<th>Name</th>
				<th>RESULT</th>
				<th>Cancel Date</th>
			</tr>
		</tfoot>
	</table>
</div>	

@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script>
{{-- <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<script>
$(document).ready(function() {
	$('.select2-basic-single').select2({
    	placeholder: "Select Filter",
    });

	$('select#Term').change(function() {
		$(".preloader2").fadeIn(400);
		$('div.preloader2').removeClass('hidden');
		$('div.billing-section').removeClass('hidden');
		
		var promises = [];
		var token = $("input[name='_token']").val();
		var term = $(this).val();		

		promises.push(
			$.ajax({
				url: '{{ route('ajax-selfpaying-student-table') }}',
				type: 'GET',
				dataType: 'json',
				data: {term:term, _token:token},
			})
			.then(function(data) {
				console.log(data)
				getSumOfPrices(data);
				assignToEventsColumns(data);
				// console.log(data.data)
				// var data = jQuery.parseJSON(data.data);
				// console.log(data)
			})
			.fail(function() {
				console.log(data);
			})

		);

		function getSumOfPrices(data) {
			// console.log(data.data); //array
			$('p.show-sum').remove();
			var prices = [];

			$.each(data.data, function(index, val) {
				prices.push(val.courseschedules.prices.price_usd);
			});
			var basketItems = prices.sort(),
			    counts = {};

			// get number of duplicate values in array
			$.each(basketItems, function(key,value) {
			  if (!counts.hasOwnProperty(value)) {
			    counts[value] = 1;
			  } else {
			    counts[value]++;
			  }
			});

			console.log(counts)
			var output = [];
			$.each(counts, function(i, v) {
				output.push(i * v);
				var product = i * v;
				$('div.enter-sum').append('<p class="show-sum">'+i+' USD x '+v+ ' = ' +product+ ' USD</p>');
			});

			console.log(output)
		}

		function assignToEventsColumns(data) {
			$('#sampol thead tr').clone(true).appendTo( '#sampol thead' );
		    $('#sampol thead tr:eq(1) th').each( function (i) {
		        var title = $(this).text();
			        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
			 
			        $( 'input', this ).on( 'keyup change', function () {
			            if ( table.column(i).search() !== this.value ) {
			                table
			                    .column(i)
			                    .search( this.value )
			                    .draw();
			            }
			        } );
			    } );

		    var table = $('#sampol').DataTable({
		    	"destroy": true,
		    	// "deferRender": true,
		    	"dom": 'B<"clear">lfrtip',
		    	"buttons": [
				        'copy', 'csv', 'excel', 'pdf'
				    ],
		    	"scrollX": true,
		    	"responsive": false,
		    	"orderCellsTop": true,
		    	"fixedHeader": true,
		    	"pagingType": "full_numbers",
		        "bAutoWidth": false,
		        "aaData": data.data,
		        "columns": [
		        		{ "data": "Term" }, 
		        		{ "data": "languages.name" }, 
		        		{ "data": "courses.Description" }, 
		        		{ "data": "courseschedules.prices.price_usd" }, 
		        		{ "data": "courseschedules.courseduration.duration_name_en" }, 
		        		{ "data": "DEPT" },  
		        		{ "data": "users.name" }, 
		        		{ "data": "Result", "className": "result" },
		        		{ "data": "deleted_at" }
					        ],
				"createdRow": function( row, data, dataIndex ) {
						    if ( data['Result'] == 'P') {
						      $(row).addClass( 'pass' );
						      $(row).find("td.result").text('PASS');
						    }

						    if ( data['Result'] == 'F') {
						      $(row).addClass( 'label-danger' );
						      $(row).find("td.result").text('Fail');
						    }

						    if ( data['Result'] == 'I') {
						      $(row).addClass( 'label-warning' );
						      $(row).find("td.result").text('Incomplete');
						    }

						    if ( data['deleted_at'] !== null) {
						      $(row).addClass( 'bg-navy' );
						      $(row).find("td.result").text('Late Cancellation');
						    }

					    }
		    })
		}

		$.when.apply($.ajax(), promises).then(function() {
	        $(".preloader2").fadeOut(800);
	    }); 
	});

});
</script>

@stop