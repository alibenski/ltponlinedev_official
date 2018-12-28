@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1><i class="fa fa-pied-piper-alt"></i> <span>Teachers</span></h1>
		</div>
		
		<div class="col-md-2">
			<a href="#" class="btn btn-block btn-primary btn-h1-spacing">Create Teacher</a>

		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>Last Name</th>
					<th>First Name</th>
					<th>Email</th>
					<th>Language</th>
					<th>Active</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($teachers as $teacher)
						
						<tr>
							<td>{{ $teacher->Tch_Lastname }}</td>
							<td>{{ $teacher->Tch_Firstname }}</td>
							<td>{{ $teacher->email }}</td>
							<td>{{ $teacher->Tch_L }}</td>
							<td>@if($teacher->In_Out == 1) <i class="fa fa-check text-success"></i>@else <i class="fa fa-remove text-danger"></i>@endif</td>
							<td><a href="#" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>	
		</div>
	</div>
</div>
@endsection