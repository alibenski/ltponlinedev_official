@extends('admin.admin')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<span><h2><i class="fa fa-calendar"></i> Placement Test Schedule</h2></span>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('placement-schedule.create') }}" class="btn btn-block btn-primary btn-h1-spacing">Create</a>
		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>Language</th>
					<th>Term</th>					
					<th>Date</th>
					<th>Date End (Online)</th>
					<th>Time</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($placement_schedule as $pschedule)
						<tr>
							<th>{{ $pschedule->language_id }}</th>
							<td>{{ $pschedule->term }}</td>
							<td>{{ $pschedule->date_of_plexam}}</td>
							<td>{{ $pschedule->date_of_plexam_end}}</td>
							<td>{{ $pschedule->time_of_plexam}}</td>
							<td><a href="#" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $placement_schedule->links() }}		
		</div>
	</div>
</div>
@stop