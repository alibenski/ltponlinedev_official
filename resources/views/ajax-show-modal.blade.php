<div class="modal-body">
    <p>The student has chosen the following time schedule(s):</p>
        @if(!empty($schedules))
            <ul>
              @foreach($schedules as $value)
                <p><span><i class="fa fa-clock-o fa-spin fa-lg" style="margin-right: 10px;" aria-hidden="true"></i></span><strong>{{ $value->schedule->name }}</strong></p>
                <p>Organization: @if(is_null($value->DEPT)) - @elseif($value->DEPT == 999) SPOUSE @else {{ $value->DEPT }} @endif</p>
                <p>Supervisor's email: @if($value->is_self_pay_form == 1) N/A - Self Payment @else {{$value->mgr_email}} @endif</p>
                <p>Supervisor's Approval: 
					@if($value->is_self_pay_form == 1)
					<span id="status" class="label label-info margin-label">
					N/A - Self Payment</span>
					@elseif(is_null($value->approval))
					<span id="status" class="label label-warning margin-label">
					Pending Approval</span>
					@elseif($value->approval == 1)
					<span id="status" class="label label-success margin-label">
					Approved</span>
					@elseif($value->approval == 0)
					<span id="status" class="label label-danger margin-label">
					Disapproved</span>
					@endif
                </p>
                <p>HR Staff and Development Section Approval:
					@if(is_null($value->is_self_pay_form))
						@if(in_array($value->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
							<span id="status" class="label label-info margin-label">
							N/A - Non-paying organization</span>
						@else
							@if(is_null($value->approval) && is_null($value->approval_hr))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($value->approval == 0 && (is_null($value->approval_hr) || isset($value->approval_hr)))
							<span id="status" class="label label-danger margin-label">
							N/A - Disapproved by Manager</span>
							@elseif($value->approval == 1 && is_null($value->approval_hr))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($value->approval == 1 && $value->approval_hr == 1)
							<span id="status" class="label label-success margin-label">
							Approved</span>
							@elseif($value->approval == 1 && $value->approval_hr == 0)
							<span id="status" class="label label-danger margin-label">
							Disapproved</span>
							@endif
						@endif
					@else
					<span id="status" class="label label-info margin-label">
					N/A - Self Payment</span>
					@endif
                </p>
                <p>
                	Language Secretariat Payment Validation: 
					@if(is_null($value->is_self_pay_form))
					<span id="status" class="label label-info margin-label">N/A</span>
					@else
						@if($value->selfpay_approval === 1)
						<span id="status" class="label label-success margin-label">Approved</span>
						@elseif($value->selfpay_approval === 2)
						<span id="status" class="label label-warning margin-label">Pending Approval</span>
						@elseif($value->selfpay_approval === 0)
						<span id="status" class="label label-danger margin-label">Disapproved</span>
						@else 
						<span id="status" class="label label-info margin-label">Waiting</span>
						@endif
					@endif
                </p>
              @endforeach
              @foreach($query as $button)
              	@if(is_null($button->deleted_at)) 
					<div class="btn-group">
						<button type="button" class="btn btn-warning" disabled>Modify Form</button>
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" disabled>
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ route('edit-enrolment-fields', [$button->INDEXID, $button->Term, $button->Te_Code, $button->form_counter]) }}">Change field data in the form</a></li>
								<li><a href="#">Convert from regular to payment-based</a></li>
								<li><a href="#">Convert from payment-based to regular</a></li>
								<li class="divider"></li>
								<li><a href="#">Convert regular to placement</a></li>
							</ul>
					</div>
                @else
                @endif
              @endforeach
            </ul>
        @endif
</div>