@extends('admin.admin')

@section('customcss')
    {{-- <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="table-section">
	<div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
	<table id="sampol" class="table table-striped no-wrap" width="100%">
		<thead>
			<tr>
				<th>Term</th>
				<th>Name</th>
				<th>Email</th>
				<th>Language</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($query_students_current_term as $el)
			<tr>
				<td>{{ $el->Term }}</td>
				<td>{{ $el->users->name }}</td>
				<td>{{ $el->users->email }}</td>
				<td>{{ $el->languages->name }}</td>
				<td>{{ $el->courses->EDescription }}</td>
			</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th>Term</th>
				<th>Name</th>
				<th>Email</th>
				<th>Language</th>
				<th>Description</th>
			</tr>
		</tfoot>
	</table>
</div>

@stop

@section('java_script')
{{-- <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script>
	$('#sampol').DataTable({
		"fixedHeader": true,
		"deferRender": true,
    	"dom": 'B<"clear">lfrtip',
    	"buttons": [
		        'copy', 'csv', 'excel', 'pdf'
		    ],
	});
	$(".preloader2").fadeOut(600);
</script>

@stop