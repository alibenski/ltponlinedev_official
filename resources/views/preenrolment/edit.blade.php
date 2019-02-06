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
	<div class="box box-primary col-sm-12">
		<div class="box-header"></div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="form-control">Index: </label>
				<p class="form-control-static">{{ $enrolment_details->INDEXID }}</p>
			</div>	

			<div class="form-group">
				<label for="form-control">Name: </label>
				<p class="form-control-static">{{ $enrolment_details->users->name }}</p>
			</div>	
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<label for="form-control">Time Stamp: </label>
				<p class="form-control-static">{{ $enrolment_details->created_at }}</p>
			</div>	
		</div>
	</div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="box box-info">
            <div class="box-header">Current Fields</div>
            <div class="box-body">
                <ul>
                <li>Language: {{ $enrolment_details->L }}</li> 
                <li>Course: {{ $enrolment_details->courses->Description }}</li>
                <div class="form-group">
                	<label for="" class="">Schedule(s):</label> 
        					@foreach($enrolment_schedules as $schedule)
        						<div class="form-control-static"><strong>{{ $schedule->schedule->name }}</strong></div>
        						<p>Supervisor's Approval: 
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
        		                </p>
        		                <p>HR Staff and Development Section Approval:
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
        		                </p>
        		                <p>
        		                	Language Secretariat Payment Validation: 
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
        		                </p>
        					@endforeach
                </div>
                <li>Organization: {{ $enrolment_details->DEPT }}</li>
                <li>Supervisor's email: {{  $enrolment_details->mgr_email }}</li>
                <li>Supervisor first name: {{ $enrolment_details->mgr_fname }}</li>
                <li>Supervisor last name: {{ $enrolment_details->mgr_lname }}</li>
                
                </ul>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-success">
            <div class="box-header">Change to...</div>
            <div class="box-body">
                <form method="POST" action="{{ route('update-enrolment-fields', [$enrolment_details->INDEXID, $enrolment_details->Term, $enrolment_details->Te_Code, $enrolment_details->form_counter]) }}" class="col-sm-12">{{ csrf_field() }}
                <label>Language</label> 
                @foreach ($languages as $id => $name)
		            <div class="input-group col-sm-12">
                      <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
                      <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
		            </div>
	            @endforeach
				<div class="form-group">
                	<label>Course</label>
                    <div class="col-sm-12">
                      <div class="dropdown">
                        <select class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%; display: none;" name="Te_Code">
                            <option value="">--- Select Course ---</option>
                        </select>
                      </div>
                    </div>
                </div>

                <div class="form-group">
                	<label>Schedule</label>
                    <div class="col-sm-12">
                      <div class="dropdown">
                        <select class="col-sm-12 form-control schedule_select_no select2-basic-single" style="width: 100%; display: none;" name="schedule_id">
                            <option value="">Fill Out Language and Course Options</option>
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
				</div> --}}
                <div class="form-group">
                	<button type="submit" class="btn btn-success btn-space pull-right">Save</button>
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

  });
</script>

<script type="text/javascript">
  $("input[name='L']").click(function(){
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