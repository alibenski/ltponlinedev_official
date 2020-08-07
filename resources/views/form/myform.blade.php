@extends('main')
@section('tabtitle', '| UN Enrolment Form')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop
@section('content')
<div id="loader">
</div>
<div class="container">
  <div class="row">   
    <div class="col-md-12">  
      <div class="card">
          <div class="card-header bg-primary">Enrolment Form for: 
            <strong>
              @if(empty($next_term && $terms))
              NO DB ENTRY
              @else 
              {{ $terms->Term_Code.' - '.$terms->Term_Name.' - '.$terms->Comments.' Term' }}
              @endif
            </strong>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('myform.store') }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <div class="form-group col-md-10 col-md-offset-2">
                <input  name="CodeIndexID" type="hidden" value="" readonly>
                {{-- <input  name="user_id" type="hidden" value="{{$repos}}" readonly> --}}
                <input  name="term_id" type="hidden" value="
                  @if(empty($terms))
                  NO DB ENTRY
                  @else 
                  {{$terms->Term_Code}}
                  @endif
                " readonly>  
                </div>

                {{-- <div class="form-group">
                    <label for="" class="col-md-3 control-label">Index Number:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-qrcode"></i></span> --}}
                            <input  name="index_id" class="form-control"  type="hidden" value="{{ Auth::user()->sddextr->INDEXNO }}" readonly>              
                            <input  name="profile" class="form-control"  type="hidden" value="{{ Auth::user()->profile }}" readonly>                              
                        {{-- </div>
                    </div>
                </div> --}}

                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Name:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span><input  name="" class="form-control"  type="text" value="{{ Auth::user()->sddextr->FIRSTNAME }} {{ Auth::user()->sddextr->LASTNAME }}" readonly>                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="org" class="col-md-3 control-label">Organization:</label>
                  <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-globe"></i></span><input  name="fakeOrg" class="form-control"  type="text" value="{{ $user->sddextr->torgan['Org name'] }} - {{ $user->sddextr->torgan['Org Full Name'] }}" readonly>
                            <input  name="org" class="form-control"  type="hidden" value="{{ $user->sddextr->torgan['Org name'] }}" readonly>
                        </div>
                        {{-- <p class="small text-danger"><strong>Please check that you belong to the correct organization in this field.</strong></p> --}}
                  </div>
                </div>

                <div class="form-group" style="@if(is_null($repos_lang)) display: none @else  @endif ">
                    <label for="name" class="col-md-3 control-label">Last/Current UN Language Course:</label>

                    <div class="col-md-8 inputGroupContainer">
                      @if(is_null($repos_lang)) None
                      @else
                        @foreach( $repos_lang as $value )
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-graduation-cap"></i></span><input  name="" class="form-control"  type="text" value="@if(empty($value->Te_Code)) {{ $value->coursesOld->Description }} @else {{ $value->courses->Description}} @endif last @if(empty($value->terms->Term_Name) || is_null($value->terms->Term_Name))No record found @else {{ $value->terms->Term_Name }} (@if($value->Result == 'P') Passed @elseif($value->Result == 'F') Failed @elseif($value->Result == 'I') Incomplete @else -- @endif) @endif" readonly>                            
                          </div>
                        @endforeach
                      @endif
                    </div>
                </div> 

                <!-- NO DECISION SECTION -->
                <div class="0 box">
                   
                    <div class="form-group">
                        <label class="col-md-3 control-label">Select language:</label>
                              <div class="col-md-8">
                                  @foreach ($languages as $id => $name)
                                <div class="input-group col-md-9">
                                          <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
                                          <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
                                </div>
                                  @endforeach
                              </div>
                    </div>

                    <div class="placementTestMsg " style="display: none">
                      <div class="alert alert-warning">
                        <p>Our records show that either you are a new student or you have not been enrolled on the selected language course during the past two terms.</p>
                        <p>You are required to take a <strong>placement test</strong> unless you are a complete beginner.</p>
                        
                        <div class="form-group">
                              <label class="col-md-4 control-label">Are you a complete beginner?</label>
                                <div class="col-md-4">
                                          <input id="placementDecision3" name="placementDecisionB" class="with-font" type="radio" value="1">
                                          <label for="placementDecision3" class="form-control-static">YES</label>
                                </div>

                                <div class="col-md-4">
                                          <input id="placementDecision4" name="placementDecisionB" class="with-font" type="radio" value="0">
                                          <label for="placementDecision4" class="form-control-static">NO</label>
                                </div>
                        </div>
                      </div>
                    </div>

                  <div class="placement-enrol" style="display: none"> {{-- start of placement test enrolment part --}}
                    <div class="form-group col-md-12">
                      <div class="alert alert-danger col-md-8 col-md-offset-2">
                        <h4 class="text-danger"><strong><i class="fa fa-warning"></i> Important Note:</strong></h4>
                        <p><strong>If you have already passed the LPE or the highest level of your chosen language but have not taken classes during two terms, you are still required to fill in the form below. However the placement test might not be necessary depending on the information you provide. The language training secretariat will examine your request and make a decision.</strong></p>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-header bg-primary col-md-12"><strong>Placement test dates</strong></div>
                        <div class="card-body">
                          <div class="row col-md-10 col-md-offset-1">
                            <div class="alert alert-info alert-dismissible alert-placement-instruction">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <p>Please select a date (required) for your placement test from the options available. If you are unable to take the placement test on the given dates, then please write the reason in the comments box and indicate your availability.  Where possible, we will try to accommodate your wishes.  If it is not possible, you will need to apply again for the following term.</p> 
                              <p> If you think that the placement test is not necessary for you, for whatever reason, please explain why in the box below.</p> 
                            </div>
                          </div>
                          
                          <div class="otherQuestions2 row col-md-12">
                            <div class="insert-container col-md-12">
                                <div class="form-group">
                                  <div class="place-here col-md-6 col-md-offset-3">
                                  <label for="scheduleChoices"></label>
                                    <div class="scheduleChoices col-md-12">
                                    {{-- insert jquery schedules here --}}
                                    </div>
                                  </div>
                                </div>
                              <div class="insert-msg"></div>

                              <div class="col-md-12 form-group">
                                <label class="col-md-3 control-label">Comments: <i>(optional)</i></label>
                                <div class="col-md-8 ">
                                <textarea name="std_comment" class="form-control" maxlength="3500" placeholder="For queries or comments about the placement test e.g. time, place, date, constraints, etc."></textarea>
                                </div>
                              </div>

                            </div>    
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-header bg-primary col-md-12"><strong>Information about your course preference</strong></div>
                        <div class="card-body">
                          <div class="row col-md-10 col-md-offset-1">
                            <div class="alert alert-danger">
                            <p>Please indicate the time and the days you are available to attend the course. Check/tick all that apply.</p>
                            </div>
                          </div>
                          <div class="row card col-md-10 col-md-offset-1">
                            <div class="otherQuestions col-md-5">
                              <div class="form-group">
                                <label for="" class="control-label">Time:</label>
                                <div class="col-md-12">
                                      <div class="input-group col-md-12">                             
                                        <input id="morning" name="timeInput[]" class="with-font" type="checkbox" value="morning">
                                        <label for="morning" class="form-control-static">Morning (8 to 9.30 or 10 a.m.)</label>
                                      </div>
                                      {{-- <div class="input-group col-md-12">
                                        <input id="lunchtime1" name="timeInput[]" class="with-font" type="checkbox" value="lunchtime1">
                                        <label for="lunchtime1" class="form-control-static">lunchtime1 (tbc)</label>
                                      </div>
                                      <div class="input-group col-md-12">
                                        <input id="lunchtime2" name="timeInput[]" class="with-font" type="checkbox" value="lunchtime2">
                                        <label for="lunchtime2" class="form-control-static">lunchtime2 (tbc)</label>
                                      </div> --}}
                                      <div class="input-group col-md-12">
                                        <input id="afternoon" name="timeInput[]" class="with-font" type="checkbox" value="afternoon">
                                        <label for="afternoon" class="form-control-static">Afternoon (12.30 to 2 or 2.30 p.m.)</label>
                                      </div>
                                 </div>
                              </div>
                            </div>

                            <div class="otherQuestions3 col-md-7">
                              <div class="form-group">
                                <label for="" class="control-label">Day:</label>
                                <div class="col-md-12">
                                  @foreach ($days as $id => $name)
                                      <div class="input-group col-md-12">                             
                                        <input id="{{ $name }}" name="dayInput[]" class="with-font" type="checkbox" value="{{ $id }}">
                                        <label for="{{ $name }}" class="form-control-static">{{ $name }}</label>
                                      </div>
                                  @endforeach
                                 </div>
                              </div>
                            </div>

                            <div class="col-md-12 form-group">
                              <label class="col-md-3 control-label text-danger">Comments: <i>(required)</i></label>
                              <div class="col-md-8 pink-border">
                              <small class="text-danger"><i class="fa fa-warning"></i> <strong>You are required to fill this comment box. Failure to do so will nullify your submission.</strong></small>
                              <textarea name="course_preference_comment" class="form-control" maxlength="3500" placeholder="preferred course, schedule flexbility, constraints, passed LPE, etc." required=""></textarea>
                              </div>
                            </div>

                          </div>
                        </div> {{-- end card body --}}
                      </div>
                    </div>

                  </div> {{-- end of placement test enrolment part --}}

                  <div class="placement-beginner-msg" style="display: none">
                    <div class="alert alert-info alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <p>Thank you for your response, {{Auth::user()->sddextr->FIRSTNAME}}.</p>
                      <p>You have answered <strong>YES</strong>. The beginner course for your selected language has been automatically filled in. Click the "Available for the following schedule(s)" field to check its availability.</p>
                    </div>
                  </div>

                  <div class="regular-enrol" style="display: none"> {{-- start of hidden fields --}}
                    
                    <div class="form-group">
                        <label for="course_id" class="col-md-3 control-label">Course selected: </label>
                        <div class="col-md-8">
                          <div class="dropdown">
                            <select class="col-md-8 form-control course_select_no wx" style="width: 100%; display: none;" name="course_id" autocomplete="off">
                                <option value="">--- Select Course ---</option>
                            </select>
                          </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="schedule_id" class="col-md-3 control-label">Available for the following schedule(s): </label>
                        <button type="button" class="multi-clear button btn btn-danger" style="margin-bottom: 5px;" aria-label="Programmatically clear Select2 options">Clear All</button>
                        <div class="col-md-8">
                          <div class="dropdown">
                            <select class="col-md-8 form-control schedule_select_no select2-multi" multiple="multiple" style="width: 100%; display: none;" name="schedule_id[]" autocomplete="off">
                                <option value="">Fill Out Language and Course Options</option>
                            </select>
                          </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                      <div class="disclaimer-flexible alert alert-default alert-block col-md-8 col-md-offset-3">
                        <input id="flexibleBtn" name="flexibleBtn" class="with-font" type="checkbox" value="1">
                        <label for="flexibleBtn" class="form-control-static">I am flexible and can accept another schedule (days/times) if the selected class is full.
                        </label>
                      </div>
                    </div> 

                    <div class="form-group">
                        <label class="col-md-3 control-label">Comments: <i>(optional)</i></label>
                        <div class="col-md-8">
                          <textarea name="regular_enrol_comment" class="form-control" maxlength="3500" placeholder=""></textarea>
                          <small class="text-danger">Please indicate any relevant information above; for example: what course (if any) you would like to take if the course you selected is full, and any time constraints.</small>
                        </div>
                    </div>

                            <!-- SHOW CHOICES REAL TIME -->
                    <div class="col-md-12">
                      <div class="well">
                        <div class="row">        
                            <div class="form-group">
                              <label for="first" class="col-md-2 control-label" style="color: green;">Student availability 1:</label> 
                              <div class="col-md-8 form-control-static"><p id="first" name=""></p></div>
                            </div>

                            <div class="form-group">
                              <label for="second" class="col-md-2 control-label" style="color: #337ab7;">Student availability 2:</label>
                              <div class="col-md-8 form-control-static"><p id="second"  name=""></p></div>
                            </div>
                        </div>    
                      </div>  
                    </div>
                            <!-- END OF SHOW CHOICES REAL TIME -->   
                  </div> {{-- end of hidden fields --}}
                    
                  <div class="submission-part" style="display: none">
                    
                    <div class="form-group col-md-12">
                      <div class="disclaimer alert col-md-8 col-md-offset-2">
                        <p class="small text-danger"><strong>Required field</strong></p>
                        <input id="approval" name="approval" class="with-font" type="radio" value="1" required="required">
                        <label for="approval" class="form-control-static">I have informed my supervisor about this enrolment and she/he has agreed that I will be able to attend the course. </label>
                      </div>
                    </div>

                    {{-- <div class="form-group">
                        <label for="mgr_name" class="col-md-3 control-label">Manager's Name:</label>
                        
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user-circle" aria-hidden="true"></i>
                                </span><input  name="mgr_fname" placeholder="Manager's First Name" class="form-control"  type="text" required="required">
                            </div>
                        </div>    
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user-circle" aria-hidden="true"></i>
                                </span><input  name="mgr_lname" placeholder="Manager's Last Name" class="form-control"  type="text" required="required">                                    
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mgr_email" class="col-md-3 control-label">Manager's Email Address:</label>
                        
                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span><input  name="mgr_email" placeholder="Enter Manager's Email" class="form-control"  type="text" required="required">                                    
                            </div>
                             <p class="small text-danger"><strong>Enter the <u>correct email address</u> of your manager because this form will be sent to this email address for approval.</strong></p>
                        </div>
                    </div> --}}
                
                    <div class="form-group col-md-12">
                      <div class="disclaimer alert col-md-8 col-md-offset-2">
                        <p class="small text-danger"><strong>Required field</strong></p>
                        <input id="agreementBtn" name="agreementBtn" class="with-font" type="radio" value="1" required="required">
                        <label for="agreementBtn" class="form-control-static">I have read and understood the <a href="http://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/LanguageCourses_en.pdf" target="_blank">Information Circular</a> regarding the Language Training Programme at UNOG.</label>
                      </div>
                    </div>
                    
                    <div class="form-group col-md-12">
                      <div class="disclaimer alert alert-danger col-md-8 col-md-offset-2">
                        <h4 class="text-danger"><strong><i class="fa fa-warning"></i> Important Note:</strong></h4>
                        <p><strong>If you wish to enrol on two courses for the same term, you need to submit another form.</strong> However, if you wish to take one course, but are not sure which one to select, please indicate it in the comments box above. Do not fill in several forms. <strong>One form = one course</strong>.</p>
                      </div>
                    </div>

                    <div class="col-sm-2 col-sm-offset-5">
                      <button type="submit" class="btn btn-success button-prevent-multi-submit">Send Enrolment</button>
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                    </div>
                  </div>
                </div>
                <!-- END OF NO DECISION SECTION -->
            </form>
          </div>
        </div>
    </div>
    </div>
  </div>
</div>
@stop   

@section('scripts_code')

<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script src="{{ asset('js/bootstrap-maxlength.js') }}"></script>
<script src="{{ asset('js/jquery.userTimeout.js') }}"></script>
<script>
// $(document).userTimeout({
//   logouturl: 'http://ltponlinedev.local/logout',
//   session: 600000,
//   force: 900000,
//   modalBody: 'Due to security reasons, your session will time out due to inactivity. Please choose to stay logged in or to logoff. Otherwise, you will be logged off automatically after 15 minutes.'
// });
</script>
<script>
  $('textarea').maxlength({
    alwaysShow: false,
    threshold: 500,
    warningClass: "label label-success",
    limitReachedClass: "label label-danger",
    separator: ' out of ',
    preText: 'Writing ',
    postText: ' chars.',
    validate: true
  });
</script>

<script>
  $(document).ready(function() {
    $("#loader").fadeOut(500);
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minView: 2, 
    });
    $.get("/check-placement-form-ajax", function(data) {
      $.each(data, function(index, val) {
        console.log('placementFormLang = ' + val.L);
        $("input[name='L'][value='"+ val.L +"']").attr('disabled', true); // check if the student already submitted placement form
        $("input[name='L'][value='"+ val.L +"']:disabled").after("<span class='label label-danger'>Scheduled for placement test");
      });
    }); 
  });
</script>

<script>
  $("input[name='L']").on('click', function(){
      $(".regular-enrol").attr('style', 'display: none'); // initiate hidden div 
      $(".submission-part").attr('style', 'display: none');
      $("input[name='placementDecisionB']").val("");
      $("textarea[name='course_preference_comment']").removeAttr('required'); // reset comment box as not required field
  // populate placement schedules
      $("label[for='scheduleChoices']").remove();
      $(".scheduleChoices").remove();
      $('.insert-msg').remove();
      $('.insert-container').append('<div class="insert-msg"></div>')

      if ($(this).val() == 'F') {
        $(".alert-placement-instruction").addClass('hidden');
        $(".place-here").hide().append('<label for="scheduleChoices">The French placement test is online. You may take the test online any time in the indicated period below. Click on the button if you agree.</label>').fadeIn('fast');
      } 
      else if ($(this).val() == 'E') {
        $(".alert-placement-instruction").removeClass('hidden');
        $(".place-here").hide().append('<label for="scheduleChoices">If you are in Geneva, please select one of the dates shown. If you are outside Geneva, please select the <em>online</em> option.</label>').fadeIn('fast');
      } else {
        $(".alert-placement-instruction").removeClass('hidden');
        $(".place-here").hide().append('<label for="scheduleChoices">Placement test date(s):</label>').fadeIn('fast');
      }

      $(".place-here").hide().append('<div class="scheduleChoices col-md-12"></div>').fadeIn('fast');

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
                  // $(".scheduleChoices").append('<input id="placementLang'+val.language_id+'" name="placementLang" type="radio" value="'+val.id+'" required="required">').fadeIn();
                  // $(".scheduleChoices").append('<input id="placementLang'+val.language_id+'" name="placementLang" type="radio" value="'+val.id+'" >').fadeIn();
                  if (val.is_online == 1) {
                    $(".scheduleChoices").append('<div class="input-group"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space">Online from '+ dateString +' to ' + dateStringEnd + '</label></div>').fadeIn();
                  } else {
                    $(".scheduleChoices").append('<div class="input-group"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space"> '+ dateString +' (in person)</label></div>').fadeIn();
                  }
              }); // end of $.each

              // if no schedule, tell student there is none
              if (!$("input[name='placementLang']").length){
                console.log('no schedule input');
                $("label[for='scheduleChoices']").html("<div class='alert alert-danger'>Sorry no placement test schedule available for this language</div>");
              } 

              // insert message of convocation email
              $('input[name="placementLang"]').on('click', function() {
                // $("textarea[name='course_preference_comment']").attr('required', 'required');
                $('.insert-msg').hide();
                $('.insert-msg').addClass('col-md-6 col-md-offset-3');     
                $('.insert-msg').html("<div class='alert alert-info'>You will receive further information from the Language Secretariat regarding the placement test.</div>").fadeIn();
              });
            }
      });
  // check if student is new or missed 2 terms 
      var L = $(this).val();
      var index = $("input[name='index_id']").val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();
      console.log(L);
      $.ajax({
          url: "{{ route('check-placement-course-ajax') }}", 
          method: 'POST',
          data: {L:L, index:index, term_id:term, _token:token},
          success: function(data) {
            console.log('take placement: ' + data);
            // if ($.isEmptyObject(data)) {
            if (data == true) { // check if student is new or missed 2 terms
              $("input[name='placementDecisionB']").prop('checked', false);
              $("input[name='placementDecisionB']").attr('required', 'required');
              $(".placementTestMsg").removeAttr('style');
              $(".placement-enrol").attr('style', 'display:none');
              $(".placement-beginner-msg").attr('style', 'display:none');
            }
            else {
              $("input[name='placementDecisionB']").removeAttr('required');
              $("input[name='placementLang']").removeAttr('required');
              $(".regular-enrol").removeAttr('style');
              $(".submission-part").removeAttr('style');
              $(".placementTestMsg").attr('style', 'display:none');
              $(".placement-enrol").attr('style', 'display:none');
              $(".placement-beginner-msg").attr('style', 'display:none');
                $.get("{{ route('check-enrolment-entries-ajax') }}", function(data) {
                      console.log('regular enrol form count:' + data);
                      if (data >= 2) {
                        alert('You are not allowed to submit more than 2 enrolment forms. You will now be redirected to the submitted forms page.');
                        $("#loader").fadeIn(500);
                        var delay = 2000;
                        setTimeout(function() {
                        var redirUrl = "{{ route('submitted') }}";
                        $(location).attr('href',redirUrl);
                        }, delay);
                      }
                    }); 
              return false;
            }
          }
      });
      $("#placementDecision3").prop('checked', false);
      $("select[name='course_id']").prop('disabled', false);
  });
  // when clicks YES I am a beginner
  $("#placementDecision3").on('click', function() {
      $.get("{{ route('check-enrolment-entries-ajax') }}", function(data) {
            console.log('regular enrol form count:' + data);
            if (data >= 2) {
              alert('You are not allowed to submit more than 2 enrolment forms. However, if you are not a complete beginner, you could submit a placement test form. The page will now reload.');
              $("#loader").fadeIn(500);
              // var redirUrl = "{{ route('submitted') }}";
              // $(location).attr('href',redirUrl);
              location.reload(true);
                
            }
          }); 
      $("input[name='placementLang']").removeAttr('required');
      var L = $("input[name='L']:checked").val();
      var token = $("input[name='_token']").val();
      console.log(L);
      $.ajax({
                url: "{{ route('select-ajax-level-one') }}", 
                method: 'POST',
                data: {L:L, _token:token},
                success: function(data, status) {
                  console.log(data);
                  $("select[name='course_id']").html('');
                  $("select[name='course_id']").html(data.options);
                  $("select[name='course_id']")[0].selectedIndex = 1; // select the first option
                  $("select[name='course_id'] option:not(:selected)").prop('disabled', true); // remove the other options
                  console.log($("select[name='course_id']").val());
                  // get schedule of selected language level 1 
                  var course_id = $("select[name='course_id']").val();
                  var term = $("input[name='term_id']").val();
                  var token = $("input[name='_token']").val();
                  console.log("course: "+course_id)
                  $.ajax({
                      url: "{{ route('select-ajax2') }}", 
                      method: 'POST',
                      data: {course_id:course_id, _token:token, term_id:term },
                      success: function(data) {
                        console.log(data)
                        $("select[name='schedule_id[]']").html('');
                        $("select[name='schedule_id[]']").html(data.options);
                      }
                  });
                }
            });

      $(".placementTestMsg").attr('style', 'display:none');
      $(".placement-beginner-msg").removeAttr('style');
      $(".regular-enrol").removeAttr('style'); // show div with select dropdown
      $(".submission-part").removeAttr('style');
      

  });
  // when student clicks NO I am not a beginner
  $("#placementDecision4").on('click', function() {
    $("input[name='placementDecisionB']").val("0");
      $.get("{{ route('check-placement-entries-ajax') }}", function(data) {
            console.log(data.length);
            if (data.length >= 2) {
              alert('You are not allowed to submit more than 2 placement test forms. The page will now reload.');
              $("#loader").fadeIn(500);
              // var redirUrl = "{{ route('whatorg') }}";
              // $(location).attr('href',redirUrl);
              location.reload(true);
            }
          }); 
    $(".placement-enrol").removeAttr('style');
    $(".placementTestMsg").hide();
    // if no schedule, tell student there is none
    if ($("input[name='placementLang']").length){
      console.log('there is input');
      $(".submission-part").removeAttr('style');
    }
  });
  $("input[name='agreementBtn']").on('click',function(){
      $(".disclaimer").addClass('alert-success', 500);
  }); 
</script>

<script src="{{ asset('js/customSelect2.js') }}"></script>

<script src="{{ asset('js/submit.js') }}"></script> 

<script>
  $(document).ready(function(){
    $('input[type=radio]').prop('checked',false);
    });
</script>

<script type="text/javascript">
  $("input[name='L']").click(function(){
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
            $("select[name='schedule_id[]']").html('');
            $("select[name='schedule_id[]']").html(data.options);
          }
      });
  }); 
</script>

@stop