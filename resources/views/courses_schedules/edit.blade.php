@extends('admin.admin')

@section('customcss')
	<link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')

<h2><i class="fa fa-calendar-o"></i> {{ $course_schedule->course->Description }} - {{ $course_schedule->scheduler->begin_day }} - {{ $course_schedule->scheduler->time_combination }}
</h2>
<br>
<form method="POST" action="{{ route('course-schedule.update', $course_schedule->id) }}" class="form-prevent-multi-submit">
<div class="row">
	<div class="col-md-12">  
	<div class="panel panel-primary">
	<div class="panel-heading"><strong>Choose New Parameters</strong></div>
	<div class="panel-body">

		<div class="form-group">
		  <label name="L" class="control-label" style="margin: 5px 5px;">Language: </label>
		  <select class="col-md-8 form-control select2-one" name="L" autocomplete="off" required="required" style="width: 100%">
		      <option value="">--- Select Language ---</option>
		      @foreach ($languages as $id => $name)
		          <option value="{{ $id }}"> {{ $name }}</option>
		      @endforeach
		  </select>
		</div>

		<div class="form-group">
		  <label for="course_id" class="control-label" style="margin: 5px 5px;">Course: </label>
		    <div class="dropdown">
		      <select class="col-md-8 form-control select2-one" name="course_id" autocomplete="off" required="required" style="width: 100%">
		          <option value="">--- Select Course ---</option>
		      </select>
		    </div>
		</div>
	</div>
	</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">  
	<div class="panel panel-primary">
	<div class="panel-heading"><strong>Choose New Schedule Teacher Room</strong></div>
	<div class="panel-body">
	  <div class="row">
	    <div class="col-md-12">

	            <div class="form-group">
	                <label class="control-label" style="margin: 5px 5px;">Schedule: </label>
	        		<select id="box_value_{{ $id }}" class="col-md-8 form-control select2-one" name="schedule_id"  autocomplete="off" style="width: 100%">
	                    <option value="">--- Select Schedule ---</option>
	        			@foreach ($schedules as $id => $name)
	                    <option value="{{ $id }}">{{ $name }}</option>
	        			@endforeach
	        		</select>
	            </div>
	            
	            <div class="form-group teacher_div_{{ $id }}">
	              <label name="Tch_ID" class="control-label" style="margin: 5px 5px;">Teacher: </label>
	                <select id="Tch_ID_select_{{ $id }}" class="col-md-8 form-control select2-one" name="Tch_ID"  autocomplete="off" style="width: 100%">
	                    <option value="">--- Select Teacher ---</option>
	                    @foreach ($teachers as $valueTeacher)
	                        <option value="{{$valueTeacher->Tch_ID}}">{{$valueTeacher->Tch_Name}} </option>
	                    @endforeach
	                </select>
	            </div>

	            <div class="form-group room_div_{{ $id }}">
	              <label name="room_id" class="control-label" style="margin: 5px 5px;">Room: </label>
	              <select id="room_id_select_{{ $id }}" class="col-md-8 form-control select2-one" name="room_id"  autocomplete="off"  style="width: 100%">
	                  <option value="">--- Select Room ---</option>
	                  @foreach ($rooms as $valueRoom)
	                      <option value="{{$valueRoom->id}}">{{$valueRoom->Rl_Room}} </option>
	                  @endforeach
	              </select>
	            </div>               
	    </div>
	  </div>
	</div>
	</div>
	</div>

	<div class="col-md-6">
	<div class="panel panel-info">
	<div class="panel-heading">Operation</div>
	<div class="panel-body">
	  <div class="row">
	    <div class="col-md-5 col-md-offset-1">
	      <a href="{{ route('course-schedule.index') }}" class="btn btn-danger btn-block btn-space">Back</a>
	    </div>
	    <div class="col-md-5">  
	      <button id="saveBtn" type="submit" class="btn btn-space btn-success btn-block button-prevent-multi-submit">Save</button>
	      <input type="hidden" name="_token" value="{{ Session::token() }}">
	      {{ method_field('PUT') }}
	    </div>
	  </div>
	</div>
	</div>
	</div>
	<input  name="cs_unique" type="hidden" value="{{$course_schedule->cs_unique}}"> 
</div>
</form>
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/submit.js') }}"></script>
<script>
  $(document).ready(function(){
    $('.select2-one').select2({
      placeholder: "--- Select Here ---",
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