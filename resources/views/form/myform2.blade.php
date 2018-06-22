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
      <div class="panel panel-default">
          <div class="panel-heading">Enrolment Form for Semester: 
            <strong>
              @if(empty($next_term && $terms))
              NO DB ENTRY
              @else 
              {{ $terms->Term_Code.' - '.$terms->Term_Name.' - '.$terms->Comments.' Season' }}
              @endif
            </strong>
          </div>
          <div class="panel-body">
            <form method="POST" action="{{ route('noform.store') }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <div class="form-group col-md-10 col-md-offset-2">
                <input  name="CodeIndexID" type="hidden" value="" readonly>
                {{-- <input  name="user_id" type="hidden" value="{{ $repos }}" readonly> --}}
                <input  name="term_id" type="hidden" value="
                  @if(empty($terms))
                  NO DB ENTRY
                  @else 
                  {{ $terms->Term_Code }}
                  @endif
                " readonly>  
                </div>
                
                {{-- <div class="form-group">
                    <label for="" class="col-md-3 control-label">Index Number:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-qrcode"></i></span> --}}
                            <input  name="index_id" class="form-control"  type="hidden" value="{{ Auth::user()->sddextr->INDEXNO }}" readonly>                   
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
                        <p class="small text-danger"><strong>Please check that you belong to the correct Organization in this field.</strong></p>
                  </div>
                </div>

                <div class="form-group" style="@if(is_null($repos_lang)) display: none @else  @endif ">
                    <label for="name" class="col-md-3 control-label">Last/Current UN Language Course:</label>

                    <div class="col-md-8 inputGroupContainer">
                      @if(is_null($repos_lang)) None
                      @else
                        @foreach( $repos_lang as $value )
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-graduation-cap"></i></span><input  name="" class="form-control"  type="text" value="@if(empty($value->courses->EDescription) || is_null($value->courses->EDescription)) {{ $value->Te_Code }} @else {{ $value->courses->EDescription}} @endif last @if(empty($value->terms->Term_Name) || is_null($value->terms->Term_Name))NO DB ENTRY @else {{ $value->terms->Term_Name }} @endif" readonly>                                         
                          </div>
                        @endforeach
                      @endif
                    </div>
                </div> 

                <div class="form-group">
                  <label for="contractDate" class="col-md-3 control-label">Contract Expiry Date: </label>
                  <div class="input-group date form_datetime col-md-8" data-date="" data-date-format="dd MM yyyy" data-link-field="contractDate">
                          <input class="form-control" size="16" type="text" value="" readonly>
                          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                        <input type="hidden" name="contractDate" id="contractDate" value="" required="" />
                </div>

                <!-- NO DECISION SECTION -->
                <div class="0 box">
                   
                    <div class="form-group">
                        <label class="col-md-3 control-label">Enrol to which language:</label>
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
                        <p>Dear {{Auth::user()->sddextr->FIRSTNAME}},</p>
                        <p>Our records show that either you are a new student or you have not been enrolled on the selected language course during the past two terms.</p>
                        <p>You are required to take a <strong>Placement Test</strong> unless you are a complete beginner.</p>
                        
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
                    <div class="alert alert-info alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <p>Thank you for your response, {{Auth::user()->sddextr->FIRSTNAME}}.</p>
                      <p>You have answered <strong>NO</strong>. You are not a complete beginner on your selected language. Please select a date for your placement test from the options available. If you are unable to take the test, then you can apply again in the next enrolment period.</p> 
                      <p>At the end of this form, you have the option to fill out a comment box to express any concerns <strong>(e.g. preferred specialized course, time contraints, etc.)</strong>. Thank you for your cooperation.</p>
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
                          <label class="col-md-3 control-label">Comment: <i>(optional)</i></label>
                          <div class="col-md-8 ">
                          <textarea name="std_comment" class="form-control" maxlength="3500"></textarea>
                          </div>
                        </div>

                      </div>    
                    </div>


                  </div> {{-- end of placement test enrolment part --}}

                  <div class="placement-beginner-msg" style="display: none">
                    <div class="alert alert-info alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <p>Thank you for your response, {{Auth::user()->sddextr->FIRSTNAME}}.</p>
                      <p>You have answered <strong>YES</strong>. The beginner course for your selected language has been automatically filled in. Click the "Preferred class schedule" field to check their availability.</p>
                    </div>
                  </div>

                  <div class="regular-enrol" style="display: none"> {{-- start of hidden fields --}}

                    <div class="form-group">
                        <label for="course_id" class="col-md-3 control-label">Preferred course: </label>
                        <div class="col-md-8">
                          <div class="dropdown">
                            <select class="col-md-8 form-control course_select_no wx" style="width: 100%; display: none;" name="course_id" autocomplete="off">
                                <option value="">--- Select Course ---</option>
                            </select>
                          </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="schedule_id" class="col-md-3 control-label">Preferred class schedule (2 max): </label>
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
                        <label for="flexibleBtn" class="form-control-static">Please check this box if you will be flexible to take another schedule should the classes be full for this course. 
                        </label>
                      </div>
                    </div>  
                        <!-- SHOW CHOICES REAL TIME -->
                    <div class="col-md-12">
                      <div class="well">
                        <div class="row">        
                            <div class="form-group">
                              <label for="first" class="col-md-2 control-label" style="color: green;">Schedule 1st Choice:</label> 
                              <div class="col-md-8 form-control-static"><p id="first" name=""></p></div>
                            </div>

                            <div class="form-group">
                              <label for="second" class="col-md-2 control-label" style="color: #337ab7;">Schedule 2nd Choice:</label>
                              <div class="col-md-8 form-control-static"><p id="second"  name=""></p></div>
                            </div>
                        </div>    
                      </div>  
                    </div>
                        <!-- END OF SHOW CHOICES REAL TIME -->  
                  </div> {{-- end of hidden fields --}}   

                  <div class="submission-part" style="display: none"> 
                    <div class="form-group">
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
                    </div>

                    <div class="form-group col-md-12">
                          <div class="disclaimer alert col-md-8 col-md-offset-2">
                                    <p class="small text-danger"><strong>Required field</strong></p>
                                    <input id="agreementBtn" name="agreementBtn" class="with-font" type="radio" value="1" required="required">
                                    <label for="agreementBtn" class="form-control-static">I have read and understood the <a href="http://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/LanguageCourses_en.pdf" target="_blank">Information Circular ST/IC/Geneva/2017/6</a> regarding language courses at UNOG.</label>
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
<script>
 $(window).load(function(){
 $("#loader").fadeOut(500);
 });

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
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minView: 1, 
    });
    $.get("/check-placement-form-ajax", function(data) {
      $.each(data, function(index, val) {
        console.log('placementFormLang = ' + val.L);
        $("input[name='L'][value='"+ val.L +"']").attr('disabled', true);    // check if the student already submitted placement form
        $("input[name='L'][value='"+ val.L +"']:disabled").after("<span class='label label-danger'>Scheduled for Placement Test");
      });
    }); 
  });
</script>

<script>
  $("input[name='L']").on('click', function(){
      $(".regular-enrol").attr('style', 'display: none'); // initiate hidden div 
      $(".submission-part").attr('style', 'display: none');
      $("input[name='placementDecisionB']").val("");
  // populate placement schedules
      $("label[for='scheduleChoices']").remove();
      $(".scheduleChoices").remove();
      $('.insert-msg').remove();
      $('.insert-container').append('<div class="insert-msg"></div>')

      if ($(this).val() == 'F') {
        $(".place-here").hide().append('<label for="scheduleChoices">The French placement test is Online. You may take the test anytime between the dates indicated below. Click on the radio button if you agree:</label>').fadeIn('fast');
      } else {
        $(".place-here").hide().append('<label for="scheduleChoices">Available Placement Test Date(s):</label>').fadeIn('fast');
      }

      $(".place-here").hide().append('<div class="scheduleChoices col-md-12"></div>').fadeIn('fast');

      var L = $(this).val();
      var token = $("input[name='_token']").val();
      console.log(L);
      $.ajax({
          url: "{{ route('check-placement-sched-ajax') }}", 
          method: 'POST',
          data: {L:L, _token:token},
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
                  
                  $(".scheduleChoices").append('<input id="placementLang'+val.language_id+'" name="placementLang" type="radio" value="'+val.id+'" required="required">').fadeIn();
                  if ($("input[name='L']:checked").val() == 'F') {
                    $(".scheduleChoices").append('<label for="placementLang'+val.language_id+'" class="label-place-sched form-control-static btn-space">from '+ dateString +' to ' + dateStringEnd + '</label>'+'<br>').fadeIn();
                  } else {
                    $(".scheduleChoices").append('<label for="placementLang'+val.language_id+'" class="label-place-sched form-control-static btn-space"> '+ dateString +'</label>'+'<br>').fadeIn();
                  }
              }); // end of $.each
              // if no schedule, tell student there is none
              if (!$("input[name='placementLang']").length){
                console.log('no input');
                $("label[for='scheduleChoices']").html("<div class='alert alert-danger'>Sorry no placement test schedule available for this language</div>");
              } 

              // insert message of convocation email
              $('input[name="placementLang"]').on('click', function() {
                $('.insert-msg').hide();
                $('.insert-msg').addClass('col-md-6 col-md-offset-3');     
                $('.insert-msg').html("<div class='alert alert-info'>You will receive a convocation email from the Language Secretariat regarding the time and place of the placement test.</div>").fadeIn();
              });
            }
      });
  // check if student is new or missed 2 terms 
      var L = $(this).val();
      var index = $("input[name='index_id']").val();
      var token = $("input[name='_token']").val();
      console.log(L);
      $.ajax({
          url: "{{ route('check-placement-course-ajax') }}", 
          method: 'POST',
          data: {L:L, index:index, _token:token},
          success: function(data) {
            console.log(data);
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
                        var delay = 1000;
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
                  $("select[name='course_id'] option:not(:selected)").remove(); // remove the other options
                  console.log($("select[name='course_id']").val());
                }
            });

      $(".placementTestMsg").attr('style', 'display:none');
      $(".placement-beginner-msg").removeAttr('style');
      $(".regular-enrol").removeAttr('style'); // show div with select dropdown
      $(".submission-part").removeAttr('style');
      var course_id = $("select[name='course_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax2') }}", 
          method: 'POST',
          data: {course_id:course_id, _token:token},
          success: function(data) {
            $("select[name='schedule_id[]']").html('');
            $("select[name='schedule_id[]']").html(data.options);
          }
      });

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
            console.log(data);
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