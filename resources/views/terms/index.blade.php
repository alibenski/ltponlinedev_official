@extends('admin.admin')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<span><h2><i class="fa fa-lg fa-snowflake-o"></i> All Semester Terms</h2></span>
		</div>
		
		<div class="col-md-2">
			<a href="" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create</a>

		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>Term Code</th>
					<th>Term Name</th>
					<th>Next Term Code</th>
					<th></th>
					<th></th>
					<th></th>
				</thead>

				<tbody>
					@foreach($terms as $term)
						
						<tr>
							<th>{{ $term->Term_Code }}</th>
							<td>{{ $term->Term_Name }}</td>
							<td>{{ $term->Term_Next }}</td>
							<td><a href="{{ route('terms.index', $term->id)}}" class="btn btn-default btn-sm">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $terms->links() }}	
			</div>
	</div>
</div>
@stop