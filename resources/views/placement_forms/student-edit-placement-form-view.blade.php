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
            <div class="card-header bg-info text-white"><h5>Current Form</h5></div>
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
                    <label for="form-control">Comment on test date, time, constraints, etc: </label>
                    <h6 class="form-control-static">
                    @if (is_null($enrolment_details->std_comments))
                    No comment given
                    @else
                    {{ $enrolment_details->std_comments }}
                    @endif
                    </h6>
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
                    <label class="font-weight-bold font-italic">Information about your availability</label>
                    <label>Availablity Time:</label>
                    <h6 class="form-control-static">{{ str_replace('-', ' ',  $enrolment_details->timeInput) }}</h6>
                    <label>Availablity Days:</label>
                    <h6 class="form-control-static">{{ str_replace('-', ' ',  $enrolment_details->dayInput) }}</h6>
                    <label>Availability Delivery Mode:</label>
                    <h6 class="form-control-static">@if($enrolment_details->deliveryMode === 0)<span class="glyphicon glyphicon-ok text-success"></span> in-person @elseif($enrolment_details->deliveryMode === 1)<span class="glyphicon glyphicon-ok text-success"></span> online @elseif($enrolment_details->deliveryMode === 2)<span class="glyphicon glyphicon-ok text-success"></span> both in-person and online @else <span class="glyphicon glyphicon-remove text-danger"></span> No response @endif</h6>
                    <label>Comment on preferred course, schedule flexbility, constraints, passed LPE, etc:</label>
                    <h6 class="form-control-static">{{ $enrolment_details->course_preference_comment }}</h6>
                </div>
                
                <div class="form-group">
                    <label>Submission Date:</label>
                    <h6 class="form-control-static">{{ $enrolment_details->created_at }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-8 mt-3">
        <div class="card">
            <div class="card-header bg-warning"><h5>Complete the form with the desired info changes</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('student-update-placement-form') }}" class="form-horizontal form-prevent-multi-submit">
                    {{ csrf_field() }}
                    <div class="d-flex flex-wrap">
                      <div class="form-group col-sm-6">
                        <label class="col-sm-12 control-label">Clcik on your selected language:</label>
                            @foreach ($languages as $id => $name)
                            <div class="input-group col-md-9" @if ($enrolment_details->L != $id) style="display: none" @endif>
                                <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}" @if ($enrolment_details->L != $id) disabled @endif>
                                <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
                            </div>
                            @endforeach
                      </div>

                      <div class="form-group col-sm-6">
                        <div class="alert alert-danger col-sm-12">
                            <h6 class="text-danger"><strong>Important Note:</strong></h6>
                            <p class="text-justify font-weight-light">If you have already passed the LPE or the highest level of your chosen language but have not taken classes during two terms, you are still required to fill in the form below. However the placement test might not be necessary depending on the information you provide. The language training secretariat will examine your request and make a decision.</p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="placement-enrol d-none">

                        @include('form.partials.placement_form.testDates')
                    
                        @include('form.partials.placement_form.coursePreference')
                    
                     <div class="col-sm-3 offset-sm-5 mt-3">
                       <button type="submit" class="btn btn-success button-prevent-multi-submit">Submit Changes</button>
                       <input type="hidden" name="_token" value="{{ Session::token() }}">
                       <input type="hidden" name="indexno" value="{{ $enrolment_details->users->indexno }}">
                       <input type="hidden" name="term_id" value="{{ $enrolment_details->Terms->Term_Code }}">
                       <input type="hidden" name="enrolment_id" value="{{ $enrolment_details->id }}">
                       <input type="hidden" name="assigned_to_course" value="{{ $enrolment_details->assigned_to_course }}">
                     </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
@stop
@section('scripts_code')
<script>
  $("input[name='L']").on('click', function(){
    $(".placement-enrol").removeClass('d-none')
    $("label[for='scheduleChoices']").html("");
    $(".scheduleChoices").remove();  
    $(".place-here").hide().append('<div class="scheduleChoices col-sm-12"></div>').fadeIn('fast');

      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();
      console.log(L);

      $.ajax({
          url: "{{ route('check-placement-sched-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data) { // get the placement test schedules
              $.each(data, function(index, val) {
                  var m_names = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                  var d = new Date(val.date_of_plexam);
                  var curr_date = d.getDate();
                  var curr_month = d.getMonth();
                  var curr_year = d.getFullYear();
                  var dateString = curr_date + " " + m_names[curr_month] + " " + curr_year;

                  var dend = new Date(val.date_of_plexam_end);
                  var curr_date_end = dend.getDate();
                  var curr_month_end = dend.getMonth();
                  var curr_year_end = dend.getFullYear();
                  var dateStringEnd = curr_date_end + " " + m_names[curr_month_end] + " " + curr_year_end;

                  console.log('is online: ' + val.is_online)

                  if (val.is_online == 1) {
                    $(".scheduleChoices").append('<div class="input-group-prepend"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space">Online from '+ dateString +' to ' + dateStringEnd + '</label></div>').fadeIn();
                  } else {
                    $(".scheduleChoices").append('<div class="input-group-prepend"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space"> '+ dateString +' (in person)</label></div>').fadeIn();
                  }
              }); // end of $.each

              // if no schedule, tell student there is none
              if (!$("input[name='placementLang']").length){
                $("label[for='scheduleChoices']").html("<div class='alert alert-danger'>Sorry no placement test schedule available for this language</div>");
              } 
        }
        });
  });
</script>
@stop