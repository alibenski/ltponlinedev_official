@extends('main')
@section('tabtitle', '| Course + Schedule')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<h1>All Course + Schedule</h1>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('classrooms.create') }}" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create Classes</a>

		</div>
		<div class="col-md-10">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>Class Code</th>
					<th>Course Name</th>
					<th>Schedule</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($classrooms as $classroom)
						
						<tr>
							<th>{{ $classroom->Code }}</th>
							<td>{{ $classroom->course->Description }}</td>
							<td>{{ $classroom->scheduler->name }}</td>
							<td><a href="{{ route('classrooms.edit', $classroom->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $classrooms->links() }}		
		</div>
	</div>
</div>
@endsection