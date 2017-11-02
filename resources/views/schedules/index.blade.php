@extends('main')
@section('tabtitle', '| All Courses')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<h1>All Schedules</h1>
		</div>
		
		<div class="col-md-2">
			<a href=" " class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create</a>

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
					<th>Description</th>
					<th>Begin Day</th>
					<th>End Day</th>
					<th>Begin Time</th>
					<th>End Time</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($schedules as $schedule)
						
						<tr>
							<th>{{ $schedule->id }}</th>
							<td>{{ $schedule->name }}</td>
							<td>{{ $schedule->begin_day }}</td>
							<td>{{ $schedule->end_day }}</td>
							<td>{{ $schedule->begin_time }}</td>
							<td>{{ $schedule->end_time }}</td>
							<td><a href="{{ route('schedules.edit', $schedule->id) }} " class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
				
		</div>
	</div>
</div>
@endsection