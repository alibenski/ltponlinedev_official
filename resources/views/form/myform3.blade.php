@extends('main')
@section('tabtitle', '| Self-Pay Enrolment Form')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div id="loader">
</div>
      @component('form.modalcheck')
      @endcomponent
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading"><strong>Self Payment</strong> Enrolment Form for Semester: 
            <strong>
              @if(empty($next_term && $terms))
              NO DB ENTRY
              @else 
              {{ $terms->Term_Next.' - '.$next_term->Term_Name.' - '.$next_term->Comments.' Season' }}
              @endif
            </strong>
          </div>
          <div class="panel-body">
            <form method="POST" enctype="multipart/form-data" action="{{ route('selfpayform.store') }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <div class="form-group col-md-10 col-md-offset-2">
                <input  name="CodeIndexID" type="hidden" value="" readonly>
                <input  name="user_id" type="hidden" value="{{ $repos }}" readonly>
                <input  type="hidden" value="{{$terms->Term_Code}}">
                <input  name="term_id" type="hidden" value="
                  @if(empty($terms))
                  NO DB ENTRY
                  @else 
                  {{ $terms->Term_Next }}
                  @endif
                " readonly>  
                </div>

                {{-- <div class="form-group">
                    <label for="" class="col-md-3 control-label">Index Number:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-qrcode"></i></span> --}}
                            <input  name="index_id" class="form-control"  type="hidden" value="{{ Auth::user()->indexno }}" readonly>                                    
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
                  </div>
                </div>

                <div class="form-group">
                    <div class="alert alert-default alert-block">
                      <div class="small text-danger col-md-offset-3">
                        <strong>Note: accepts pdf, doc, and docx files only</strong>
                      </div>
                    
                      <div class="form-group">
                        <label for="identityfile" class="col-md-3 control-label">Upload Proof of Identity: </label>
                        <input name="identityfile" type="file" class="col-md-9 form-control-static" required="required">
                      </div>

                      <div class="form-group">
                        <label for="payfile" class="col-md-3 control-label">Upload Proof of Payment: </label>
                        <input name="payfile" type="file" class="col-md-8 form-control-static" required="required">
                      </div>
                    </div>
                </div>
                
                    <div class="form-group col-md-12">
                          <div class="disclaimer-consent alert col-md-10 col-md-offset-1">
                            <input id="consentBtn" name="consentBtn" class="with-font" type="radio" value="1" required="required">
                            <label for="consentBtn" class="form-control-static">@if($user->sddextr->torgan['Org name'] != '999') By attaching the proof of payment and checking this option, I confirm and assure that my manager and/or HR Staff and Development office approved this payment-based enrolment. @else By attaching the proof of payment and checking this option, I confirm and assure that I am a spouse of a UN staff member. @endif
                            </label>
                          </div>
                    </div>  

                  <div class="form-group" style="@if(is_null($repos_lang)) display: none @else  @endif ">
                      <label for="name" class="col-md-3 control-label">Last/Current UN Language Course:</label>

                      <div class="col-md-8 inputGroupContainer">
                        @if(is_null($repos_lang)) None
                        @else
                          @foreach( $repos_lang as $value )
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-graduation-cap"></i></span><input  name="" class="form-control"  type="text" value="@if(empty($value) || is_null($value))NO DB ENTRY @else {{ $value->courses->EDescription}} @endif last @if(empty($value) || is_null($value))NO DB ENTRY @else {{ $value->terms->Term_Name }} @endif" readonly>                           
                            </div>
                          @endforeach
                        @endif
                      </div>
                  </div>

{{--                 <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Next UN Language Course:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-book"></i></span><input  name="" class="form-control"  type="text" value="{{ $repos_lang->courses->next_level_desc }}" readonly>                            
                        </div>
                    </div>
                </div> --}}

                <!-- MAKE A DECISION SECTION -->

{{--                 <div class="form-group">
                    <label class="col-md-3 control-label">Continue current course?</label>

                      <div class="col-md-2">
                                <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" >
                                <label for="decision1" class="form-control-static">YES</label>
                      </div>

                      <div class="col-md-2">
                                <input id="decision2" name="decision" class="with-font dno" type="radio" value="0">
                                <label for="decision2" class="form-control-static">NO</label>
                      </div>
                </div> --}}

                <!-- YES DECISION SECTION -->
{{--                 <div class="1 box" style="display:none">
                    <div class="form-group">
                        <label for="L" class="col-md-3 control-label">Enrol to which language: </label>
                        <div class="col-md-8">
                          <div class="input-group col-md-9">
                            <input id="{{ $repos_lang->languages->name }}" name="" class="with-font lang_select_yes" type="radio" value="{{ $repos_lang->L}}">
                           
                            <label for="{{ $repos_lang->languages->name }}" class=" form-control-static">{{ $repos_lang->languages->name }}</label>
                          </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="course_id" class="col-md-3 control-label">Enrol to which course: </label>
                        <input id="{{ $repos_lang->courses->next_level_desc }}" name="" class="with-font course_select_yes" type="radio" value="{{ $repos_lang->courses->next_level }}">
                            
                            <label for="{{ $repos_lang->courses->next_level_desc }}" class=" form-control-static">{{ $repos_lang->courses->next_level_desc }}</label>
                    </div>

                    <div class="form-group">
                        <label for="schedule_id" class="col-md-3 control-label">Pick 2 (max) class schedules: </label>
                        <button type="button" class="multi-clear button btn btn-danger" style="margin-bottom: 5px;" aria-label="Programmatically clear Select2 options">Clear All</button>
                        <div class="col-md-8">
                          <div class="dropdown">
                            <select class="col-md-8 form-control schedule_select_yes select2-multi" multiple="multiple" style="width: 100%; display: none;" name="" autocomplete="off" >
                                <option value="">--- Select Schedule ---</option>
                            </select>
                          </div>
                        </div>
                    </div>
                </div> --}}
                <!-- END OF YES DECISION SECTION -->   

                <!-- NO DECISION SECTION -->
                <div class="0 box">
                   
                    <div class="form-group">
                        <label class="col-md-3 control-label">Enrol to which language:</label>
                              <div class="col-md-8">
                                  @foreach ($languages as $id => $name)
                                <div class="input-group col-md-9">
                                          
                                          <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
                                          
                                          <label for="{{ $name }}" class=" form-control-static">{{ $name }}</label>
                                </div>
                                  @endforeach
                              </div>
                    </div>

                    <div class="placementTestMsg " style="display: none">
                      <div class="alert alert-warning">
                        <p>Dear {{Auth::user()->sddextr->FIRSTNAME}},</p>
                        <p>Our records show that either you are a new student or you have not been enrolled on the selected language course during the past 2 terms.</p>
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

                  <div class="regular-enrol" style="display: none"> {{-- start of hidden fields --}}

                    <div class="form-group">
                        <label for="course_id" class="col-md-3 control-label">Enrol to which course: </label>
                        <div class="col-md-8">
                          <div class="dropdown">
                            <select class="col-md-8 form-control course_select_no" name="course_id" autocomplete="off">
                                <option value="">--- Select Course ---</option>
                            </select>
                          </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="schedule_id" class="col-md-3 control-label">Pick 2 (max) class schedules: </label>
                        <button type="button" class="multi-clear button btn btn-danger" style="margin-bottom: 5px;" aria-label="Programmatically clear Select2 options">Clear All</button>
                        <div class="col-md-8">
                          <div class="dropdown">
                            <select class="col-md-8 form-control schedule_select_no select2-multi" multiple="multiple" style="width: 100%; display: none;" name="schedule_id[]" autocomplete="off">
                                <option value="">Fill Out Language and Course Options</option>
                            </select>
                          </div>
                        </div>
                    </div>

                          <!-- SHOW CHOICES REAL TIME -->
                    <div class="col-md-12">
                      <div class="well">
                        <div class="row">        
                            <div class="form-group">
                              <label for="first" class="col-md-2 control-label" style="color: green;">Schedule First Choice:</label>
                              <div class="col-md-8 form-control-static"><p id="first" name=""></p></div>
                            </div>

                            <div class="form-group">
                              <label for="second" class="col-md-2 control-label" style="color: #337ab7;">Schedule Second Choice:</label>
                              <div class="col-md-8 form-control-static"><p id="second"  name=""></p></div>        
                            </div>
                        </div>    
                      </div>  
                    </div>
                          <!-- END OF SHOW CHOICES REAL TIME -->   
                    <div class="form-group col-md-12">
                          <div class="disclaimer alert col-md-8 col-md-offset-2">
                                    <input id="agreementBtn" name="agreementBtn" class="with-font" type="radio" value="1" required="required">
                                    <label for="agreementBtn" class="form-control-static">I have read and understood the <a href="http://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/LanguageCourses_en.pdf" target="_blank">Information Circular ST/IC/Geneva/2017/6</a> regarding language courses at UNOG.</label>
                          </div>
                    </div>

                    <div class="col-sm-2 col-sm-offset-5">
                      <button type="submit" class="btn btn-success button-prevent-multi-submit">Send Enrolment</button>
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                    </div>
                  </div> {{-- end of hidden fields --}}  
              </div>
                <!-- END OF NO DECISION SECTION -->
            </form>
          </div>
        </div>
    </div>
    </div>
  </div>
</div>

  </div>
</div>
@stop   

@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>

<script>
 $(window).load(function(){
 $("#loader").fadeOut(500);
 });
</script>

<script>  
  $(document).ready(function () {
      $('#modal-check').modal('show');
  });
</script>

<script>
  $(document).ready(function(){
    $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
  });
</script>

<script>
  $(document).ready(function() {
    $.get("/check-placement-form-ajax", function(data) {
      $.each(data, function(index, val) {
        console.log('placementFormLang = ' + val.L);
        $("input[name='L'][value='"+ val.L +"']").attr('disabled', true); // check if the student already submitted placement form
      });
    }); 
  });
</script>

<script>
  $("input[name='L']").on('click', function(){
      $(".regular-enrol").attr('style', 'display: none'); // initiate hidden div 
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
              $(".placementTestMsg").removeAttr('style');
              $("input[name='placementDecisionB']").attr('required', 'required');
            }
            else {
              $("input[name='placementDecisionB']").removeAttr('required');
              $(".placementTestMsg").attr('style', 'display:none');
              $(".regular-enrol").removeAttr('style');
              return false;
            }
          }
      });
      $("#placementDecision3").prop('checked', false);
      $("select[name='course_id']").prop('disabled', false);
  });
  $("#placementDecision3").on('click', function() {
      var L = $("input[name='L']:checked").val();
      console.log(L);
      $("select[name='course_id']")[0].selectedIndex = 1; // select the first option
      $("select[name='course_id'] option:not(:selected)").remove(); // remove the other options
      console.log($("select[name='course_id']").val());

      $(".regular-enrol").removeAttr('style'); // show div with select dropdown

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
  $("#placementDecision4").on('click', function() {
    $("#loader").fadeIn(500);
    alert('You will now be redirected to the Placement Test Schedule Form');
    var redirUrl = "{{ route('placementinfo') }}";
    $(location).attr('href',redirUrl);
  });
  $("input[name='agreementBtn']").on('click',function(){
      $(".disclaimer").addClass('alert-success', 500);
  });   
  $("input[name='consentBtn']").on('click',function(){
      $(".disclaimer-consent").addClass('alert-success', 500);
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
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {L:L, _token:token},
          success: function(data, status) {
            $("select[name='course_id']").html('');
            $("select[name='course_id']").html(data.options);
          }
      });
  }); 
</script>

<script type="text/javascript">
  $("select[name='course_id']").change(function(){

      var course_id = $(this).val();
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
</script>

@stop