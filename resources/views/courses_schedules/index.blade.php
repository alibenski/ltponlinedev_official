@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Course + Schedule Before Enrolment</h1>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('course-schedule.create') }}" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create</a>
		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>Code</th>
					<th>Course Name</th>
					<th>Day</th>
					<th>Time</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($course_schedule as $class)
						<tr>
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
							<td><a href="{{ route('course-schedule.edit', $class->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $course_schedule->links() }}		
		</div>
	</div>
</div>
@stop