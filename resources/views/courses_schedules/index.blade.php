@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<span><h2><i class="fa fa-calendar-o"></i> Course + Schedule Before Enrolment</h2></span>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('course-schedule.create') }}" class="btn  btn-block btn-primary btn-h1-spacing">Create</a>
		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="filtered-table table-responsive">
				<table class="table table-bordered table-striped">
					<thead>
						{{-- <th>Operation</th> --}}
						<th>Term</th>
						<th>CS Code</th>
						<th>Course Name</th>
						<th>Day</th>
						<th>Time</th>
					</thead>

					<tbody>
						@foreach($course_schedule as $class)
							<tr>
								{{-- <td><a href="{{ route('course-schedule.edit', $class->id)}}" class="btn btn-default btn-sm">Edit</a></td> --}}
								<th>{{ $class->Te_Term }}</th>
								<th>{{ $class->cs_unique }}</th>
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
							</tr>
						@endforeach

					</tbody>
				</table>
			</div>
			{{ $course_schedule->links() }}		
		</div>
	</div>
</div>
@stop