<div class="row">
	@foreach ($enrolment_details as $element)
    <div class="col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
            	<h4>Enrolment Form # {{ $element->eform_submit_count}} : {{ $element->terms->Comments }} {{ date('Y', strtotime($element->terms->Term_Begin)) }} [{{$element->Term}}]<span>

            		<button id="{{$element->eform_submit_count}}" type="button" class="btn btn-danger btn-space open-course-delete-modal pull-right" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" data-toggle="modal"><i class="fa fa-trash-o"></i> Reject/Cancel</button>

            		</span></h4>
            </div>

            <div id="modalDeleteEnrolment-{{ $element->INDEXID }}-{{ $element->Te_Code }}-{{ $element->Term }}" class="modal fade delete-enrolment-form" role="dialog">
			    <div class="modal-dialog">
			        <div class="modal-content">

			            <div class="modal-header bg-danger">
			                <button type="button" class="close" data-button-id="modalDeleteEnrolment-{{ $element->INDEXID }}-{{ $element->Te_Code }}-{{ $element->Term }}" data-dismiss-cancel-modal="modal3" aria-hidden="true" style="text: white;">&times;</button>
			                <h4 class="modal-title">Rejection / Cancellation</h4>
			            </div>
			            <div class="modal-body-course-delete">
			            	<div class="col-sm-12">	

								<p>Index # {{ $element->INDEXID }} : {{ $element->users->name }}</p>
								<p>Language: {{ $element->languages->name }}</p>
								<p>Course : {{ $element->courses->Description }}</p>
			            		<div class="form-group">
									<label class="control-label">Cancellation Comment: </label>

									<textarea id="course-delete-textarea-{{$element->eform_submit_count}}" name="admin_eform_cancel_comment" class="form-control course-delete-by-admin" maxlength="3500" placeholder="Place important information about the cancellation of this form..."></textarea>
									
								</div>

			            		<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="btn btn-danger btn-space course-delete pull-right"><i class="fa fa-trash"></i> Delete Form</button>
								<input type="hidden" name="deleteTerm" value="{{ $element->Term }}">
				                <input type="hidden" name="_token" value="{{ Session::token() }}">
				                {{ method_field('DELETE') }}
			            	</div>
			            </div>
			            <div class="modal-footer modal-background"></div>
			        </div>
			    </div>
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
					<p>
						Last language course taken: <strong>
						@if(empty($historical_data))
							<p class="text-danger"><strong>
                            There were no historical records found.
							</strong></p>
                        @else
                        	<ul class="list-group">
								<li class="list-group-item">
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
                            	</li>
                            </ul>	
                        @endif 
                        </strong>
                        <button type="button" class="show-modal-history btn btn-info btn-space" data-toggle="modal"><span class="glyphicon glyphicon-time"></span>  View Course History</button>

                        <!-- Modal form to show history -->
						<div id="showModalHistory" class="modal" role="dialog">
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

	$.ajax({
    	url: '{{ route('ajax-check-batch-has-ran') }}',
    	type: 'GET',
    	data: {Term:term,_token: token},
    })
    .done(function(data) {
    	if (!jQuery.isEmptyObject( data )) {
    		$("button.modal-accept-btn").addClass('hidden');
    		$("button.modal-save-btn").addClass('hidden');
    		$("button.open-course-delete-modal").addClass('hidden');
    	}
    })
    .fail(function() {
    	console.log("error");
    })
    .always(function() {
    	console.log("complete check if batch has ran");
    });

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

	$('button[data-dismiss-inner-modal="modal2"]').click(function () {
	    $('#showModalHistory').modal('hide');
	  });

	$('button[data-dismiss-cancel-modal="modal3"]').click(function () {
		var closeButton = $(this).attr('data-button-id');
	    $('#'+closeButton).modal('hide');
	  });
</script>