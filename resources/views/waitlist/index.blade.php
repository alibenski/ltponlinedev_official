@extends('admin.admin')

@section('content')
<h2>Waitlisted Students</h2>
<div class="row">
	<div class="col-sm-10 class-sm-offset-2">
		<table class="table">
			<thead>
				<th>#</th>
				<th>INDEXID</th>
				<th>Name</th>
				<th>Term</th>
				<th>Requested Course</th>
				<th>Requested Schedule</th>
				<th>Operation</th>
			</thead>

			<tbody>
				@foreach($students as $student)
					
					<tr  class="item{{$student->id}}">
						<th>{{ $student->id }}</th>
						<td>{{ $student->INDEXID }}</td>
						<td>{{ $student->users->name }}</td>
						<td>{{ $student->Term }}</td>
						<td>{{ $student->courses->EDescription }}</td>
						<td>{{ $student->schedule->name }}</td>
						{{-- <td>
							<button>Assign</button>
						</td> --}}
					</tr>
				@endforeach

			</tbody>
		</table>	
	</div>
</div>

@stop