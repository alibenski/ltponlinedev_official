@extends('main')
@section('tabtitle', 'Edit Placement Form')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
        h6 {font-weight: bold}
    </style>
@stop
@section('content')
<div class="d-flex flex-wrap">
    <div class="col-sm-4 mt-3">
        <div class="card">
            <div class="card-header bg-info"><h5>Current Form</h5></div>
            <div class="card-body">
                
                <div class="form-group">
                    <label for="form-control">Term: </label>
                    <h6 class="form-control-static">{{ $enrolment_details->terms->Term_Name }} ({{ $enrolment_details->terms->Comments }})</h6>
                </div>  

                <div class="form-group">
                    <label for="form-control">Profile: </label>
                    <h6>
                    @if($enrolment_details->profile == "STF") Staff Member @endif
                    @if($enrolment_details->profile == "INT") Intern @endif
                    @if($enrolment_details->profile == "CON") Consultant @endif
                    @if($enrolment_details->profile == "WAE") When Actually Employed @endif
                    @if($enrolment_details->profile == "JPO") JPO @endif
                    @if($enrolment_details->profile == "MSU") Staff of Permanent Mission @endif
                    @if($enrolment_details->profile == "SPOUSE") Spouse of Staff from UN or Mission @endif
                    @if($enrolment_details->profile == "RET") Retired UN Staff Member @endif
                    @if($enrolment_details->profile == "SERV") Staff of Service Organizations in the Palais @endif
                    @if($enrolment_details->profile == "NGO") Staff of UN-accredited NGO's @endif
                    @if($enrolment_details->profile == "PRESS") Staff of UN Press Corps @endif
                    </h6>
                </div>

                <div class="form-group">
                    <label>Organization:</label>
                    <h6 class="form-control-static">{{ $enrolment_details->DEPT }}</h6>
                </div>

                <div class="form-group">
                    <label for="form-control">Name: </label>
                    <h6 class="form-control-static">{{ $enrolment_details->users->name }}</h6>
                </div>

                <div class="form-group">
                    <label for="form-control">Language: </label>
                    <h6 class="form-control-static">{{ $enrolment_details->languages->name }}</h6>
                </div> 

                <div class="form-group">
                    <label for="form-control">Placement Exam Date: 
                        @if ($enrolment_details->placementSchedule->is_online == 1)
                        <span class="badge badge-success">Online</span>
                        </label>
                        <h6 class="form-control-static">Open from {{ date('Y F d', strtotime($enrolment_details->placementSchedule->date_of_plexam)) }} to {{ date('Y F d', strtotime($enrolment_details->placementSchedule->date_of_plexam_end)) }}</h6>
                        @else
                        </label>
                        <h6 class="form-control-static">{{ date('Y F d', strtotime($enrolment_details->placementSchedule->date_of_plexam)) }}</h6>
                        @endif
                </div>

                <div class="form-group">
                	<div class="form-group">
		                <label>HR Staff and Development Section Approval:</label>
                        <h6 class="form-control-static">
						@if(is_null($enrolment_details->is_self_pay_form))
							@if(in_array($enrolment_details->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
								<span id="status" class="badge badge-primary margin-label">
								N/A - Non-paying organization</span>
							@else
								@if(is_null($enrolment_details->approval) && is_null($enrolment_details->approval_hr))
								<span id="status" class="badge badge-warning margin-label">
								Pending Approval</span>
								@elseif($enrolment_details->approval == 0 && (is_null($enrolment_details->approval_hr) || isset($enrolment_details->approval_hr)))
								<span id="status" class="badge badge-danger margin-label">
								N/A - Disapproved by Manager</span>
								@elseif($enrolment_details->approval == 1 && is_null($enrolment_details->approval_hr))
								<span id="status" class="badge badge-warning margin-label">
								Pending Approval</span>
								@elseif($enrolment_details->approval == 1 && $enrolment_details->approval_hr == 1)
								<span id="status" class="badge badge-success margin-label">
								Approved</span>
								@elseif($enrolment_details->approval == 1 && $enrolment_details->approval_hr == 0)
								<span id="status" class="badge badge-danger margin-label">
								Disapproved</span>
								@endif
							@endif
						@else
						<span id="status" class="badge badge-primary margin-label">
						N/A - Self-Payment</span>
						@endif
		                </h6>
                    </div>

                    <div class="form-group">
		                <label>Language Secretariat Payment Validation: </label>
						<h6 class="form-control-static">
                        @if(is_null($enrolment_details->is_self_pay_form))
						<span id="status" class="badge badge-primary margin-label">N/A</span>
						@else
							@if($enrolment_details->selfpay_approval === 1)
							<span id="status" class="badge badge-success margin-label">Approved</span>
							@elseif($enrolment_details->selfpay_approval === 2)
							<span id="status" class="badge badge-warning margin-label">Pending Approval</span>
							@elseif($enrolment_details->selfpay_approval === 0)
							<span id="status" class="badge badge-danger margin-label">Disapproved</span>
							@else 
							<span id="status" class="badge badge-primary margin-label">Waiting</span>
							@endif
						@endif
	                    </h6>  
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Submission Date:</label>
                    <h6 class="form-control-static">{{ $enrolment_details->created_at }}</h6>
                </div>
            </div>
        </div>
    </div>


</div>
@stop
@section('scripts_code')

@stop