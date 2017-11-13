@extends('main')
@section('tabtitle', '| Create New Class')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <h2>Create New Class for Term: {{ $terms->Term_Code.' - '.$terms->Term_Name.' - '.$terms->Comments }}</h2>
    <hr>

    <form method="POST" action="{{ route('classrooms.store') }}">
               
                <div class="form-group">
                    <label name="L" class="col-md-3 control-label">Language: </label>
                    <select class="col-md-8 form-control" name="L">
                        <option value="">--- Select Language ---</option>
                        @foreach ($languages as $id => $name)
                            <option value="{{ $id }}"> {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label name="course_id" class="col-md-3 control-label">Course & Level: </label>
                    <select class="combine col-md-8 form-control" name="course_id">
                        <option value="">--- Select Course ---</option>
                    </select>
                </div>

  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <th>Schedule</th>
          <th>Room</th>
          <th>Teacher</th>
        </thead>

        <tbody>
            <tr>
              <td>
                    <select class="combine  form-control select2-multi" name="schedule_id[]">
                            <option value=""> Select Schedule </option>
                        @foreach ($schedules as $id => $name)
                            <option value="{{ $id }}"> {{ $name }}</option>
                        @endforeach
                    </select>
              </td>
              <td>
                    <select class="combine  form-control select2-multi" name="room_id[]">
                            <option value=""> Select Room </option>
                        @foreach ($rooms as $id => $name)
                            <option value="{{ $id }}"> {{ $name }}</option>
                        @endforeach
                    </select>
              </td>
              <td>
                    <select class="combine  form-control select2-multi" name="schedule_id[]">
                            <option value=""> Select Teacher </option>
                            <option value="1"> Teacher A </option>
                            <option value="2"> Teacher B </option>
                            <option value="3"> Teacher C </option>
                            <option value="4"> Teacher D </option>
                    </select>
              </td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>




                <div class="form-group">
                    <label name="schedule_id" class="col-md-3 control-label">Assign Schedule: </label>
                    <select class="combine col-md-8 form-control select2-multi" name="schedule_id[]" multiple="multiple">
                        @foreach ($schedules as $id => $name)
                            <option value="{{ $id }}"> {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                  <input  name="term_id" class="combine" type="text" value="{{ $terms->Term_Code }}" readonly>
                  <input  id="UniqueCode" name="Code[]" class="combine" type="text" value="" readonly> 
                </div>

                <div class="row">
                  <div class="col-md-5 col-md-offset-1">
                    <a href="{{ route('classrooms.index') }}" class="btn btn-danger btn-block">Back</a>
                  </div>
                  <div class="col-md-5 ">  
                    <button id="setVal" type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save Classroom</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                  </div>
                </div>
    </form>
  </div>
</div>﻿
@endsection

@section('scripts_code')
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <script type="text/javascript">$(".select2-multi").select2(); </script>


  <script src="{{ asset('js/submit.js') }}"></script>      

  <script type="text/javascript">
    $("select[name='L']").change(function(){
        var L = $(this).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "{{ route('select-ajax') }}", 
            method: 'POST',
            data: {L:L, _token:token},
            success: function(data) {
              $("select[name='course_id'").html('');
              $("select[name='course_id'").html(data.options);
            }
        });
    }); 
  </script>

@stop
