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
                <input  name="term_id" type="hidden" value="
                  @if(empty($terms))
                  NO DB ENTRY
                  @else 
                  {{ $terms->Term_Next }}
                  @endif
                " readonly>  
                </div>
                current (hidden field) -> {{$terms->Term_Code}}
                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Index Number:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-qrcode"></i></span><input  name="index_id" class="form-control"  type="text" value="{{ Auth::user()->indexno }}" readonly>                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Name:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="" class="form-control"  type="text" value="{{ Auth::user()->name }}" readonly>                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="org" class="col-md-3 control-label">Organization:</label>
                  <div class="col-md-8">
                    <div class="dropdown">
                      <select name="org" id="input" class="col-md-8 form-control" required="required">
                        @if(!empty($org))
                          @foreach($org as $key => $value)
                            <option value="{{ $key }}" {{ ($user->sddextr->DEPT == $key) ? 'selected="selected"' : '' }}>{{ $value }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <p class="small text-danger"><strong>Please check that you belong to the correct Organization in this field.</strong></p>
                  </div>
                </div>

                <div class="form-group">
                    <label for="mgr_email" class="col-md-3 control-label">Manager's Name:</label>
                    
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
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="mgr_email" placeholder="Enter Manager's Email" class="form-control"  type="text" required="required">                                    
                        </div>
                         <p class="small text-danger"><strong>Enter the <u>correct email address</u> of your manager because this form will be sent to this email address for approval.</strong></p>
                    </div>
                </div>
             
                <div class="form-group">
                    <label for="name" class="col-md-3 control-label">Last/Current UN Language Course:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span><input  name="" class="form-control"  type="text" value="{{ $repos_lang->courses->EDescription}} last @if(empty($terms))NO DB ENTRY 
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
                          <label for="first" class="col-md-2 control-label" style="color: green;">First Choice:</label> 
                          <div class="col-md-8 form-control-static"><p id="first" name=""></p></div>
                        </div>

                        <div class="form-group">
                          <label for="second" class="col-md-2 control-label" style="color: #337ab7;">Second Choice:</label>
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
<script src="https://code.jquery.com/jquery-2.1.3.min.js"  integrity="sha256-ivk71nXhz9nsyFDoYoGf2sbjrR9ddh+XDkCcfZxjvcM="
  crossorigin="anonymous"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script>
  $(document).ready(function(){
      $(".wx").select2({
        //theme: "bootstrap",   
        minimumResultsForSearch: -1,
        placeholder: 'Choose Here',
      });
  }); 
</script>
<script type="text/javascript">
  $(document).ready(function(){
      $(".select2-multi").select2({
        //theme: "bootstrap",
        allowClear: true,
        minimumResultsForSearch: -1,
        maximumSelectionLength: 2,
        width: 'resolve', // need to override the changed default
        closeOnSelect: false,
        templateResult: formatResult,
        //templateSelection: formatResult, 
        placeholder: 'Choose Here',    
      }); 
            function formatResult (schedule) {
        if (!schedule.id) { return schedule.text; }
        
        var $schedule = $(
          '<i class="fa fa-plus-circle" aria-hidden="true"></i><span style="font-style:inherit; margin-left:10px;">'  + schedule.text + '</span>'
        );
        return $schedule;
      };
      // arrange in order of of being selected
      $(".select2-multi").on("select2:select", function (evt) {
        var element = evt.params.data.element;
        var $element = $(element);

        $element.detach();
        $(this).append($element);
        $(this).trigger("change");
      });

      $('.multi-clear').click(function() {
        $(".select2-multi").val(null).trigger("change"); 
        $("#first").empty();
        $("#second").empty();
      });
      // close dropdown list after 2 selections to override "closeOnSelect: false" param 
      $('.select2-multi').change(function(){
        var ele = $(this);
        if(ele.val()==null)
        {
          ele.select2('open');
        }
        else if(ele.val().length==2)
        {
          ele.select2('close');
        }
      });

  });
</script>

<script>
  $(document).ready(function(){
      // multi values, with last selected
      var old_values = [];
      var test = $(".select2-multi");

      test.on("select2:select", function(event) {
        var values = [];
        var values_index = [];
        var values_id = []; 
        //event.params.data.id; 
        // copy all option values from selected
        $(event.currentTarget).find("option:selected").each(function(i, selected){          
          values[i] = $(selected).text();
          values_index[i] = i;
          values_id[i] = $(selected).val();
        });

        var first =  values[0];
        var second =  values[1];
        var first_index =  values_index[0];
        var second_index =  values_index[1];
        var first_id =  values_id[0];
        var second_id =  values_id[1];

        if(first != null) {
          $("#first").text(first).css("color","green").attr("name",first_index).attr("data-id",first_id);
        }
        if(second != null) {
          $("#second").text(second).css("color","#337ab7").attr("name",second_index).attr("data-id",second_id);
        } else {
          $("#second").text("none");
        }
        // doing a diff of old_values gives the new values selected
        var last = $(values).not(old_values).get();
        // update old_values for future use
        old_values = values;
        // output values (all current values selected)
        //console.log("selected values: ", values);
        // output last added value
        //console.log("last added: ", last);
        });

      test.on("select2:unselect", function(e){
        //console.log(e);        console.log(e.params);         console.log(e.params.data);        
        var values_id = e.params.data.id;
        
        var elem_una = document.getElementById("first");
        var get_id_una = elem_una.getAttribute("data-id");

        var elem_dos = document.getElementById("second");
        var get_id_dos = elem_dos.getAttribute("data-id");
        var get_text_dos = elem_dos.innerHTML;
        var get_index_dos = elem_dos.getAttribute("name");

        if(values_id == get_id_una){
          //$("#first").removeAttr("value");
          $("#first").text(get_text_dos).attr("name",get_index_dos);
          $("#second").empty();
        } else if (values_id == get_id_dos){
          $("#second").empty();
          $( 'p[name="1"]' ).empty();
        } 

        });

  });
</script>

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
          url: "{{ route('select-ajax3') }}", 
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
          url: "{{ route('select-ajax4') }}", 
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