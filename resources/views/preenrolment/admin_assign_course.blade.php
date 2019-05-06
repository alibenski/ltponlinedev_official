<div class="row">
	@foreach ($enrolment_details as $element)
    <div class="col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
            	<h4>Enrolment Form # {{ $element->eform_submit_count}} : {{ $element->terms->Comments }} {{ date('Y', strtotime($element->terms->Term_Begin)) }} [{{$element->Term}}]</h4>
            </div>
            <div class="box-body">
            	<div class="col-sm-6">
            		@if($placement_flag) 
            			<h4 class="text-danger"><i class="fa fa-flag"></i> Student placement exam for summer on-going </h4> 
            		@endif
					<p>Name: <strong>{{ $element->users->name }}</strong></p> 
	                <p>Language: <strong>{{ $element->languages->name }}</strong></p> 
	                <p>Course: <strong>{{ $element->courses->Description }}</strong></p>
					<p>Schedule(s):</p>
		                <ol>
						@foreach ($enrolment_schedules as $val)
			                @if ($val->eform_submit_count == $element->eform_submit_count)
				                <li><strong>{{ $val->schedule->name }}</strong></li>
			                @endif
						@endforeach    
		                </ol>
					<div class="form-group">
					    <label class="control-label" for="std_comments">Student Comments:</label>
					    <div class="">
					        <textarea class="form-control" name="std_comments" cols="40" rows="3" readonly  placeholder="no comment">{{ $element->std_comments }}</textarea>
					    </div>
					</div>
					<p>Flexible: 
						@if ( $element->flexibleBtn == 1)
						<strong>Yes</strong>
						@else
						<strong>No</strong>
						@endif
					</p>
					<p>
						Last language course taken: <strong>
							@if(empty($historical_data))

                                There were no historical records found.

                            @else
                                @if(empty($historical_data->Te_Code)) {{ $historical_data->coursesOld->Description }} 
                                @else {{ $historical_data->courses->Description }} 
                                @endif : {{ $historical_data->terms->Term_Name }} 
                                	<em>
                                	@if (empty($historical_data->classrooms))

                                	@else
	                                	@if (is_null($historical_data->classrooms->Tch_ID))
	                                		Waitlisted
	                                	@elseif($historical_data->classrooms->Tch_ID == 'TBD')
	                                		Waitlisted
	                                	@else
	                                		{{ $historical_data->classrooms->Tch_ID }}
	                                	@endif
                                	@endif
                                	</em>
                                	(@if($historical_data->Result == 'P') Passed @elseif($historical_data->Result == 'F') Failed @elseif($historical_data->Result == 'I') Incomplete @else -- @endif)
                            @endif 
                            </strong>
					</p>

	                <div class="form-group">
						<label class="control-label">Admin Comments: </label>

						<textarea id="textarea-{{$element->eform_submit_count}}" name="admin_eform_comment" class="form-control course-no-change" maxlength="3500" @if(is_null($element->admin_eform_comment)) placeholder="Place important information to note about this student, enrolment form, etc." @else placeholder="{{$element->admin_eform_comment}}" @endif></textarea>
						
					</div>

					<span id="{{$element->eform_submit_count}}" class="schedule-count btn-accept hidden">
	                	<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-accept-btn btn btn-success btn-space"><span><i class="fa fa-thumbs-up"></i></span> Accept  </button>		                	 	
	                </span>
	                
					@if(empty($element->updated_by_admin)) 
                	@else
						@if ($element->modifyUser)
			            <div class="callout callout-warning">
							Last update by:  {{ $element->modifyUser->name }} on {{ $element->updatedOn }} 
						</div>
						
						<div class="well">
							<p><strong>Change Logs (Student Originally Chose):</strong></p>
							@foreach ($modified_forms as $e)
								@foreach ($e as $v)
									@if ($v->eform_submit_count == $element->eform_submit_count)
										Form # {{ $v->eform_submit_count }}: 
										{{ $v->courses->Description }} 
										{{ $v->schedule->name }} <br>
									@endif
									
								@endforeach
							@endforeach	
						</div>

						@endif
					@endif
				</div>

				<div class="col-sm-6">
			        <form id="form-{{ $element->eform_submit_count }}" method="POST" action="" class="col-sm-12">
		                	{{ csrf_field() }}
		                <input name="INDEXID" type="hidden" class="modal-input" value="{{ $element->INDEXID }}">
		                <input name="L" type="hidden" class="modal-input" value="{{ $element->L }}">
		                <input name="eform_submit_count" type="hidden" class="modal-input" value="{{ $element->eform_submit_count }}">
		                <input name="Term" type="hidden" class="modal-input" value="{{ $element->Term }}">

						<div class="form-group">
							<p class="alert alert-warning">					
		                	To change course and/or schedule, fill in the fields below and click Modify
							</p>
		                	<label>Course:</label>


		                        <select id="{{$element->eform_submit_count}}" class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%; " name="Te_Code">
		                            <option value="">--- Select Course ---</option>
		                        </select>


		                </div>

		                <div class="form-group">
		                	<label>Schedule:</label>

		                        <select id="schedule-{{$element->eform_submit_count}}" class="col-sm-12 form-control schedule_select_no select2-basic-single" style="width: 100%; " name="schedule_id">
		                            <option value="">--- Select Here ---</option>
		                        </select>

		                </div>
						
		                <div class="form-group">
							<label class="control-label">Admin Comments: </label>

							<textarea id="textarea-{{$element->eform_submit_count}}" name="admin_eform_comment" class="form-control course-changed" maxlength="3500" @if(is_null($element->admin_eform_comment)) placeholder="Place important information to note about this student, enrolment form, etc." @else placeholder="{{$element->admin_eform_comment}}" @endif></textarea>
							
						</div>

		                <div class="form-group">		                

		                	<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-save-btn btn btn-warning btn-space pull-right"><span><i class="fa fa-exchange"></i></span> Modify </button>
			                
			                <input type="hidden" name="_token" value="{{ Session::token() }}">
			                {{ method_field('PUT') }}
		                </div>
			        </form>
		    	</div>
            </div>
        	<div class="overlay">
        		<i class="fa fa-refresh fa-spin"></i>
        	</div>
        </div>
    </div>
	@endforeach
</div>

<script type="text/javascript">
$(document).ready(function() {
  var INDEXID = $("input[name='INDEXID'].modal-input").val();
  var L = $("input[name='L'].modal-input").val();
  var term = $("input[name='Term'].modal-input").val();
  var token = $("input[name='_token']").val();

  var promises = [];
  $('.schedule-count').each(function(index, val) {
    var eform_submit_count = $(this).attr('id');

    console.log('eform_submit_count '+eform_submit_count)
    promises.push($.ajax({
      url: '{{ route('admin-check-schedule-count') }}',
      type: 'GET',
      data: {eform_submit_count:eform_submit_count, INDEXID:INDEXID, L:L, term_id:term, _token:token},
    })
    .done(function(data) {
      console.log(data)
      if (data == 1) {
        $('span#'+eform_submit_count+'.btn-accept').removeClass('hidden');
      }
      
    })
    .fail(function() {
      console.log("error");
      alert("Ooops! An error occured. Click OK to reload.");
            window.location.reload();
    })
    .always(function() {
      console.log("complete check schedule count for button");
    }));
  });

  $.when.apply($('.schedule-count'), promises).then(function() {
        $(".overlay").fadeOut(600);
    }); 


  $.ajax({
    url: "{{ route('select-ajax') }}", 
    method: 'POST',
    data: {L:L, term_id:term, _token:token},
    success: function(data, status) {
      $("select[name='Te_Code']").html('');
      $("select[name='Te_Code']").html(data.options);
    }
  }); 

  $("select[name='Te_Code']").on('change',function(){
    var course_id = $(this).val();
    var eform_submit_count = $(this).attr('id');

    $.ajax({
      url: "{{ route('select-ajax2') }}", 
      method: 'POST',
      data: {course_id:course_id, term_id:term, _token:token},
      success: function(data) {
        $("select#schedule-"+eform_submit_count+"[name='schedule_id']").html('');
        $("select#schedule-"+eform_submit_count+"[name='schedule_id']").html(data.options);
      }
    });
  });
});

$(document).on('click', '.show-modal-history', function() {
	$('.modal-title-history').text('Past Language Courses');
    $('#showModalHistory').modal('show'); 
});
</script>