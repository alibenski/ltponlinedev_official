@extends('admin.admin')

@section('content')

<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <h2>Create Course + Schedule for Term: {{ $next_term->Term_Code.' - '.$next_term->Term_Name.' - '.$next_term->Comments }}</h2>
    <h5 class="alert alert-info alert-block">On this page, language administrators create the Course-Schedule combinations BEFORE the Enrolment period</h5>
    <hr>

    <form method="POST" action="{{ route('course-schedule.store') }}">
               
                <div class="form-group">
                    <label name="L" class="col-md-3 control-label">Language: </label>
                    <select class="col-md-8 form-control" name="L" autocomplete="off" required="required">
                        <option value="">--- Select Language ---</option>
                        @foreach ($languages as $id => $name)
                            <option value="{{ $id }}"> {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="course_id" class="col-md-3 control-label">Course & Level: </label>
                      <div class="dropdown">
                        <select class="col-md-8 form-control" name="course_id" autocomplete="off" required="required">
                            <option value="">--- Select Course ---</option>
                        </select>
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
                  <input  name="term_id" class="combine" type="hidden" value="{{ $next_term->Term_Code }}" readonly>
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

<script>
  $(document).ready(function(){
    $('input[type=checkbox]').prop('checked',false);
  });
</script>

<script type="text/javascript">
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

@stop