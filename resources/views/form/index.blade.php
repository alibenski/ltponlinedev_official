@extends('main')
@section('tabtitle', '| All Forms')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<h1>All Forms Submitted</h1>
		</div>
		
		<div class="col-md-2">
			<a href="" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Button</a>

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
					<th>Code</th>
					<th>Manager Email</th>
					<th>Language</th>
					<th>Course</th>
					<th>Term</th>
					<th></th>
				</thead>

				<tbody>
					@foreach($repos as $repo)
						
						<tr>
							<th>{{ $repo->id }}</th>
							<th>{{ $repo->CodeIndexID }}</th>
							<th>{{ $repo->EMAIL }}</th>
							<th>{{ $repo->Language_Code }}</th>
							<th>{{ $repo->Course_Code }}</th>
							<th>{{ $repo->Term_Code }}</th>
							<td><a href="{{ route('myform.edit', $repo->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			{{ $repos->links() }}
		</div>
	</div>
</div>
@stop