<div class="modal-body">
    <p>You have chosen the following time schedule(s) for this course:</p>
        @if(!empty($schedules))
            <ul>
              @foreach($schedules as $value)
                <p><span><i class="fa fa-clock-o fa-spin fa-lg" style="margin-right: 10px;" aria-hidden="true"></i></span><strong>{{ $value->schedule->name }}</strong></p>
                <p>Organization: @if(is_null($value->DEPT)) - @else {{ $value->DEPT }} @endif</p>
                <p>Manager's Approval: 
					@if($value->is_self_pay_form == 1)
					<span id="status" class="label label-success margin-label">
					N/A - Self Payment
					@elseif(is_null($value->approval))
					<span id="status" class="label label-warning margin-label">
					Pending Approval
					@elseif($value->approval == 1)
					<span id="status" class="label label-success margin-label">
					Approved
					@elseif($value->approval == 0)
					<span id="status" class="label label-danger margin-label">
					Disapproved
					@endif
                </p>
                <p>HR Staff and Development Section Approval:
					@if($value->is_self_pay_form == 1)
					<span id="status" class="label label-success margin-label">
					N/A - Self Payment
					{{-- Add more organizations in array below --}}
					@elseif(in_array(Auth::user()->sddextr->DEPT, ["UNOG", "JIU"]))
					<span id="status" class="label label-info margin-label">
					N/A
					@elseif(is_null($value->approval) && is_null($value->approval_hr))
					<span id="status" class="label label-warning margin-label">
					Pending Approval
					@elseif($value->approval == 0 && (is_null($value->approval_hr) || isset($value->approval_hr)))
					<span id="status" class="label label-danger margin-label">
					N/A - Disapproved by Manager
					@elseif($value->approval == 1 && is_null($value->approval_hr))
					<span id="status" class="label label-warning margin-label">
					Pending Approval
					@elseif($value->approval == 1 && $value->approval_hr == 1)
					<span id="status" class="label label-success margin-label">
					Approved
					@elseif($value->approval == 1 && $value->approval_hr == 0)
					<span id="status" class="label label-danger margin-label">
					Disapproved
					@endif
                </p>
              @endforeach
            </ul>
        @endif
    <p class="alert alert-warning"><strong>Note:</strong> Please note that the class schedules are not absolute and there is a possibility that they could change upon further evaluation of the Language Secretariat.</p>    
</div>
