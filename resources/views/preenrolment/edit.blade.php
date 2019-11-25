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
                    <label for="form-control">Course: </label>
                    <h4 class="form-control-static">{{ $enrolment_details->courses->Description }}</h4>
                </div> 


                <div class="form-group">
                	<label for="" class="">Schedule(s):</label> 
        					@foreach($enrolment_schedules as $schedule)
        						<div class="form-control-static"><strong><h4>{{ $schedule->schedule->name }}</h4></strong></div>
        						{{-- <p>Supervisor's Approval: 
        							@if($schedule->is_self_pay_form == 1)
        							<span id="status" class="label label-info margin-label">
        							N/A - Self-Payment</span>
        							@elseif(is_null($schedule->approval))
        							<span id="status" class="label label-warning margin-label">
        							Pending Approval</span>
        							@elseif($schedule->approval == 1)
        							<span id="status" class="label label-success margin-label">
        							Approved</span>
        							@elseif($schedule->approval == 0)
        							<span id="status" class="label label-danger margin-label">
        							Disapproved</span>
        							@endif
        		                </p> --}}
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
        					@endforeach
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
            <div class="box-header"><h3>Change to...</h3></div>
            <div class="box-body">
                <div class="alert alert-default">
                    <h2><i class="fa fa-warning"></i> Warning</h2>
                </div>
                <div class="alert alert-warning">
                    <h4>
                        <p>
                            Changing the following fields assumes that the changes have been approved by the LTP chief and HR focal point. Take note that no email correspondences will be sent after fields have been updated.    
                        </p>
                        <p>
                            This page is not yet complete.
                        </p>
                    </h4>
                </div>
                
                <form method="POST" action="{{ route('update-enrolment-fields', [$enrolment_details->INDEXID, $enrolment_details->Term, $enrolment_details->Te_Code, $enrolment_details->eform_submit_count]) }}" class="col-sm-12">
                    {{ csrf_field() }}
                
                <input name="radioFullSelectDropdown" class="radio-full-select-dropdown" type="radio" value="1">
                <label for="radioFullSelectDropdown" class="label-full-select-dropdown">Change selected course</label>

                <div class="insert-full-select-dropdown"></div>
                
                <div class="form-group">
                    <label>HR approval </label>
                    <div class="col-sm-12">
                      <div class="dropdown">
                        <select class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%;" name="approval_hr">
                            <option value="">--- No Change ---</option>
                            <option value="1">Approve</option>
                            <option value="0">Disapprove<option>
                        </select>
                      </div>
                    </div>
                </div>

                {{-- <label>Organization</label>
            	<div class="col-sm-12">
                  <div class="dropdown">
					<select id="input" name="DEPT" class="col-md-8 form-control select2-basic-single" style="width: 100%;">
					@if(!empty($org))
						<option value="">Select</option>
						@foreach($org as $value)
						<option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
						@endforeach
					@endif
					</select>
                  </div>
                </div>

                <div class="form-group">
                	<label>Supervisor's email</label>
                	<input type="text" name="mgr_email" class="col-sm-12 form-control" placeholder="Leave blank if no change">
				</div>
                <div class="form-group">
                	<label>Supervisor first name</label>
                	<input type="text" name="mgr_fname" class="col-sm-12 form-control" placeholder="Leave blank if no change">
				</div>
                <div class="form-group">
                	<label>Supervisor last name</label>
                	<input type="text" name="mgr_lname" class="col-sm-12 form-control" placeholder="Leave blank if no change">
				</div>
                <div class="form-group">
                	<label>Supervisor approval</label>
                	<div class="col-sm-12">
                      <div class="dropdown">
                        <select class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%;" name="approval">
                            <option value="">--- No Change ---</option>
                            <option value="1">Approve</option>
                            <option value="0">Disapprove<option>
                        </select>
                      </div>
                    </div>
				</div>
                 --}}

                <div class="form-group">
                	<button type="submit" class="btn btn-success btn-space pull-right"><i class="fa fa-save"></i> Save</button>
	                <input type="hidden" name="Term" value="{{ $enrolment_details->Term }}">
	                <input type="hidden" name="form_counter" value="{{ $enrolment_details->form_counter }}">
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
    
    $('.select2-basic-single').select2({
    placeholder: "--- No Change ---",
    });

    $('input.radio-full-select-dropdown').click( function(){
        var radioFullSelect = $(this).val();
        var token = $("input[name='_token']").val();

       $.ajax({
            url: 'ajax-show-full-select-dropdown',
            type: 'GET',
            data: {radioFullSelect:radioFullSelect, _token:token},
        })
        .done(function(data, status) {
            // $('div.insert-full-select-dropdown').html('');
            // $('div.insert-full-select-dropdown').html(data.options);
            console.log(data);
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
         
    });
  });
</script>

<script type="text/javascript">
  $("input[name='L']").on('click', function(){
      var L = $(this).val();
      var term = $("input[name='Term']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("select[name='Te_Code']").html('');
            $("select[name='Te_Code']").html(data.options);
          }
      });
  }); 
</script>

<script type="text/javascript">
  $("select[name='Te_Code']").on('change',function(){
      var course_id = $(this).val();
      var term = $("input[name='Term']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax2') }}", 
          method: 'POST',
          data: {course_id:course_id, term_id:term, _token:token},
          success: function(data) {
            $("select[name='schedule_id']").html('');
            $("select[name='schedule_id']").html(data.options);
          }
      });
  }); 
</script>
@stop