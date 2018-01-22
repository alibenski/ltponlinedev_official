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
					<th>Schedule</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($classrooms as $classroom)
						
						<tr>
							<th>{{ $classroom->Code }}</th>
							<td>{{ $classroom->course->Description }}</td>
							<td>
								@if(empty( $classroom->scheduler->name ))
								null
								@else 
								{{ $classroom->scheduler->name }}
								@endif
							</td>
							<td><a href="{{ route('classrooms.edit', $classroom->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $classrooms->links() }}		
		</div>
	</div>
</div>
@stop