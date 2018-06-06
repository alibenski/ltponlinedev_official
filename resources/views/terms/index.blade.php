@extends('admin.admin')

@section('content')
<div class="col-lg-12">
	<h1><i class="fa fa-lg fa-snowflake-o"></i> All Semester Terms <a href="{{ route('terms.create') }}" class="btn btn-success btn-h1-spacing pull-right" style="margin: 1px;">Create</a></h1>
    <hr>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<th>Term Code</th>
					<th>Term Name</th>
					<th>Term Begin Date</th>
					<th>Term End Date</th>
					<th>Enrolment Date Begin</th>
					<th>Enrolment Date End</th>
					<th>Cancellation Date Limit</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($terms as $term)
						
						<tr>
							<th>{{ $term->Term_Code }}</th>
							<td>{{ $term->Term_Name }}</td>
							<td>{{ date('d M Y', strtotime($term->Term_Begin)) }}</td>
							<td>{{ date('d M Y', strtotime($term->Term_End)) }}</td>
							<td>
							@if(empty($term->Enrol_Date_Begin))
					            <span class="label label-danger">NONE</span>
							@else
								{{ date('d M Y - H:ia', strtotime($term->Enrol_Date_Begin)) }}
							@endif
							</td> 
							<td>
							@if(empty($term->Enrol_Date_End))
					            <span class="label label-danger">NONE</span>
							@else
								{{ date('d M Y - H:ia', strtotime($term->Enrol_Date_End)) }}
							@endif
							</td>
							<td>
							@if(empty($term->Cancel_Date_Limit))
					            <span class="label label-danger">NONE</span>
							@else
								{{ date('d M Y - H:ia', strtotime($term->Cancel_Date_Limit)) }}
							@endif
							</td>
							<td><a href="{{ route('terms.edit', $term->Term_Code)}}" class="btn btn-info pull-left">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $terms->links() }}	
		</div>
</div>
@stop