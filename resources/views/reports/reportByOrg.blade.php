@extends('main_no_nav2')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
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

<div class="alert alert-info">
	<h5 class="text-bold">Report generated using the following parameters:</h5>
	Organization <input value="{{ $org }}" name="DEPT" readonly/>
	@if ($param == 'year')
	Year <input value="{{ $year }}" name="year" readonly/>
	@elseif( $param == 'Term')
	Term Code <input value="{{ $term }}" name="Term" readonly/>
	@endif
</div>

<div class="reports-section">
	<div class="box box-success">
		<div class="overlay">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
		<div class="box-body">

			<table id="sampol" class="table table-striped no-wrap" width="100%">
				<thead>
					<tr>
						<th>Term</th>
						<th>Language</th>
						<th>Description</th>
						<th>Price USD</th>
						<th>Duration</th>
						<th>Organization</th>
						<th>Index No.</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>RESULT</th>
						<th>Days Present</th>
						<th>Days Excused</th>
						<th>Days Absent</th>
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
						<th>Index No.</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>RESULT</th>
						<th>Days Present</th>
						<th>Days Excused</th>
						<th>Days Absent</th>
						<th>Cancel Date</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

@stop

@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
<script type="text/javascript" src="{{ asset('js/reportByOrg.js') }}"></script>

<script>
$(document).ready(function() {

    getAllStudents();
	    
	function getAllStudents() {
            
			const year = $('input[name="year"]').val();
	    	const Term = $('input[name="Term"]').val();
	    	const DEPT = $('input[name="DEPT"]').val();
			
			let promises = [];	

			promises.push(
				$.ajax({
					url: "{{ route('report-by-org-admin') }}",
					type: 'GET',
					dataType: 'json',
					data: {year: year, Term: Term, DEPT: DEPT},
				})
				.then(function(data) {
					console.log(data)
					// console.log(data.data['0'])
					// getSumOfPrices(data);
					assignToEventsColumns(data);
					// console.log(data.data)
					// var data = jQuery.parseJSON(data.data);
					// console.log(data)
					$(".overlay").removeAttr('style');
				})
				.fail(function(data) {
					console.log(data);
				})

			);
			$.when.apply($.ajax(), promises).then(function() {
		        $(".overlay").fadeOut(600);
		    });

			$.getScript("/js/reportByOrg.js");	
	}

});
</script>

@stop