@if (count($enrolment_details) < 1)
<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
		<div class="alert alert-danger">
			<h4><i class="icon fa fa-ban"></i> Sorry, no forms were found!</h4>
		</div>
	</div>
</div>
@endif
<div class="row">
	@foreach ($enrolment_details as $element)
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border bg-aqua">
            	<h4>Enrolment Form # {{ $element->eform_submit_count}} - {{$next_term_string->Comments}} Term<span><button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="btn btn-danger btn-space course-delete pull-right" data-toggle="modal"><i class="fa fa-remove"></i> Delete Form</button></span></h4>
            	{{ method_field('DELETE') }}
            </div>
            <div class="box-body">
            	<div class="col-sm-6">

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

					@if($element->Term > 231)
						<p>Flexible Day? 
							@if(is_null($element->flexibleDay))
								-
							@elseif($element->flexibleDay === 1)
								<span class="badge label-success">Yes</span>
							@else
								<span class="badge label-danger">NOT FLEXIBLE</span>
							@endif
						</p>
						<p>Flexible Time? 
							@if(is_null($element->flexibleTime))
								-
							@elseif($element->flexibleTime === 1)
								<span class="badge label-success">Yes</span>
							@else
								<span class="badge label-danger">NOT FLEXIBLE</span>
							@endif
						</p>
					@else
						<p>Flexible Schedule (day/time):  
							@if ( $element->flexibleBtn == 1)
							<strong>Yes</strong>
							@else
							<strong>No</strong>
							@endif
						</p>
					@endif
						<p>Flexible Format (In-person or Online): 
							@if(is_null($element->flexibleFormat))
								-
							@elseif($element->flexibleFormat === 1)
								<span class="badge label-success">Yes</span>
							@else
								<span class="badge label-danger">NOT FLEXIBLE</span>
							@endif
						</p>

					<p>
						<button type="button" class="show-modal-history btn btn-info btn-space" data-toggle="modal"><span class="glyphicon glyphicon-time"></span>  View Course History</button>

						<!-- Modal form to show history -->
						<div id="showModalHistory" class="modal" role="dialog" data-backdrop="static" data-keyboard="false">
						    <div class="modal-dialog">
						        <div class="modal-content">
						            <div class="modal-header">
						                <button type="button" class="close" data-dismiss-inner-modal="modal2">×</button>
						                <h4 class="modal-title-history"></h4>
						            </div>
						            <div class="modal-body">
						                <div class="panel-body panel-info">
						                    @if($history->isEmpty())
						                    <div class="alert alert-warning">
						                        <p>There were no historical records found.</p>
						                    </div>
						                    @else
						                    <ul  class="list-group">
						                        @foreach($history as $hist_datum)
						                            <li class="list-group-item"><strong class="text-success">
						                            @if(empty($hist_datum))
						                            <div class="alert alert-warning">
						                                <p>There were no historical records found.</p>
						                            </div>
						                            @else
						                                @if(empty($hist_datum->Te_Code)) {{ $hist_datum->coursesOld->Description }} 
						                                @else {{ $hist_datum->courses->Description }} 
						                                @endif</strong> : {{ $hist_datum->terms->Term_Name }} 

						                                <em>
					                                	@if (empty($hist_datum->classrooms))
					                                	@else
						                                	@if (is_null($hist_datum->classrooms->Tch_ID))
						                                		Waitlisted
						                                	@elseif($hist_datum->classrooms->Tch_ID == 'TBD')
						                                		Waitlisted
						                                	@else
						                                		* {{ $hist_datum->classrooms->Tch_ID }} *
						                                	@endif
					                                	@endif
					                                	</em>

						                                (@if($hist_datum->Result == 'P') Passed @elseif($hist_datum->Result == 'F') Failed @elseif($hist_datum->Result == 'I') Incomplete @else -- @endif)</li>
						                            @endif
						                        @endforeach
						                    </ul>
						                    @endif
						                </div>						                	  
						            </div>
						            <div class="modal-footer">
						                <button type="button" class="btn btn-warning" data-dismiss-inner-modal="modal2">
						                    <span class='glyphicon glyphicon-remove'></span> Close
						                </button>
						            </div>
						        </div>
						    </div>
						</div>
					</p>
					<p>
						Last placement test taken: 
						<br>
						@if(empty($last_placement_test))
							<p class="text-danger"><strong>
							There were no placement test records found.
							</strong></p>
                        @else
							<ul class="list-group">
								<li class="list-group-item">
									<strong>{{ $last_placement_test->terms->Comments }} {{ $last_placement_test->terms->Term_Name }}</strong> : {{ $last_placement_test->languages->name }} Placement Test 
									<br><strong>Assessment/Result :</strong> {{ $last_placement_test->Result }}
									<br><strong>Assigned Course : </strong> @if ($last_placement_test->Te_Code) {{ $last_placement_test->courses->Description }} @endif
								</li>
							</ul>
                        @endif
					</p>
					<div class="form-group">
						<label class="control-label">Comments: </label>

						<textarea id="textarea-{{$element->eform_submit_count}}" name="teacher_comments" class="form-control course-no-change" maxlength="3500" @if(is_null($element->teacher_comments)) placeholder="2nd prefered course to take and any important information that the Language Secretariat needs to know..." @else placeholder="{{$element->teacher_comments}}" @endif></textarea>
						<br />
						<textarea id="textarea-{{$element->eform_submit_count}}" name="admin_eform_comment" class="form-control course-no-change" maxlength="3500" readonly @if(is_null($element->admin_eform_comment)) placeholder="This is a read-only field. No comments from focal point/secretariat." @else placeholder="{{$element->admin_eform_comment}}" @endif></textarea>
					</div>
						
					<span id="{{$element->eform_submit_count}}" class="schedule-count btn-accept hidden">
	                	<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-accept-btn btn btn-success btn-space"><span><i class="fa fa-thumbs-up"></i></span> Accept  </button>		                	 	
	                </span>

					<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-not-assign-btn btn btn-warning btn-space"><span><i class="fa fa-thumbs-down"></i></span> Verify and Not Assign </button>	
					
					@if(is_null($element->updated_by_admin)) 
                	@else
						<div class="callout callout-info">
							<p class="text-secondary"><strong>Status:</strong> 
							@if ( $element->updated_by_admin == 1 ) Verified and Assigned
							@elseif( $element->updated_by_admin == 0 ) Verified but Not Assigned
							@else Not Assigned
							@endif
							</p>
							@if ($element->modifyUser)
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
		                <input name="INDEXID" type="hidden" value="{{ $element->INDEXID }}">
		                <input name="L" type="hidden" value="{{ $element->L }}">
		                <input name="eform_submit_count" type="hidden" value="{{ $element->eform_submit_count }}">
		                <input name="Term" type="hidden" value="{{ $element->Term }}">

						<div class="form-group">
		                	<p class="alert alert-success">					
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
							<label class="control-label">Comments: </label>

							<textarea id="textarea-{{$element->eform_submit_count}}" name="teacher_comments" class="form-control course-changed" maxlength="3500" @if(is_null($element->teacher_comments)) placeholder="2nd prefered course to take and any important information that the Language Secretariat needs to know..." @else placeholder="{{$element->teacher_comments}}" @endif></textarea>
							
						</div>

		                <div class="form-group">

		                	<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-save-btn btn btn-success btn-space pull-right"><span><i class="fa fa-exchange"></i></span> Modify </button>
			                
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
  var INDEXID = $("input[name='INDEXID']").val();
  var L = $("input[name='L']").val();
  var term = $("input[name='Term']").val();
  var token = $("input[name='_token']").val();

  var promises = [];
  $('.schedule-count').each(function(index, val) {
    var eform_submit_count = $(this).attr('id');

    console.log('eform_submit_count '+eform_submit_count)
    promises.push($.ajax({
      url: '{{ route('teacher-check-schedule-count') }}',
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

$('button[data-dismiss-inner-modal="modal2"]').click(function () {
	$('#showModalHistory').modal('hide');
	$("body").addClass("modal-open");

});
</script>