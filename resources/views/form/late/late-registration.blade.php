@extends('layouts.late.late-main')
@section('tabtitle', 'Late Enrolment Form')
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
          <div class="card-header bg-danger text-white">Late Enrolment Form for: 
            <strong>
              @if(empty($next_term && $terms))
              NO DB ENTRY
              @else 
              {{ $terms->Term_Code.' - '.$terms->Term_Name.' - '.$terms->Comments.' Term' }}
              @endif
            </strong>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('store-late-registration') }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <div class="form-group col-md-10 col-md-offset-2">
                <input  name="CodeIndexID" type="hidden" value="" readonly>
                <input  name="term_id" type="hidden" value="
                  @if(empty($terms))
                  NO DB ENTRY
                  @else 
                  {{$terms->Term_Code}}
                  @endif
                " readonly>  
                </div>

                <input  name="index_id" class="form-control"  type="hidden" value="{{ Auth::user()->sddextr->INDEXNO }}" readonly>                                           

                @include('form.partials.studentDetailsSection')

                @if (Auth::user()->profile == "SPOUSE")
                  @if ($terms->Term_End > $user->contract_date)
                  <div class="form-group">
                      <div class="alert alert-default alert-block">
                        <div class="small text-danger col-md-offset-3">
                          <strong>Note: accepts pdf, doc, and docx files only. File size must less than 8MB.</strong>
                        </div>
                          @include('file_attachment_field.id-file-attachment')

                          @include('file_attachment_field.contract-file-attachment')

                          @include('file_attachment_field.multiple-file-attachment')
                  @else
                          <br />
                          <div class="small text-success col-md-offset-3">
                            <strong>Contract date ({{ $user->contract_date }}) still valid. </strong>
                          </div>
                  @endif

                        <div class="form-group col-md-12">
                              <div class="disclaimer-consent alert alert-default alert-block col-md-10 col-md-offset-1">
                                <p class="small text-danger"><strong>Required field</strong></p>
                                <input id="consentBtn" name="consentBtn" class="with-font" type="radio" value="1" required="required">
                                <label for="consentBtn" class="form-control-static">@if($user->profile != 'SPOUSE') By ticking this button, I confirm that my supervisor has approved my enrolment in the course. Or, if I don’t have any supervisor, I acknowledge that I will be able to attend the course. @else By ticking this option, I confirm I am  the spouse of a UN staff member. @endif
                                </label>
                              </div>
                        </div>  
                      </div>
                  </div>
                @else
                    
                @endif
                
                <!-- NO DECISION SECTION -->
                <div class="0 box">
                   
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

                    <div class="placementTestMsg " style="display: none">
                      <div class="alert alert-warning">
                        <p>Our records show that either you are a new student or you have not been enrolled on the selected language course during the past two terms.</p>
                        <p>You are required to take a <strong>placement test</strong> unless you are a complete beginner.</p>
                        
                        <div class="form-group">
                              <label class="col-md-12 control-label">Are you a complete beginner?</label>
                              <div class="d-flex justify-content-start">
                                <div class="col-md-3">
                                  <input id="placementDecision3" name="placementDecisionB" class="with-font" type="radio" value="1">
                                  <label for="placementDecision3" class="form-control-static">YES</label>
                                </div>

                                <div class="col-md-3">
                                  <input id="placementDecision4" name="placementDecisionB" class="with-font" type="radio" value="0">
                                  <label for="placementDecision4" class="form-control-static">NO</label>
                                </div>
                              </div>
                        </div>
                      </div>
                    </div>

                  <div class="placement-enrol" style="display: none"> {{-- start of placement test enrolment part --}}
                    @include('form.partials.placement_form.importantNote')

                    @include('form.partials.placement_form.testDates')

                    @include('form.partials.placement_form.coursePreference')

                  </div> {{-- end of placement test enrolment part --}}

                  <div class="placement-beginner-msg" style="display: none">
                    <div class="alert alert-info alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <p>Thank you for your response, {{Auth::user()->sddextr->FIRSTNAME}}.</p>
                      <p>You have answered <strong>YES</strong>. The beginner course for your selected language has been automatically filled in. Click the "Available for the following schedule(s)" field to check its availability.</p>
                    </div>
                  </div>

                  <div class="regular-enrol" style="display: none"> {{-- start of hidden fields --}}
                    
                    @include('form.partials.regular_form.courseSelect')

                    @include('form.partials.regular_form.availableCourseSchedule')

                    @include('form.partials.regular_form.flexibilityOptions') 
                    
                    @include('form.partials.regular_form.realTimeChoices') 
                    
                  </div> {{-- end of hidden fields --}}
                    
                  <div class="submission-part" style="display: none">
                    
                    <div class="form-group col-md-12">
                      <div class="disclaimer alert col-md-12">
                        <p class="small text-danger"><strong>Required field</strong></p>
                        <input id="approval" name="approval" class="with-font" type="radio" value="1" required="required">
                        <label for="approval" class="form-control-static">By ticking this button, I confirm that my supervisor has approved my enrolment in the course. </label>
                      </div>
                    </div>
                
                    <div class="form-group col-md-12">
                      <div class="disclaimer alert col-md-12">
                        <p class="small text-danger"><strong>Required field</strong></p>
                        <input id="agreementBtn" name="agreementBtn" class="with-font" type="radio" value="1" required="required">
                        <label for="agreementBtn" class="form-control-static">I have read and understood the <a href="http://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/LanguageCourses_en.pdf" target="_blank">Information Circular</a> regarding the Language Training Programme at UNOG.</label>
                      </div>
                    </div>
                    
                    <div class="form-group col-md-12">
                      <div class="disclaimer alert alert-danger col-md-12">
                        <h4 class="text-danger"><strong><i class="fa fa-warning"></i> Important Note:</strong></h4>
                        <p><strong>If you wish to enrol on two courses for the same term, you need to submit another form.</strong> However, if you wish to take one course, but are not sure which one to select, please indicate it in the comments box above. Do not fill in several forms. <strong>One form = one course</strong>.</p>
                      </div>
                    </div>

                    <div class="col-md-3 offset-md-5">
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
    let term = $("input[name='term_id']").val();
    $.get("/late-check-placement-form-ajax", { term: term }, function(data) {
      $.each(data, function(index, val) {
        console.log('placementFormLang = ' + val.L);
        $("input[name='L'][value='"+ val.L +"']").attr('disabled', true); // check if the student already submitted placement form
        $("input[name='L'][value='"+ val.L +"']:disabled").after("<span class='badge-pill text-danger'>Scheduled for placement test");
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
      $("textarea[name='regular_enrol_comment']").removeAttr('required'); // reset comment box as not required field
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
        $(".place-here").hide().append('<label for="scheduleChoices">Placement test date(s): <span class="text-danger"><em>(required)</em></span></label>').fadeIn('fast');
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
                    $(".scheduleChoices").append('<div class="input-group-prepend"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space">Online from '+ dateString +' to ' + dateStringEnd + '</label></div>').fadeIn();
                  } else {
                    $(".scheduleChoices").append('<div class="input-group-prepend"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space"> '+ dateString +'</label></div>').fadeIn();
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
                $('.insert-msg').addClass('col-md-12');     
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
          url: "{{ route('late-check-placement-course-ajax') }}", 
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
              $("textarea[name='regular_enrol_comment']").attr('required', 'required');
              $("input[name='placementDecisionB']").removeAttr('required');
              $("input[name='placementLang']").removeAttr('required');
              $(".regular-enrol").removeAttr('style');
              $(".submission-part").removeAttr('style');
              $(".placementTestMsg").attr('style', 'display:none');
              $(".placement-enrol").attr('style', 'display:none');
              $(".placement-beginner-msg").attr('style', 'display:none');
                $.get("{{ route('late-check-enrolment-entries-ajax') }}", { term: term }, function(data) {
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
      let term = $("input[name='term_id']").val();
      $("textarea[name='regular_enrol_comment']").attr('required', 'required');
      $.get("{{ route('late-check-enrolment-entries-ajax') }}", { term: term }, function(data) {
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
                  // $("select[name='course_id'] option:not(:selected)").remove(); // remove the other options
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
    let term = $("input[name='term_id']").val();
    $("input[name='placementDecisionB']").val("0");
    $("textarea[name='regular_enrol_comment']").removeAttr('required');
    $("textarea[name='course_preference_comment']").attr('required', 'required');
      $.get("{{ route('late-check-placement-entries-ajax') }}", { term: term }, function(data) {
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