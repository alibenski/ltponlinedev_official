@extends('admin.no_sidebar_admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="alert alert-warning col-sm-10 col-sm-offset-1">
	<h4 class="text-center"><strong>Filtered Placement Test Forms</strong></h4>
</div>
	@if(Request::input('Term'))<h4 class="alert alert-info pull-right">Currently Viewing: {{ Request::input('Term') }} </h4>@else @endif
@if(is_null($placement_forms))

@else
{{ $placement_forms->links() }}
<div class="table-responsive col-sm-12 filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	        	<th>Operation</th>
	            <th>Name</th>
	            <th>Convoked?</th>
	            <th>Assigned Course?</th>
	            <th>Organization</th>
	            <th>Language</th>
	            {{-- <th>Student Cancelled?</th> --}}
	            <th>Exam Date</th>
	            <th>Manager Approval</th>
	            <th>HR Approval</th>
	            {{-- <th>ID Proof</th>
	            <th>Payment Proof</th> --}}
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($placement_forms as $form)
			<tr>
				<td>
					{{-- <button class="show-modal btn btn-warning" data-index="{{$form->INDEXID}}" data-tecode="{{$form->Te_Code}}" data-term="{{$form->Term}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button> --}}
                    <a href="{{ route('placement-form.edit', [$form->id]) }}" target="_blank" class="btn btn-warning"><span class="glyphicon glyphicon-eye-open"></span> Show</a> 
                </td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>
				@if(is_null($form->convoked)) - @else 
					@if($form->convoked == 1) Yes
					@elseif($form->convoked == 0) No 
					@endif
				@endif
				</td>
				<td>
				@if(empty($form->assigned_to_course)) - @else 
					@if($form->assigned_to_course == 1) Yes 
					@else No 
					@endif
				@endif
				</td>
				<td>
				@if(empty($form->DEPT)) None @else {{ $form->DEPT }}  @endif
				</td>
				<td>{{ $form->L }}</td>
				{{-- <td>
					@if( is_null($form->cancelled_by_student))
					@else <span id="status" class="label label-danger margin-label">YES</span>
					@endif
				</td> --}}
				<td>@if ($form->L === "F") Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} @else {{ $form->placementSchedule->date_of_plexam }} @endif</td>
				<td>
					@if($form->is_self_pay_form == 1)
					<span id="status" class="label label-info margin-label">
					N/A - Self Payment</span>
					@elseif(is_null($form->approval))
					<span id="status" class="label label-warning margin-label">
					Pending Approval</span>
					@elseif($form->approval == 1)
					<span id="status" class="label label-success margin-label">
					Approved</span>
					@elseif($form->approval == 0)
					<span id="status" class="label label-danger margin-label">
					Disapproved</span>
					@endif
				</td>
				<td>
					@if(is_null($form->is_self_pay_form))
						@if(in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
							<span id="status" class="label label-info margin-label">
							N/A - Non-paying organization</span>
						@else
							@if(is_null($form->approval) && is_null($form->approval_hr))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($form->approval == 0 && (is_null($form->approval_hr) || isset($form->approval_hr)))
							<span id="status" class="label label-danger margin-label">
							N/A - Disapproved by Manager</span>
							@elseif($form->approval == 1 && is_null($form->approval_hr))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($form->approval == 1 && $form->approval_hr == 1)
							<span id="status" class="label label-success margin-label">
							Approved</span>
							@elseif($form->approval == 1 && $form->approval_hr == 0)
							<span id="status" class="label label-danger margin-label">
							Disapproved</span>
							@endif
						@endif
					@else
					<span id="status" class="label label-info margin-label">
					N/A - Self Payment</span>
					@endif
				</td>
				{{-- <td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
				</td>
				<td>
				@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
				</td> --}}
				<td>{{ $form->created_at}}</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
	{{ $placement_forms->links() }}
</div>
@endif
@stop

@section('java_script')
<script language="javascript">
// setTimeout(function(){
//    window.location.reload(1);
// }, 3000);
</script>
<script language="javascript">
	window.setInterval(function(){
    if(localStorage["update"] == "1"){
        localStorage["update"] = "0";
        window.location.reload();
    }
}, 500);
</script>
@stop