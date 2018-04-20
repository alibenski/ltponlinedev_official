@extends('main')
@section('tabtitle', '| UN Enrolment Form')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
    {{-- <div class="col-md-12">
      <div class="alert alert-warning alert-block">
        <p class="text-center"><strong>Second Choice Enrolment Form</strong></p>
      </div>
    </div>  --}} 

    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">Enrolment Form for Semester: 
            <strong>
              @if(empty($next_term && $terms))
              NO DB ENTRY
              @else 
              {{ $terms->Term_Next.' - '.$next_term->Term_Name.' - '.$next_term->Comments.' Season' }}
              @endif
            </strong>
          </div>
          <div class="panel-body">
            <form method="POST" action="{{ route('noform.store') }}" class="form-horizontal form-prevent-multi-submit">
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
                
                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Index Number:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-qrcode"></i></span><input  name="index_id" class="form-control"  type="text" value="{{ Auth::user()->sddextr->INDEXNO }}" readonly>                                    
                        </div>
                    </div>
                </div>

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
                            <span class="input-group-addon"><i class="fa fa-globe"></i></span><input  name="org" class="form-control"  type="text" value="{{ $user->sddextr->DEPT }}" readonly>                                    
                        </div>
                    {{-- <div class="dropdown">
                      <select name="org" id="input" class="col-md-8 form-control" required="required">
                        @if(!empty($org))
                          @foreach($org as $key => $value)
                            <option value="{{ $key }}" {{ ($user->sddextr->DEPT == $key) ? 'selected="selected"' : '' }}>{{ $value }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <p class="small text-danger"><strong>Please check that you belong to the correct Organization in this field.</strong></p> --}}
                  </div>
                </div>

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
             
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Last/Current UN Language Course:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-graduation-cap"></i></span><input  name="" class="form-control"  type="text" value="{{ $repos_lang->courses->EDescription}} last @if(empty($terms))NO DB ENTRY 
                              @else{{ $terms->Term_Name }}
                              @endif
                              " readonly>                            
                        </div>
                    </div>
                </div> 

                <!-- NO DECISION SECTION -->
                <div class="0 box">
                   
                    <div class="form-group">
                        <label class="col-md-3 control-label">Enrol to which language:</label>
                              <div class="col-md-8">
                                  @foreach ($languages as $id => $name)
                                <div class="input-group col-md-9">
                                          
                                          <input id="{{ $name }}" name="L" class="lang_select_no" type="radio" value="{{ $id }}">
                                          
                                          <label for="{{ $name }}" class=" form-control-static">{{ $name }}</label>
                                </div>
                                  @endforeach
                              </div>
                    </div>

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
                </div>
                <!-- END OF NO DECISION SECTION -->

                        <!-- SHOW CHOICES REAL TIME -->
                <div class="col-md-12">
                  <div class="well">
                    <div class="row">        
                        <div class="form-group">
                          <label for="first" class="col-md-2 control-label" style="color: green;">Schedule Choice A:</label> 
                          <div class="col-md-8 form-control-static"><p id="first" name=""></p></div>
                        </div>

                        <div class="form-group">
                          <label for="second" class="col-md-2 control-label" style="color: #337ab7;">Schedule Choice B:</label>
                          <div class="col-md-8 form-control-static"><p id="second"  name=""></p></div>
                        </div>
                    </div>    
                  </div>  
                </div>
                        <!-- END OF SHOW CHOICES REAL TIME -->   
                <div class="col-sm-offset-5">
                  <button type="submit" class="btn btn-success button-prevent-multi-submit">Send Enrolment</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
                </div>
            </form>
          </div>
        </div>
    </div>
    </div>
  </div>
</div>
@stop   

@section('scripts_code')

<script src="{{ asset('js/select2.full.js') }}"></script>

<script>
  $("input[name='L']").on('click', function(){
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
            if (data == true) {
              $(".placementTestMsg").removeAttr('style');
              $("input[name='placementDecisionB']").attr('required', 'required');
            }
            else {
              $("input[name='placementDecisionB']").removeAttr('required');
              $(".placementTestMsg").attr('style', 'display:none');
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
    var redirUrl = "{{ route('placementinfo') }}";
    $(location).attr('href',redirUrl);
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
  $("select[name='course_id']").on('change',function(){

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