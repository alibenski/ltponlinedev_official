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
					<th>Title</th>
					<th>Last Name</th>
					<th>First Name</th>
					<th>Language</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($teachers as $teacher)
						
						<tr>
							<th>{{ $teacher->Tch_Title }}</th>
							<td>{{ $teacher->Tch_Lastname }}</td>
							<td>{{ $teacher->Tch_Firstname }}</td>
							<td>{{ $teacher->Tch_L }}</td>
							<td><a href="#" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>	
		</div>
	</div>
</div>
@endsection