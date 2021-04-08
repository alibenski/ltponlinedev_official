@extends('admin.admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
    </style>
@stop

@section('content')

<h2 class="text-center"> Export New Students To Moodle </h2>
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

	<h3 class="text-center">Students from Level 1</h3>
	<table id="sampol" class="table table-striped no-wrap" width="100%">
		<thead>
			<tr>
				<th>INDEXID</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email</th>
				{{-- <th>Te_Code</th> --}}
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>INDEXID</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email</th>
				{{-- <th>Te_Code</th> --}}
			</tr>
		</tfoot>
	</table>
	<br />
	<br />
	<h3 class="text-center">Students from Placement</h3>
    <table id="sampol2" class="table table-striped no-wrap" width="100%">
		<thead>
			<tr>
				<th>INDEXID</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email</th>
				{{-- <th>Te_Code</th> --}}
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>INDEXID</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email</th>
				{{-- <th>Te_Code</th> --}}
			</tr>
		</tfoot>
	</table>
</div>	

@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script>
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
				url: '{{ route('admin-query-export-moodle') }}',
				type: 'GET',
				dataType: 'json',
				data: {term:term, _token:token},
			})
			.then(function(data) {
				console.log(data)
				assignToEventsColumns(data);
			})
			.fail(function() {
				console.log(data);
			})			

		);
		$.ajax({
				url: '{{ route('admin-placement-export-moodle') }}',
				type: 'GET',
				dataType: 'json',
				data: {term:term, _token:token},
			})
			.then(function(data) {
				console.log(data)
				assignToEventsColumns2(data);
			})
			.fail(function() {
				console.log(data);
			})

		function assignToEventsColumns(data) {
		    var table = $('#sampol').DataTable({
		    	"destroy": true,
		    	// "deferRender": true,
		    	"dom": 'B<"clear">lfrtip',
				"bFilter": false,
		    	"buttons": [
				        'copy', 'csv', 'excel', 'pdf'
				    ],
		    	"scrollX": true,
		    	"responsive": false,
		    	"orderCellsTop": true,
		    	"fixedHeader": true,
		    	"pagingType": "full_numbers",
		        "bAutoWidth": false,
		        "aaData": data,
		        "columns": [
		        		{ "data": "INDEXID" }, 
		        		{ "data": "lastname" }, 
		        		{ "data": "firstname" }, 
		        		{ "data": "email" }, 
		        		// { "data": "Te_Code" }, 
		        		
					        ],
		    })
		}

		function assignToEventsColumns2(data) {
		    var table = $('#sampol2').DataTable({
		    	"destroy": true,
		    	// "deferRender": true,
		    	"dom": 'B<"clear">lfrtip',
				"bFilter": false,
		    	"buttons": [
				        'copy', 'csv', 'excel', 'pdf'
				    ],
		    	"scrollX": true,
		    	"responsive": false,
		    	"orderCellsTop": true,
		    	"fixedHeader": true,
		    	"pagingType": "full_numbers",
		        "bAutoWidth": false,
		        "aaData": data,
		        "columns": [
		        		{ "data": "INDEXID" }, 
		        		{ "data": "lastname" }, 
		        		{ "data": "firstname" }, 
		        		{ "data": "email" }, 
		        		// { "data": "Te_Code" }, 
		        		
					        ],
		    })
		}

		$.when.apply($.ajax(), promises).then(function() {
	        $(".preloader2").fadeOut(800);
	    }); 
	});

});
</script>

@stop