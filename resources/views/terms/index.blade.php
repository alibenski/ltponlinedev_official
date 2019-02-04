@extends('admin.admin')

@section('content')
<div class="col-lg-12">
	<h1><i class="fa fa-lg fa-snowflake-o"></i> All Semester Terms </h1>

	<div class="col-md-2">
			<a href="{{ route('terms.create') }}" class="btn btn-block btn-h1-spacing btn-primary btn-h1-spacing">Create Term</a>
	</div>
	<div class="row col-md-12">
    <hr>		
	</div>
    <div class="row col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<th>Term Code</th>
					<th>Term Name</th>
					<th>Term Name Fr</th>
					<th>Term Begin Date</th>
					<th>Term End Date</th>
					<th>Enrolment Date Begin</th>
					<th>Enrolment Date End</th>
					<th>Cancellation Date Limit</th>
					{{-- <th>Approval Date Limit</th> --}}
					<th>Approval Date Limit HR</th>
					<th>Season</th>
					{{-- <th>Remind Mgr</th> --}}
					<th>Remind HR</th>
					<th>Operation</th>
					<th>Updated By</th>
				</thead>

				<tbody>
					@foreach($terms as $term)
						
						<tr>
							<th>{{ $term->Term_Code }}</th>
							<td>{{ $term->Term_Name }}</td>
							<td>{{ $term->Term_Name_Fr }}</td>
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
							{{-- <td>
							@if(empty($term->Approval_Date_Limit))
					            <span class="label label-danger">NONE</span>
							@else
								{{ date('d M Y - H:ia', strtotime($term->Approval_Date_Limit)) }}
							@endif
							</td> --}}
							<td>
							@if(empty($term->Approval_Date_Limit_HR))
					            <span class="label label-danger">NONE</span>
							@else
								{{ date('d M Y - H:ia', strtotime($term->Approval_Date_Limit_HR)) }}
							@endif
							</td>
							<td>{{ $term->Comments }}</td>
							{{-- <td>{{ $term->Remind_Mgr_After }} days</td> --}}
							<td>{{ $term->Remind_HR_After }} days</td>
							<td><a href="{{ route('terms.edit', $term->Term_Code)}}" class="btn btn-info pull-left">Edit</a></td>
							<td>@if(empty($term->users)) @else {{ $term->users->name }} @endif</td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $terms->links() }}	
		</div>
    </div>
</div>
@stop