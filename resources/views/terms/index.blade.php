@extends('admin.admin')

@section('content')
<div class="col-lg-10 col-lg-offset-1">
	<h1><i class="fa fa-lg fa-snowflake-o"></i> All Semester Terms <a href="{{ route('terms.create') }}" class="btn btn-success btn-h1-spacing pull-right" style="margin: 1px;">Create</a></h1>
    <hr>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<th>Term Code</th>
					<th>Term Name</th>
					<th>Next Term Code</th>
					<th>Enrolment Date Begin</th>
					<th>Enrolment Date End</th>
					<th>Cancellation Date Limit</th>
					<th></th>
				</thead>

				<tbody>
					@foreach($terms as $term)
						
						<tr>
							<th>{{ $term->Term_Code }}</th>
							<td>{{ $term->Term_Name }}</td>
							<td>{{ $term->Term_Next }}</td>
							<td>{{ $term->Enrol_Date_Begin }}</td>
							<td>{{ $term->Enrol_Date_End }}</td>
							<td>{{ $term->Cancel_Date_Limit }}</td>
							<td><a href="{{ route('terms.index', $term->id)}}" class="btn btn-info pull-left">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $terms->links() }}	
		</div>
</div>
@stop