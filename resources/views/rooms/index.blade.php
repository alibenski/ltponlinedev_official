@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1><i class="fa fa-building-o"></i> <span>Rooms</span></h1>
		</div>
		
		<div class="col-md-2">
			<a href="#" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create Room</a>

		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>Name</th>
					<th>Type</th>
					<th>Location</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($rooms as $room)
						
						<tr>
							<th>{{ $room->Rl_Room }}</th>
							<td>{{ $room->Rl_Type }}</td>
							<td>{{ $room->Rl_Location }}</td>
							<td><a href="#" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>	
		</div>
	</div>
</div>
@endsection