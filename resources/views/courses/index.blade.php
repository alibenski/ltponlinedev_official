@extends('main')
@section('tabtitle', '| All Courses')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<h1>All Language Courses</h1>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('courses.create') }}" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create Course</a>

		</div>
		<div class="col-md-10">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>#</th>
					<th>Course Name</th>

				</thead>

				<tbody>
					@foreach($courses as $course)
						
						<tr>
							<th>{{ $course->Te_Code }}</th>
							<td>{{ $course->Description }}</td>

							<td><a href="{{ route('courses.edit', $course->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>

			</div>
	</div>
</div>
@endsection