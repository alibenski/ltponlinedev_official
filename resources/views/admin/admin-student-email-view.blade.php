@extends('admin.admin')

@section('customcss')
    {{-- <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')
@include('admin.partials._termSessionMsg')
@if (Session::has('Term'))
	<div class="table-section">
		<div id="loading">
			<div class="preloader2">
				<h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3>
			</div>
		</div>
		<h3>Viewing All Current Students</h3>
		<br>
		<table id="sampol" class="table table-striped no-wrap" width="100%">
			<thead>
				<tr>
					<th>Term</th>
					<th>Name</th>
					<th>Profile</th>
					<th>Email</th>
					<th>Organization</th>
					<th>Language</th>
					<th>Description</th>
					<th>Teacher</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Term</th>
					<th>Name</th>
					<th>Profile</th>
					<th>Email</th>
					<th>Organization</th>
					<th>Language</th>
					<th>Description</th>
					<th>Teacher</th>
				</tr>
			</tfoot>
		</table>
	</div>
@endif
<input type="hidden" id="term" value="{{ Session::get('Term') }}">
@stop

@section('java_script')
{{-- <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script>
	$(document).ready(function () {
		const term = $('input#term').val();
		const promises = [];
		if (term) {
			promises.push(
			$.ajax({
				type: "get",
				url: "{{ route('get-admin-all-current-student-in-term')}}",
				data: {term:term},
				dataType: "json",
			})
			.then(function(data) {
				console.log(data)
				assignToEventsColumns(data);
			})
			.fail(function(data) {
				console.log(data);
			}));
		}
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
				"scrollX": true,
				"responsive": false,
				"orderCellsTop": true,
				"fixedHeader": true,
				"pagingType": "full_numbers",
				"bAutoWidth": false,
				"aaData": data,
				"columns": [
						{ "data": "Term" }, 
						{ "data": "users.name" }, 
						{ "data": "users.profile" }, 
						{ "data": "users.email" }, 
						// { "data": "users.sddextr.DEPT" }, 
						{ "data": "DEPT" }, 
						{ "data": "languages.name" }, 
						{ "data": "courses.Description" },  
						{ "data": "classrooms.teachers.Tch_Name" }
							],
			})
		}
	});

</script>

@stop