@extends('admin.admin')

@section('content')
<div class="col-lg-12">
	<h1><i class="fa fa-lg fa-snowflake-o"></i> All Semester Terms </h1>

	<div class="col-md-1">
			<a href="{{ route('terms.create') }}" class="btn btn-block btn-h1-spacing btn-primary btn-h1-spacing">Create Term</a>
	</div>
    <hr>
    <div class="row col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<th>Term Code</th>
					<th>Term Name</th>
					<th>Term Begin Date</th>
					<th>Term End Date</th>
					<th>Previous Term</th>
					<th>Next Term</th>
					<th>Enrolment Date Begin</th>
					<th>Enrolment Date End</th>
					<th>Cancellation Date Limit</th>
					<th>Approval Date Limit</th>
					<th>Season</th>
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
							@if(empty($term->Term_Prev))
					            <span class="label label-danger">NONE</span>
							@else
								{{ $term->Term_Prev }}
							@endif
							</td>
							<td> 
							@if(empty($term->Term_Next))
					            <span class="label label-danger">NONE</span>
							@else
								{{ $term->Term_Next}}
							@endif
							</td> 
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
							<td>
							@if(empty($term->Approval_Date_Limit))
					            <span class="label label-danger">NONE</span>
							@else
								{{ date('d M Y - H:ia', strtotime($term->Approval_Date_Limit)) }}
							@endif
							</td>
							<td>{{ $term->Comments }}</td>
							<td><a href="{{ route('terms.edit', $term->Term_Code)}}" class="btn btn-info pull-left">Edit</a></td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $terms->links() }}	
		</div>
    </div>
</div>
@stop