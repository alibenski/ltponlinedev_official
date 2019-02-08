@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')

<h2><i class="fa fa-calendar-o"></i> {{ $course_schedule->course->Description }} - {{ $course_schedule->scheduler->begin_day }} - {{ $course_schedule->scheduler->time_combination }}
</h2>
<br>
<form method="POST" action="{{ route('course-schedule.update', $course_schedule->id) }}">
<div class="row">
	<div class="col-md-12">  
	<div class="panel panel-primary">
	<div class="panel-heading"><strong>Choose New Parameters</strong></div>
	<div class="panel-body">
		<div class="form-group">
		  <label name="L" class="col-md-3 control-label" style="margin: 5px 5px;">Language: </label>
		  <select class="col-md-8 form-control select2-one" name="L" autocomplete="off" required="required" style="width: 100%">
		      <option value="">--- Select Language ---</option>
		      @foreach ($languages as $id => $name)
		          <option value="{{ $id }}"> {{ $name }}</option>
		      @endforeach
		  </select>
		</div>

		<div class="form-group">
		  <label for="course_id" class="col-md-3 control-label" style="margin: 5px 5px;">Course: </label>
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
	<div class="panel-heading"><strong>Choose New Schedule</strong></div>
	<div class="panel-body">
	  <div class="row">
	    <div class="col-md-4 ">
	      <input type="button" value="Assign" id="buttonClass" class="btn btn-info btn-block btn-space">
	    </div>
	    <div class="col-md-12">
	      <div class="col-md-12">
	        @foreach ($schedules as $id => $name)
	            <div class="checkbox">
	                <label>
	                    <input id="box_value_{{ $id }}" type="checkbox" name="schedule_id[]" multiple="multiple" value="{{ $id }}" /> {{ $name }}
	                </label>
	            </div>
	            <div class="form-group teacher_div_{{ $id }}" style="display: none;">
	              <label name="Tch_ID" class="col-md-3 control-label" style="margin: 5px 5px;">Teachers: </label>
	                <select id="Tch_ID_select_{{ $id }}" class="col-md-8 form-control select2-multi" name="Tch_ID[]" multiple="multiple" autocomplete="off" style="width: 100%">
	                    <option value="">--- Select Teacher ---</option>
	                    @foreach ($teachers as $valueTeacher)
	                        <option value="{{$valueTeacher->Tch_ID}}">{{$valueTeacher->Tch_Name}} </option>
	                    @endforeach
	                </select>
	            </div>
	            <div class="form-group room_div_{{ $id }}" style="display: none;">
	              <label name="room_id" class="col-md-3 control-label" style="margin: 5px 5px;">Rooms: </label>
	              <select id="room_id_select_{{ $id }}" class="col-md-8 form-control select2-multi" name="room_id[]" multiple="multiple" autocomplete="off"  style="width: 100%">
	                  <option value="">--- Select Room ---</option>
	                  @foreach ($rooms as $valueRoom)
	                      <option value="{{$valueRoom->id}}">{{$valueRoom->Rl_Room}} </option>
	                  @endforeach
	              </select>
	            </div>
	        @endforeach
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
	      <button id="saveBtn" type="submit" class="btn btn-space btn-success btn-block button-prevent-multi-submit" disabled="">Save</button>
	      <input type="hidden" name="_token" value="{{ Session::token() }}">
	      {{ method_field('PUT') }}
	    </div>
	  </div>
	</div>
	</div>
	</div>
	<input  name="cs_unique" type="hidden" value="" readonly> 
</div>
</form>
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
  $(document).ready(function(){
    $('input[type=checkbox]').prop('checked',false);
    $('input[type=radio]').prop('checked',false);

    $('.select2-one').select2({
      placeholder: "--- Select Here ---",
    });
    
    $('.select2-multi').select2({
      placeholder: "--- Select Here ---",
      maximumSelectionLength: 1,
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

<script>
$(document).ready(function(){
  /* Get the checkboxes values based on the class attached to each check box */
  $("#buttonClass").click(function() {
      getValueUsingClass();
  });
  function getValueUsingClass(){
    /* declare an checkbox array */
    var chkArray = [];
    
    /* look for all checkbxoes that have name of 'schedule_id[]' attached to it and check if it was checked */
    $('input[name="schedule_id[]"]:checked').each(function() {
      chkArray.push($(this).val());
    });

    $.each(chkArray, function(index, val) {
      console.log(val)
      $('#Tch_ID_select_'+val).prop('required', true);
      $('#room_id_select_'+val).prop('required', true);
      $('.teacher_div_'+val).removeAttr('style');
      $('.room_div_'+val).removeAttr('style');
    });

    /* we join the array separated by the comma */
    var selected;
    selected = chkArray.join(',') ;
    
    /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
    if(selected.length > 0){
      console.log("You have selected " + selected)
      $('#saveBtn').removeAttr('disabled');
    }else{
      alert("Please at least check one of the checkbox");
      $('#saveBtn').attr('disabled', 'disabled');
    }
  }
});   
</script>

<script>
$(document).ready(function(){
  $('input[name="schedule_id[]"]').on('click', function() {
    var valueID = $(this).val();
      if (!$('#box_value_'+valueID).is(':checked')) {
        // console.log($('#Tch_ID_select_'+valueID))
        $('#Tch_ID_select_'+valueID).val([]).trigger('change'); // reset select2 value
        $('#room_id_select_'+valueID).val([]).trigger('change'); // reset select2 value
        $('#Tch_ID_select_'+valueID).prop('required', false);
        $('#room_id_select_'+valueID).prop('required', false);
        $('.teacher_div_'+valueID).attr('style', 'display: none;');
        $('.room_div_'+valueID).attr('style', 'display: none;');
      }
  });
});   
</script>
@stop