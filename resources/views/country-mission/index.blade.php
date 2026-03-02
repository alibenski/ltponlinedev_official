@extends(Auth::user()->can('M&C Administration (limited)') ? 'admin.no_sidebar_admin' : 'admin.admin')

@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h2><i class="fa fa-globe"></i> <span>Mission Offices and Emails</span></h2>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-12">
            <div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
		    <div class="filtered-table table-responsive ">
			<table id="sampol" class="table table-striped">
				<thead>
					<th>Mission Office</th>
					<th>Email</th>
				</thead>

				<tbody>
					@foreach($missionOffices as $missionOffice)
						<tr  class="item{{$missionOffice->email}}">
							<td>{{ $missionOffice->name }}</td>
							<td>{{ $missionOffice->email }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		    </div>		
		</div>
	</div>

@stop

@section('java_script')
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
        "oLanguage": {
            "sSearch": "Search Filter:"
            }
    });
    $(".preloader2").fadeOut(600);
</script>
@stop