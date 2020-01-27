@extends('admin.admin')
@section('customcss')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
	@include('admin.partials._termSessionMsg')
	<div class="row">
		<div class="col-md-12">
			<span><h2><i class="fa fa-calendar-o"></i> Course + Schedule Before Enrolment</h2></span>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('course-schedule.create') }}" class="btn btn-block btn-primary btn-h1-spacing"><i class="fa fa-plus"></i> Create</a>
		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>
</div>
@if (Session::has('Term'))
<div class="row">
	<div class="col-md-12">
		{{-- <div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div> --}}
		<div class="filtered-table table-responsive">
			<table id="sampol" class="table table-bordered table-striped">
				<thead>
					<th>Operation</th>
					<th>id</th>
					<th>Term</th>
					{{-- <th>CS Code</th> --}}
					<th>Code - Course Name</th>
					<th>Day</th>
					<th>Time</th>
					<th>Format</th>
					<th>Duration</th>
					<th>Price CHF</th>
				</thead>

				<tbody>
					@foreach($course_schedule as $class)
						<tr>
							<td>
								<form method="POST" action="{{ route('course-schedule.destroy', $class->id)}}">
									<input type="submit" value="Delete" class="btn btn-sm btn-danger delete-record" />
									<input type="hidden" name="_token" value="{{ Session::token() }}">
									{{ method_field('DELETE') }}
								</form>
							</td>
							{{-- <td><a href="{{ route('course-schedule.edit', $class->id)}}" class="btn btn-default btn-sm">Edit</a></td> --}}
							<th>{{ $class->id }}</th>
							<th>{{ $class->Te_Term }}</th>
							{{-- <th>{{ $class->cs_unique }}</th> --}}
							<td>{{ $class->Te_Code_New}} - {{ $class->course->Description }}</td>
							<td>
								@if(empty( $class->schedule_id ))
								null
								@else 
								{{ $class->scheduler->begin_day }}
								@endif
							</td>
							<td>
								@if(empty( $class->schedule_id ))
								null
								@else 
								{{ $class->scheduler->time_combination }}
								@endif
							</td>
							<td>
								@if ($class->courseformat)
								{{ $class->courseformat->format_name_en }}
								@endif
							</td>
							<td>
								@if ($class->courseduration)
								{{ $class->courseduration->duration_name_en}}
								@endif
							</td>
							<td>
								@if ($class->prices)
									{{ $class->prices->price }}	
								@endif
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		</div>	
	</div>
</div>
@endif

@stop

@section('java_script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script>
// $('#sampol').DataTable({
// 	"fixedHeader": true,
// 	"deferRender": true,
// 	"dom": 'B<"clear">lfrtip',
// 	"buttons": [
// 			'copy', 'csv', 'excel', 'pdf'
// 		],
// });
// $(".preloader2").fadeOut(600);
</script>
<script>
$('input.delete-record').on('click', function(event) {
	var r = confirm("You are about to delete a record. Are you sure?");
	if (r == false) {
		event.preventDefault();
	}
});
</script>
@stop