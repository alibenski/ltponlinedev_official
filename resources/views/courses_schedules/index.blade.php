@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<span><h2><i class="fa fa-calendar"></i> Course + Schedule Before Enrolment: {{ $next_term->Term_Code.' - '.$next_term->Term_Name.' - '.$next_term->Comments }}</h2></span>
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
					<th>CS Code</th>
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