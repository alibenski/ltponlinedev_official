@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1><i class="fa fa-book"></i> <span>Course Catalogue</span></h1>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('courses.create') }}" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create Course</a>

		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>#</th>
					<th>Code</th>
					<th>Course Name</th>
					<th>Language</th>
					<th>Schedule</th>
				</thead>

				<tbody>
					@foreach($courses as $course)
						
						<tr>
							<th>{{ $course->id }}</th>
							<td>{{ $course->Te_Code_New }}</td>
							<td>{{ $course->Description }}</td>
							<td>{{ $course->language->name }}</td>
							<td>
					            @if(empty($exists))
					            <span class="label label-danger">none</span>
					            @else
					            <!-- Variable course refers to schedule function defined as variable schedule -->
					            @foreach($course->schedule as $schedule)
					                <span class="label label-default">{{ $schedule->name }}</span>
					            @endforeach
					            @endif
							</td>
							<td><a href="{{ route('courses.edit', $course->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $courses->links() }}		
		</div>
	</div>
</div>
@endsection