@extends('admin.no_sidebar_admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="alert col-sm-12">
    <h3 class="text-center"><i class="fa fa-pencil-square-o"></i><strong> Edit Classroom </strong></h3>
</div>

<form class="form-horizontal" role="form" method="POST" action="{{ route('classrooms.update', $classroom->id) }}">{{ csrf_field() }}
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-body">
			    <div class="form-group">
			        <div class="col-sm-12">
			        <label class="control-label" for="id">Classroom ID:</label>
			            <input type="text" class="form-control class-id" value="{{$classroom->id}}" disabled>
			        </div>
			    </div>
			    <div class="form-group">
			        <div class="col-sm-12">
			        <label class="control-label" for="title">Title:</label>
			            <input type="text" class="form-control" value="{{ $classroom->course->Description }} {{ $classroom->scheduler->name }}" disabled>
			        </div>
			    </div>
			</div>
		</div>
	</div>

    <div class="form-group">
        <div class="col-sm-4 add-margin">
			<div class="panel panel-primary">
				<div class="panel-heading"><strong>Existing Values</strong></div>
		        <div class="panel-body existing-content">
		            <p>Teacher: @if($classroom->Tch_ID) <strong>{{ $classroom->teachers->Tch_Name }}</strong> @else <span class="label label-danger">none assigned</span> @endif</p>
						@if(!empty($classroom->Te_Mon_Room))
						<p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
						<p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
						<p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>
						@endif
						@if(!empty($classroom->Te_Tue_Room))
						<p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
						<p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
						<p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>
						@endif
						@if(!empty($classroom->Te_Wed_Room))
						<p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
						<p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
						<p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>
						@endif
						@if(!empty($classroom->Te_Thu_Room))
						<p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
						<p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
						<p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>
						@endif
						@if(!empty($classroom->Te_Fri_Room))
						<p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
						<p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
						<p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>
						@endif
		        </div>
		    </div>
        </div>

        <div class="col-sm-5 add-margin">
        	<div class="panel panel-info">
				<div class="panel-heading"><strong>Students [Class Code: {{ $classroom->Code }}]</strong></div>
		        <div class="panel-body">
		            @foreach ($students->chunk(4) as $element)
		            	<div class="col-sm-3">
			            	@foreach ($element as $student)
			            		<p>{{ $student->users->name }}</p>
			            	@endforeach
		            	</div>
		            @endforeach
		        </div>
		    </div>
        </div>
    </div>

	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">  
				<div class="panel panel-primary">
				<div class="panel-heading"><strong>Choose Schedule</strong></div>
					<div class="panel-body">
					  <div class="row">
					    <div class="col-md-2">
					      <input type="button" value="Assign" id="buttonClass" class="btn btn-info btn-block btn-space">
					    </div>
						<div class="col-md-2 pull-right">
						    <button id="saveBtn" type="submit" class="btn btn-success btn-space" disabled=""><i class='fa fa-save'></i> Save</button>
						    <input type="hidden" name="_token" value="{{ Session::token() }}">
						    {{ method_field('PUT') }}
						    <a href="{{ route('classrooms.index') }}" class="btn btn-danger btn-space">
					            <i class='fa fa-rotate-left'></i> Back
					        </a>
						</div>

					    <div class="col-md-12">
					      <div class="col-md-12">
					        @foreach ($schedules as $schedule)
					        <div class="col-sm-2">
						        @foreach ($schedule as $id => $name)
						            <div class="checkbox">
						                <label>
						                    <input id="box_value_{{ $id }}" type="checkbox" name="schedule_id" multiple="multiple" value="{{ $id }}" /> {{ $name }}
						                </label>
						            </div>

						            <div class="form-group teacher_div_{{ $id }}" style="display: none;">
						              <label name="Tch_ID" class="col-md-3 control-label" style="margin: 5px 5px;">Teachers: </label>
						                <select id="Tch_ID_select_{{ $id }}" class="col-md-8 form-control select2-multi" name="Tch_ID" multiple="multiple" autocomplete="off" style="width: 100%">
						                    <option value="">--- Select Teacher ---</option>
						                    @foreach ($teachers as $valueTeacher)
						                        <option value="{{$valueTeacher->Tch_ID}}">{{$valueTeacher->Tch_Name}} </option>
						                    @endforeach
						                </select>
						            </div>
									
									<div class="sched-days schedule-days-{{$id}}"></div>
						        @endforeach
					        </div>
					        @endforeach
					      </div>                  
					    </div>
					  </div>
					</div>
				</div>
			</div>
		</div>
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

<script>
$(document).ready(function(){
  /* Get the checkboxes values based on the class attached to each check box */
  $("#buttonClass").click(function() {
      getScheduleDays();
      getValueUsingClass();
  });

  function getScheduleDays() {
	var scheduleID = $('input[name="schedule_id"]:checked').val();
	var token = $("input[name='_token']").val();

	$.ajax({
		url: '{{ route('get-schedule-days') }}',
		type: 'GET',
		data: {id:scheduleID, _token:token},
	})
	.done(function(data) {
		$('.schedule-days-'+scheduleID).html('');
		$('.schedule-days-'+scheduleID).html(data);
		$('.select2-one').select2({
	      placeholder: "--- Select Here ---",
	    });
	    
	    $('.select2-multi').select2({
	      placeholder: "--- Select Here ---",
	      maximumSelectionLength: 1,
	    });
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
  }

  function getValueUsingClass(){
    /* declare an checkbox array */
    var chkArray = [];
    
    /* look for all checkboxes that have name of 'schedule_id' attached to it and check if it was checked */
    $('input[name="schedule_id"]:checked').each(function() {
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
  $('input[name="schedule_id"]').on('click', function() {
    var valueID = $(this).val();
      if (!$('#box_value_'+valueID).is(':checked')) {
        // console.log($('#Tch_ID_select_'+valueID))
        $('#Tch_ID_select_'+valueID).val([]).trigger('change'); // reset select2 value
        $('#room_id_select_'+valueID).val([]).trigger('change'); // reset select2 value
        $('#Tch_ID_select_'+valueID).prop('required', false);
        $('#room_id_select_'+valueID).prop('required', false);
        $('.teacher_div_'+valueID).attr('style', 'display: none;');
        $('.room_div_'+valueID).attr('style', 'display: none;');
        $('.schedule-days-'+valueID).html('');
      }
  });

  $('input[name="schedule_id"]').on('change', function (e) {
	    if ($('input[name="schedule_id"]:checked').length > 1) {
	        $(this).prop('checked', false);
	        alert("Only 1 schedule is allowed for this class.");
	    }
  });
});   
</script>
@stop