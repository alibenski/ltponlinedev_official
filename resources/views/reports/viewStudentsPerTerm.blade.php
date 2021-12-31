@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
		.error { color: red; }
    </style>
@stop

@section('content')
<div class="preloader2" >
	<h3 class="text-center">Please wait... Loading data from database... Drawing the table...</h3> 
</div>
<table id="sampol" class="table table-striped no-wrap" width="100%">
	<thead>
		<tr>
			<th>Term</th>
			<th>Student Count</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Term</th>
			<th>Student Count</th>
		</tr>
	</tfoot>
</table>

<div class="row">
  <p><i class="fa fa-info-circle"></i> <small>Values above show the number of <strong>active students enrolled</strong> to their classes per term <strong>excluding waitlisted and cancelled</strong></small></p>
</div>

@stop

@section('java_script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		let promises = [];

		promises.push(
			$.ajax({
	    		url: 'stats-students-per-term',
	    		type: 'GET',
	    		data: {},
	    	})
	    	.done(function(data) {
	    		console.log(data)
	    		assignToEventsColumns(data);
	    	})
	    	.fail(function(data) {
	    		console.log(data)
	    	})
	    	.always(function() {
	    		console.log("complete");
	    	})
    	);

		$.when.apply($.ajax(), promises).then(function() {
	        $(".preloader2").fadeOut(600);
	    });

    	function assignToEventsColumns(data) {
		    var table = $('#sampol').DataTable({
		    	// "deferRender": true,
		    	"dom": 'B<"clear">lfrtip',
		    	"buttons": [
				        'copy', 'csv', 'excel', 'pdf'
				    ],
				"order": [[ 0, "desc" ]],
		    	"scrollX": true,
		    	"destroy": true, // destroy the existing table to apply the new options
		    	"responsive": false,
		    	"orderCellsTop": true,
		    	"fixedHeader": true,
		    	"pagingType": "full_numbers",
		        "bAutoWidth": false,
		        "aaData": data,
		        "columns": [
		        		{ "data": "term" }, 
		        		{ "data":  "count" }, 
					        ],


		    })
		}
	});
</script>
@stop