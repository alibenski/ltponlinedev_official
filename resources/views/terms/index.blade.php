@extends('main')
@section('tabtitle', '| All Forms')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10">
			<h1>All Semester Terms</h1>
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
					<th>Term Code</th>
					<th>Term Name</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</thead>

				<tbody>
					@foreach($terms as $term)
						
						<tr>
							<th>{{ $term->Term_Code }}</th>
							<td>{{ $term->Term_Name }}</td>
							<td><a href="{{ route('terms.index', $term->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>

			</div>
	</div>
</div>
@stop