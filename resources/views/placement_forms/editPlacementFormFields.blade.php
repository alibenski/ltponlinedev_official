@extends('admin.no_sidebar_admin')

@section('customcss')
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop

@section('content')

<div class="row">
    <div class="col-sm-4">
        <div class="box box-info">
            <div class="box-header"><h3>Current Fields</h3></div>
            <div class="box-body">
                
                <div class="form-group">
                    <label for="form-control">Term: </label>
                    <h4 class="form-control-static">{{ $enrolment_details->terms->Term_Name }} ({{ $enrolment_details->terms->Comments }})</h4>
                </div>  

                <div class="form-group">
                    <label for="form-control">Index: </label>
                    <h4 class="form-control-static">{{ $enrolment_details->INDEXID }}</h4>
                </div>  

                <div class="form-group">
                    <label for="form-control">Name: </label>
                    <h4 class="form-control-static">{{ $enrolment_details->users->name }}</h4>
                </div>

                <div class="form-group">
                    <label for="form-control">Language: </label>
                    <h4 class="form-control-static">{{ $enrolment_details->languages->name }}</h4>
                </div> 

                <div class="form-group">
                	<div class="form-group">
		                <label>HR Staff and Development Section Approval:</label>
                        <h4 class="form-control-static">
						@if(is_null($schedule->is_self_pay_form))
							@if(in_array($schedule->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
								<span id="status" class="label label-info margin-label">
								N/A - Non-paying organization</span>
							@else
								@if(is_null($schedule->approval) && is_null($schedule->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($schedule->approval == 0 && (is_null($schedule->approval_hr) || isset($schedule->approval_hr)))
								<span id="status" class="label label-danger margin-label">
								N/A - Disapproved by Manager</span>
								@elseif($schedule->approval == 1 && is_null($schedule->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($schedule->approval == 1 && $schedule->approval_hr == 1)
								<span id="status" class="label label-success margin-label">
								Approved</span>
								@elseif($schedule->approval == 1 && $schedule->approval_hr == 0)
								<span id="status" class="label label-danger margin-label">
								Disapproved</span>
								@endif
							@endif
						@else
						<span id="status" class="label label-info margin-label">
						N/A - Self-Payment</span>
						@endif
		                </h4>
                    </div>

                    <div class="form-group">
		                <label>Language Secretariat Payment Validation: </label>
						<h4 class="form-control-static">
                        @if(is_null($schedule->is_self_pay_form))
						<span id="status" class="label label-info margin-label">N/A</span>
						@else
							@if($schedule->selfpay_approval === 1)
							<span id="status" class="label label-success margin-label">Approved</span>
							@elseif($schedule->selfpay_approval === 2)
							<span id="status" class="label label-warning margin-label">Pending Approval</span>
							@elseif($schedule->selfpay_approval === 0)
							<span id="status" class="label label-danger margin-label">Disapproved</span>
							@else 
							<span id="status" class="label label-info margin-label">Waiting</span>
							@endif
						@endif
	                    </h4>  
                    </div>
                </div>

                <div class="form-group">
                    <label>Organization:</label>
                    <h4 class="form-control-static">{{ $enrolment_details->DEPT }}</h4>
                </div>
                
                <div class="form-group">
                    <label>Submission Date:</label>
                    <h4 class="form-control-static">{{ $enrolment_details->created_at }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-8">
        <div class="box box-success">
            <div class="box-header"><h3>Modification Options Placement Test Form</h3></div>
            <div class="box-body">
               
                @if (Session::has('msg-language-updated')) 
                    <div class="alert alert-success alert-block" role="alert">
                        <strong>Language modification: </strong> {{ Session::get('msg-language-updated') }}
                    </div>
                @endif

                @if (Session::has('msg-delete-form')) 
                    <div class="alert alert-danger alert-block" role="alert">
                        <strong>HR approval: </strong> {{ Session::get('msg-delete-form') }}
                    </div>
                @endif

                @if (Session::has('msg-restore-form')) 
                    <div class="alert alert-success alert-block" role="alert">
                        <strong>HR approval: </strong> {{ Session::get('msg-restore-form') }}
                    </div>
                @endif

                @if (Session::has('msg-change-org')) 
                    <div class="alert alert-success alert-block" role="alert">
                        <strong>Organization field: </strong> {{ Session::get('msg-change-org') }}
                    </div>
                @endif

                @if (Session::has('msg-convert-to-selfpay-form')) 
                    <div class="alert alert-success alert-block" role="alert">
                        <strong>Conversion: </strong> {{ Session::get('msg-convert-to-selfpay-form') }}
                    </div>
                @endif

                <div class="alert alert-warning">
                    <h4>
                        <p><i class="fa fa-warning"></i> Warning</p>
                        <p>
                            Changing the following fields assumes that the changes have been approved by the LTP chief and HR focal point. Take note that no email correspondences will be sent after fields have been updated.    
                        </p>
                    </h4>
                </div>
                
                <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ route('update-placement-fields', [$enrolment_details->id]) }}" class="col-sm-12">
                    {{ csrf_field() }}
                <div class="input-group col-md-12">
                    <h4>
                        <input id="radioFullSelectDropdown" name="radioFullSelectDropdown" class="with-font radio-full-select-dropdown" type="checkbox" value="1">
                        <label for="radioFullSelectDropdown" class="label-full-select-dropdown"> Change selected language</label>
                    </h4>

                    <div class="insert-language-dropdown"></div>
                </div>

                <div class="input-group col-md-12">
                    <h4>
                        <input id="radioChangeHRApproval" name="radioChangeHRApproval" class="with-font modify-option radio-change-hr-approval" type="checkbox" value="1" @if ($enrolment_details->is_self_pay_form == 1 || in_array($enrolment_details->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO'])) disabled="" @endif>
                        <label for="radioChangeHRApproval" class="label-change-hr-approval">
                            @if ($enrolment_details->is_self_pay_form == 1 || in_array($enrolment_details->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))<del class="text-danger">Change HR approval status (if applicable)</del>
                            @else Change HR approval status (if applicable)
                            @endif
                        </label>
                    </h4>

                    <div class="insert-change-hr-approval"></div>
                </div>

                <div class="input-group col-md-12">
                    <h4>
                        <input id="radioChangeOrgInForm" name="radioChangeOrgInForm" class="with-font modify-option radio-change-org-in-form" type="checkbox" value="1">
                        <label for="radioChangeOrgInForm" class="label-change-org-in-form"> Change Organization (only applies to this form)</label>
                    </h4>

                    <div class="insert-change-org-in-form"></div>
                </div>                
                
                <div class="input-group col-md-12">
                    <h4>
                        <input id="radioSelfPayOptions" name="radioSelfPayOptions" class="with-font radio-selfpay-options" type="checkbox" value="1">
                        <label for="radioSelfPayOptions" class="label-selfpay-options"> Self-payment form options</label>
                    </h4>
                        <div class="decision-section hidden">
                            <div class="col-sm-12">
                                <h4>
                                <input id="decisionConvertToSelfpay" name="decisionConvert" class="with-font modify-option decision-convert-to-selfpay" type="radio" value="1" @if ($enrolment_details->is_self_pay_form == 1) disabled="" @endif>
                                <label for="decisionConvertToSelfpay">
                                    @if ($enrolment_details->is_self_pay_form == 1)<del class="text-danger"> Convert to a self-payment form</del>
                                    @else Convert to a self-payment form
                                    @endif
                                </label>
                                </h4>
                            </div>
                            <div class="insert-convert-to-selfpay"></div>

                            <div class="col-sm-12">
                                <h4>
                                <input id="decisionConvertToRegular" name="decisionConvert" class="with-font modify-option decision-convert-to-regular" type="radio" value="0" @if (is_null($enrolment_details->is_self_pay_form)) disabled="" @endif>
                                <label for="decisionConvertToRegular">
                                    @if (is_null($enrolment_details->is_self_pay_form))<del class="text-danger"> Convert to a non-self-payment form</del>
                                    @else Convert to a non-self-payment form
                                    @endif
                                </label>
                                </h4>
                            </div>
                            <div class="insert-convert-to-regular"></div>
                        </div>
                </div>

                <div class="input-group col-md-12">
                    <h4>
                        <input id="radioUndoDeleteStatus" name="radioUndoDeleteStatus" class="with-font modify-option radio-undo-delete-status" type="checkbox" @if (is_null($enrolment_details->deleted_at)) disabled="" @endif value="1">
                        <label for="radioUndoDeleteStatus" class="label-selfpay-options">
                            @if (is_null($enrolment_details->deleted_at))<del class="text-danger"> Undo delete/cancelled status</del> 
                            @else Undo delete/cancelled status
                            @endif
                        </label>
                    </h4>
                </div>

                <div class="form-group">
                	<button type="submit" class="btn btn-success btn-space pull-right"><i class="fa fa-save"></i> Save</button>
	                <input type="hidden" name="Term" value="{{ $enrolment_details->Term }}">
	                <input type="hidden" name="eform_submit_count" value="{{ $enrolment_details->eform_submit_count }}">
	                <input type="hidden" name="_token" value="{{ Session::token() }}">
	                {{ method_field('PUT') }}
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('java_script')
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script src="{{ asset('js/bootstrap-maxlength.js') }}"></script>
<script src="{{ asset('js/jquery.userTimeout.js') }}"></script>
{{-- <script src="{{ asset('js/customSelect2.js') }}"></script> --}}
<script src="{{ asset('js/submit.js') }}"></script> 

<script>
  $(document).ready(function(){
    $('input[type=radio]').prop('checked',false);
    $('input[type=checkbox]').prop('checked',false);

    $('.select2-basic-single').select2({
    placeholder: "--- No Change ---",
    });

    $('input.radio-full-select-dropdown').click( function(){
        var radioFullSelect = $(this).val();
        var token = $("input[name='_token']").val();
            if (($('input.radio-full-select-dropdown').prop('checked')) == true) {
               $.ajax({
                    url: "{{ route('ajax-show-language-dropdown') }}",
                    type: 'GET',
                    data: {radioFullSelect:radioFullSelect, _token:token},
                })
                .done(function(data) {
                    $('div.insert-language-dropdown').html('');
                    $('div.insert-language-dropdown').html(data.options);
                    $('.select2-basic-single').select2({
                    placeholder: "--- No Change ---",
                    });

                    $("input[name='L'].lang_select_no").each(function() {
                        if ($(this).val() == "{{ $enrolment_details->L }}") {
                            $(this).attr('disabled', true);
                        }
                    });
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    // console.log("complete");
                });
            } else {
                $('div.insert-language-dropdown').empty();   
            }
    });

    $('input.radio-change-hr-approval').click( function(){
        var token = $("input[name='_token']").val();
            if (($('input.radio-change-hr-approval').prop('checked')) == true) {
               $.ajax({
                    url: "{{ route('ajax-change-hr-approval') }}",
                    type: 'GET',
                    data: {_token:token},
                })
                .done(function(data) {
                    $('div.insert-change-hr-approval').html('');
                    $('div.insert-change-hr-approval').html(data.options);
                    $('.select2-basic-single').select2({
                    placeholder: "--- No Change ---",
                    });
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    // console.log("complete");
                });
            } else {
                $('div.insert-change-hr-approval').empty();   
            }
    });

    $('input.radio-change-org-in-form').click( function(){
        var token = $("input[name='_token']").val();
            if (($('input.radio-change-org-in-form').prop('checked')) == true) {
               $.ajax({
                    url: "{{ route('ajax-change-org-in-form') }}",
                    type: 'GET',
                    data: {_token:token},
                })
                .done(function(data) {
                    $('div.insert-change-org-in-form').html('');
                    $('div.insert-change-org-in-form').html(data.options);
                    $('.select2-basic-single').select2({
                    placeholder: "--- No Change ---",
                    });
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    // console.log("complete");
                });
            } else {
                $('div.insert-change-org-in-form').empty();   
            }
    });

    $('input.radio-selfpay-options').click( function(){
        var token = $("input[name='_token']").val();

        if ($(this).prop('checked') == true) {
            $('div.decision-section').removeClass('hidden');
            $('input.decision-convert-to-selfpay').prop('checked',false);
            $('input.decision-convert-to-regular').prop('checked',false);

            $('input.decision-convert-to-selfpay').on('click', function() {
                if (($(this).prop('checked')) == true) {
                    $('div.insert-convert-to-regular').empty();  

                   $.ajax({
                        url: "{{ route('ajax-convert-to-selfpay') }}",
                        type: 'GET',
                        data: {_token:token},
                    })
                    .done(function(data) {
                        $('div.insert-convert-to-selfpay').html('');
                        $('div.insert-convert-to-selfpay').html(data.options);
                        $('.select2-basic-single').select2({
                        placeholder: "--- No Change ---",
                        });
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        // console.log("complete");
                    });
                } 
            });

            $('input.decision-convert-to-regular').on('click', function() {
                if (($(this).prop('checked')) == true) {
                    $('div.insert-convert-to-selfpay').empty();  

                   $.ajax({
                        url: "{{ route('ajax-convert-to-regular') }}",
                        type: 'GET',
                        data: {_token:token},
                    })
                    .done(function(data) {
                        $('div.insert-convert-to-regular').html('');
                        $('div.insert-convert-to-regular').html(data.options);
                        $('.select2-basic-single').select2({
                        placeholder: "--- No Change ---",
                        });
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        // console.log("complete");
                    });
                } 
            });

        } else {
            $('div.decision-section').addClass('hidden');
            $('div.insert-convert-to-selfpay').empty(); 
            $('div.insert-convert-to-regular').empty();  
        }
    });
  });
</script>

<script>
    // Check if at least one input field is filled 
    $(function checkAtLeastOneInput(){
        $("#updateForm").submit(function(event){
            // event.preventDefault();
            var valid=0;
            $(this).find('input.modify-option').each(function(){
                if($(this).prop('checked') == true) 
                    valid+=1;
            });
                        
            if(valid){
                $('button[type="submit"]').attr('disabled', true);
                // alert(valid + " input(s) filled. Thank you.");
                return true;
            }
            else {
                alert("Error: you must select and modify at least one field.");
                return false;
            }
        });
    });
</script>
@stop