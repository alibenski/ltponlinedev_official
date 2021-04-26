@extends('main')
@section('tabtitle', 'Edit Form')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
        h6 {font-weight: bold}
    </style>
@stop
@section('content')
<div class="d-flex">
    <div class="col-sm-4 mt-3 clearfix">
        <div class="card">
            <div class="card-header bg-info"><h5>Current Form</h5></div>
            <div class="card-body">
                
                <div class="form-group">
                    <label for="form-control">Term: </label>
                    <h6 class="form-control-static">{{ $enrolment_details->first()->terms->Term_Name }} ({{ $enrolment_details->first()->terms->Comments }})</h6>
                </div>  

                <div class="form-group">
                    <label for="form-control">Profile: </label>
                    <h6>
                    @if($enrolment_details->first()->profile == "STF") Staff Member @endif
                    @if($enrolment_details->first()->profile == "INT") Intern @endif
                    @if($enrolment_details->first()->profile == "CON") Consultant @endif
                    @if($enrolment_details->first()->profile == "WAE") When Actually Employed @endif
                    @if($enrolment_details->first()->profile == "JPO") JPO @endif
                    @if($enrolment_details->first()->profile == "MSU") Staff of Permanent Mission @endif
                    @if($enrolment_details->first()->profile == "SPOUSE") Spouse of Staff from UN or Mission @endif
                    @if($enrolment_details->first()->profile == "RET") Retired UN Staff Member @endif
                    @if($enrolment_details->first()->profile == "SERV") Staff of Service Organizations in the Palais @endif
                    @if($enrolment_details->first()->profile == "NGO") Staff of UN-accredited NGO's @endif
                    @if($enrolment_details->first()->profile == "PRESS") Staff of UN Press Corps @endif
                    </h6>
                </div>

                <div class="form-group">
                    <label>Organization:</label>
                    <h6 class="form-control-static">{{ $enrolment_details->first()->DEPT }}</h6>
                </div>

                <div class="form-group">
                    <label for="form-control">Name: </label>
                    <h6 class="form-control-static">{{ $enrolment_details->first()->users->name }}</h6>
                </div>

                <div class="form-group">
                    <label for="form-control">Language: </label>
                    <h6 class="form-control-static">{{ $enrolment_details->first()->languages->name }}</h6>
                </div> 

                <div class="form-group">
                    <label for="form-control">Course: </label>
                    <h6 class="form-control-static">{{ $enrolment_details->first()->courses->Description }}</h6>
                </div> 


                <div class="form-group">
                    <label for="" class="">Schedule(s):</label> 
                        @foreach($enrolment_details as $schedule)
                            <div class="form-control-static"><strong><h6>{{ $schedule->schedule->name }}</h6></strong></div>
                            <div class="form-group">
                                <label>HR Staff and Development Section Approval:</label>
                                <h6 class="form-control-static">
                                @if(is_null($schedule->is_self_pay_form))
                                    @if(in_array($schedule->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
                                        <span id="status" class="badge badge-primary">
                                        N/A - Non-paying organization</span>
                                    @else
                                        @if(is_null($schedule->approval) && is_null($schedule->approval_hr))
                                        <span id="status" class="badge badge-warning">
                                        Pending Approval</span>
                                        @elseif($schedule->approval == 0 && (is_null($schedule->approval_hr) || isset($schedule->approval_hr)))
                                        <span id="status" class="badge badge-danger">
                                        N/A - Disapproved by Manager</span>
                                        @elseif($schedule->approval == 1 && is_null($schedule->approval_hr))
                                        <span id="status" class="badge badge-warning">
                                        Pending Approval</span>
                                        @elseif($schedule->approval == 1 && $schedule->approval_hr == 1)
                                        <span id="status" class="badge badge-success">
                                        Approved</span>
                                        @elseif($schedule->approval == 1 && $schedule->approval_hr == 0)
                                        <span id="status" class="badge badge-danger">
                                        Disapproved</span>
                                        @endif
                                    @endif
                                @else
                                <span id="status" class="badge badge-primary">
                                N/A - Self-Payment</span>
                                @endif
                                </h6>
                            </div>

                            <div class="form-group">
                                <label>Language Secretariat Payment Validation: </label>
                                <h6 class="form-control-static">
                                @if(is_null($schedule->is_self_pay_form))
                                <span id="status" class="badge badge-primary">N/A</span>
                                @else
                                    @if($schedule->selfpay_approval === 1)
                                    <span id="status" class="badge badge-success">Approved</span>
                                    @elseif($schedule->selfpay_approval === 2)
                                    <span id="status" class="badge badge-warning">Pending Approval</span>
                                    @elseif($schedule->selfpay_approval === 0)
                                    <span id="status" class="badge badge-danger">Disapproved</span>
                                    @else 
                                    <span id="status" class="badge badge-primary">Waiting</span>
                                    @endif
                                @endif
                                </h6>  
                            </div>
                        @endforeach
                </div>
                
                <div class="form-group">
                    <label>Submission Date:</label>
                    <h6 class="form-control-static">{{ $enrolment_details->first()->created_at }}</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8 mt-3 clearfix">
        <div class="card">
            <div class="card-header bg-info"><h5>Modification Options</h5></div>
            <div class="card-body">

            </div>
        </div>
    </div>
</div>
@stop
@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
@stop