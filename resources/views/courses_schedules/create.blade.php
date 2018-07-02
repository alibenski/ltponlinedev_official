@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <h2><i class="fa fa-calendar-o"></i> Create Course + Schedule</h2>
    <h5 class="alert alert-info alert-block">On this page, language administrators create the class schedules (Course-Schedule combinations) BEFORE the Enrolment period</h5>
    <hr>

    <form method="POST" action="{{ route('course-schedule.store') }}">
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Basic Info</strong>
            </div>
              <div class="panel-body">
                <div class="form-group">
                  <label name="term" class="col-md-3 control-label" style="margin: 5px 5px;">Term: </label>
                    <select class="col-md-8 form-control" name="term" autocomplete="off" required="required" style="width: 100%">
                        <option value="">--- Select Term ---</option>
                        @foreach ($terms as $value)
                            <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                  <label for="enrolment_duration" class="col-md-10 control-label" style="margin: 5px 5px;">Enrolment Duration: </label>
                  <div class="col-md-12 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i>  Begin</span><input  name="enrol_date_begin" class="form-control"  type="text" value="" readonly>                                    
                            <span class="input-group-addon"><i class="fa fa-calendar"></i>  End</span><input  name="enrol_date_end" class="form-control"  type="text" value="" readonly>                                    
                        </div>
                  </div>
                </div>  

                <div class="form-group">
                    <label name="L" class="col-md-3 control-label" style="margin: 5px 5px;">Language: </label>
                    <select class="col-md-8 form-control" name="L" autocomplete="off" required="required" style="width: 100%">
                        <option value="">--- Select Language ---</option>
                        @foreach ($languages as $id => $name)
                            <option value="{{ $id }}"> {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                  <label for="language_css" class="col-md-10 control-label" style="margin: 5px 5px;">Language CSS (Automatically Rendered based on Language selection above via JQuery): </label>
                  <div class="col-md-12 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-codepen"></i></span><input  name="language_css" class="form-control"  type="text" value="" readonly>                                    
                        </div>
                  </div>
                </div> 

                <div class="form-group">
                  <label for="availability_css" class="col-md-10 control-label" style="margin: 5px 5px;">Availability CSS (Automatically Rendered via Controller): </label>
                  <div class="col-md-12 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-codepen"></i></span><input  name="availability_css" class="form-control"  type="text" value="" readonly>                                    
                        </div>
                  </div>
                </div> 

                <div class="form-group">
                  <label for="website_language" class="col-md-10 control-label" style="margin: 5px 5px;">Website Language (Automatically Rendered via static HTML): </label>
                  <div class="col-md-12 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-codepen"></i></span><input  name="website_language" class="form-control"  type="text" value="" readonly>                                    
                        </div>
                  </div>
                </div> 

                <div class="form-group">
                    <label for="course_id" class="col-md-3 control-label" style="margin: 5px 5px;">Course: </label>
                      <div class="dropdown">
                        <select class="col-md-8 form-control" name="course_id" autocomplete="off" required="required" style="width: 100%">
                            <option value="">--- Select Course ---</option>
                        </select>
                      </div>
                </div>
              </div>
          </div>     
          <div class="well col-md-12" style="margin-top: 20px;">
              <div class="row">
                <div class="col-md-12">
                  <table class="table">
                    <thead>
                      <th>Format</th>
                      <th>Duration</th>
                    </thead>
                    <tbody>
                        <tr>
                          <td>
                                @foreach ($format as $id => $name)

                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="format_id" value="{{ $id }}" /> {{ $name }}
                                        </label>
                                    </div>

                                @endforeach
                          </td>
                          <td>
                                @foreach ($duration as $id => $name)

                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="duration_id" value="{{ $id }}" /> {{ $name }}
                                        </label>
                                    </div>

                                @endforeach
                          </td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>

          <div class="well col-md-12" style="margin-top: 20px;">
              <div class="row">
                <div class="col-md-12">
                  <table class="table">
                    <thead>
                      <th>Pick a Schedule</th>
                    </thead>
                    <tbody>
                        <tr>
                          <td>
                                @foreach ($schedules as $id => $name)

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="schedule_id[]" multiple="multiple" value="{{ $id }}" /> {{ $name }}
                                        </label>
                                    </div>

                                @endforeach
                          </td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
              <div class="well col-md-6 col-md-offset-3">
                <div class="form-group">
                  <input  name="cs_unique" class="combine select2-multi" multiple="multiple" type="hidden" value="" readonly> 
                </div>

                <div class="row">
                  <div class="col-md-5 col-md-offset-1">
                    <a href="{{ route('course-schedule.index') }}" class="btn btn-danger btn-block">Back</a>
                  </div>
                  <div class="col-md-5 ">  
                    <button id="setVal" type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                  </div>
                </div>
              </div>
    </form>
  </div>
</div>﻿

@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
  
<script>
  $(document).ready(function(){
    $('input[type=checkbox]').prop('checked',false);
    $('input[type=radio]').prop('checked',false);
    $('select').select2({
    placeholder: "--- Select Here ---",
    }
      );
  });
</script>

<script type="text/javascript">
  $("select[name='term']").change(function(){
      var term = $(this).val();
      var token = $("input[name='_token']").val();
      
      $.ajax({
          url: "{{ route('get-term-data-ajax') }}", 
          method: 'GET',
          data: {term:term, _token:token},
          success: function(data, status) {
            var m_names = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            var denrol_begin = data[0]['Enrol_Date_Begin'];
            var date = new Date(denrol_begin);
            var get_date = date.getDate();
            var get_month = date.getMonth();
            var get_year = date.getFullYear();
            var enrolBeginString = get_date + "-" + m_names[get_month] + "-" + get_year;

            var denrol_end = data[0]['Enrol_Date_End'];
            var date_end = new Date(denrol_end);
            var get_date_end = date_end.getDate();
            var get_month_end = date_end.getMonth();
            var get_year_end = date_end.getFullYear();
            var enrolEndString = get_date_end + "-" + m_names[get_month_end] + "-" + get_year_end;

            $("input[name='enrol_date_begin']").val(enrolBeginString);
            $("input[name='enrol_date_end']").val(enrolEndString);
          }
      });
  }); 

  $("select[name='L']").change(function(){
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
  $("select[name='L']").change(function(){
      var L = $(this).val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax-level-one') }}", 
          method: 'POST',
          data: {L:L, _token:token},
          success: function(data, status) {
            console.log(data)
            $("select[name='course_id']").html('');
            $("select[name='course_id']").html(data.options);
          }
      });
  }); 
</script>

@stop