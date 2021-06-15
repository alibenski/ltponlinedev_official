<div class="modal-body">
    <p>You have chosen the following time schedule(s) for this course:</p>
        @if(!empty($schedules))
            <ul>
              @foreach($schedules as $value)
                <p><span><i class="fa fa-clock-o fa-spin fa-lg" style="margin-right: 10px;" aria-hidden="true"></i></span><strong>{{ $value->schedule->name }}</strong></p>
                <p>Organization: @if(is_null($value->DEPT)) - @elseif($value->DEPT == 999) SPOUSE @else {{ $value->DEPT }} @endif</p>
                <p>HR Staff and Development Section Approval:
					@if(is_null($value->is_self_pay_form))
						@if(in_array($value->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
							<span id="status" class="badge badge-info margin-label">
							N/A - Non-paying organization</span>
						@else
							@if(is_null($value->approval) && is_null($value->approval_hr))
							<span id="status" class="badge badge-warning margin-label">
							Pending Approval</span>
							@elseif($value->approval == 0 && (is_null($value->approval_hr) || isset($value->approval_hr)))
							<span id="status" class="badge badge-danger margin-label">
							N/A - Disapproved by Manager</span>
							@elseif($value->approval == 1 && is_null($value->approval_hr))
							<span id="status" class="badge badge-warning margin-label">
							Pending Approval</span>
							@elseif($value->approval == 1 && $value->approval_hr == 1)
							<span id="status" class="badge badge-success margin-label">
							Approved</span>
							@elseif($value->approval == 1 && $value->approval_hr == 0)
							<span id="status" class="badge badge-danger margin-label">
							Disapproved</span>
							@endif
						@endif
					@else
					<span id="status" class="badge badge-info margin-label">
					N/A - Self-Payment</span>
					@endif
                </p>
                <p>
                	Language Secretariat Payment Validation: 
					@if(is_null($value->is_self_pay_form))
					<span id="status" class="badge badge-info margin-label">N/A</span>
					@else
						@if($value->selfpay_approval === 1)
						<span id="status" class="badge badge-success margin-label">Approved</span>
						@elseif($value->selfpay_approval === 2)
						<span id="status" class="badge badge-warning margin-label">Pending Valid Document</span>
						@elseif($value->selfpay_approval === 0)
						<span id="status" class="badge badge-danger margin-label">Disapproved</span>
						@else 
						<span id="status" class="badge badge-info margin-label">Waiting</span>
						@endif
					@endif
                </p>
              @endforeach
            </ul>
        @endif
    <p class="alert alert-warning"><strong>Note:</strong> Please note that the class schedules are <strong><u><span class="text-danger">not</span></u></strong> final and there is a possibility that they could change upon further evaluation of the Language Secretariat.</p>    
</div>
