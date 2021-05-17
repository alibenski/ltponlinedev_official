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
<div class="d-flex flex-wrap">
    <div class="col-sm-4 mt-3">
        <div class="card">
            <div class="card-header bg-info text-white"><h5>Current Form</h5></div>
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
                    <label>Student schedule is flexible:</label>
                    <h6 class="form-control-static">@if($enrolment_details->first()->flexibleBtn == 1) <span id="status" class="badge badge-success">Yes</span> @else <span id="status" class="badge badge-danger">No</span> @endif</h6>
                </div>
                
                <div class="form-group">
                    <label>Student Comment:</label>
                    <h6 class="form-control-static">@if($enrolment_details->first()->std_comments){{ $enrolment_details->first()->std_comments }}@else <span id="status" class="badge badge-primary">None</span> @endif</h6>
                </div>

                <div class="form-group">
                    <label>Submission Date:</label>
                    <h6 class="form-control-static">{{ $enrolment_details->first()->created_at }}</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8 mt-3">
        <div class="card">
            <div class="card-header bg-warning"><h5>Resubmit your form with the changes below</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('student-update-enrolment-form') }}" class="form-horizontal form-prevent-multi-submit">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-12 control-label">Select language:</label>
                        <div class="col-md-12">
                            @foreach ($languages as $id => $name)
                            <div class="input-group col-md-9">
                                <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
                                <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label for="course_id" class="col-md-12 control-label">Select Course: </label>
                        <div class="col-md-12">
                            <div class="dropdown">
                            <select class="col-md-12 form-control course_select_no wx" style="width: 100%" name="course_id" autocomplete="off">
                                <option value="">--- Select Course ---</option>
                            </select>
                            </div>
                        </div>
                    </div>

                    <div id="schedule_section" class="form-group d-none">
                        <label for="schedule_id" class="col-md-12 control-label">Select a schedule: (limited to only 1)</label>
                        <div class="col-md-12">
                            <div class="dropdown">
                            <select class="col-md-12 form-control schedule_select_no select2-basic-single" style="width: 100%;" name="schedule_id" autocomplete="off">
                                <option value="">Fill Out Language and Course Options</option>
                            </select>
                            </div>
                            {{-- <button type="button" class="multi-clear button btn btn-danger mt-2" style="margin-bottom: 5px;" aria-label="Programmatically clear Select2 options">Clear selected schedule</button> --}}
                        </div>
                    </div>
                    

                    <div class="form-group col-md-12">
                      <div class="disclaimer-flexible alert alert-default alert-block col-md-12">
                        <input id="flexibleBtn" name="flexibleBtn" class="with-font" type="checkbox" value="1">
                        <label for="flexibleBtn" class="form-control-static">I am flexible and can accept another schedule (days/times) if the selected class is full.
                        </label>
                      </div>
                    </div> 

                    <div class="form-group">
                        <label class="col-md-12 control-label">Comments: </label>
                        <div class="col-md-12">
                          <textarea name="regular_enrol_comment" class="form-control" maxlength="3500" placeholder=""></textarea>
                          <small class="text-danger">Please indicate any relevant information above; for example: what course (if any) you would like to take if the course you selected is full, and any time constraints.</small>
                        </div>
                    </div>

                    <div class="col-md-3 offset-md-5">
                      <button type="submit" class="btn btn-success button-prevent-multi-submit">Submit Changes</button>
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                      <input type="hidden" name="indexno" value="{{ $enrolment_details->first()->users->indexno }}">
                      <input type="hidden" name="term_id" value="{{ $enrolment_details->first()->Terms->Term_Code }}">
                      @foreach ($enrolment_id_array as $id)
                        <input type="hidden" name="enrolment_id[]" value="{{ $id }}">
                      @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/customSelect2.js') }}"></script>
<script src="{{ asset('js/submit.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script type="text/javascript">
  $('.select2-basic-single').select2({
      minimumResultsForSearch: -1,
      placeholder: "Select Schedule",
  });

  $("input[name='L']").click(function(){
      $("#schedule_section").addClass('d-none');
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("select[name='course_id']").html('');
            $("select[name='course_id']").html(data.options);
          }
      });
  }); 
</script>
<script type="text/javascript">
  $("select[name='course_id']").on('change',function(){
      var course_id = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax2') }}", 
          method: 'POST',
          data: {course_id:course_id, term_id:term, _token:token},
          success: function(data) {
              $("select[name='schedule_id']").html('');
              $("select[name='schedule_id']").html(data.options);
              $("#schedule_section").removeClass('d-none');
          }
      });
  }); 
</script>
@stop