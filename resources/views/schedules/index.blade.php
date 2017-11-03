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
			<a href="{{ route('schedules.create') }}" class="btn btn-block btn-primary btn-h1-spacing" style="margin-top: 20px;">Create</a>

		</div>
		<div class="col-md-10">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
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
							<td>{{ $schedule->name }}</td>
							<td>{{ $schedule->begin_day }}</td>
							<td>
								@if(empty($schedule->end_day))
					            <span>Only</span>
					            @else
					            	<span>{{ $schedule->end_day }}</span>
					            @endif
							</td>
							<td>
								@if(empty($schedule->begin_time))
					            <span class="label label-danger">none</span>
					            @else
					            	<span class="label label-default">{{ date('h:i:sa', strtotime($schedule->begin_time)) }}</span>
					            @endif
							</td>
							<td>
								@if(empty($schedule->end_time))
					            <span class="label label-danger">none</span>
					            @else
					            	<span class="label label-default">{{ date('h:i:sa', strtotime($schedule->end_time)) }}</span>
					            @endif
							</td>
							<td><a href="{{ route('schedules.edit', $schedule->id) }} " class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
				
		</div>
	</div>
</div>
@endsection