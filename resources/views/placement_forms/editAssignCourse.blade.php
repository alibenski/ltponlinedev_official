@extends('shared_template')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<div class="alert bg-gray col-sm-12">
		<h4 class="text-center"><i class="fa fa-pencil-square-o"></i><strong> Assign Language Course To Placement Form</strong></h4>
	</div>
</div>

@if($placement_form->assigned_to_course === 1)
<div class="row">
	<div class="callout callout-info col-sm-12">
		<h4><i class="fa fa-check-square-o"></i> Current Course Assigned:  {{ $placement_form->courses->Description }} - {{ $placement_form->schedule->name }}</h4>
	</div>
</div>
@elseif($placement_form->assigned_to_course === 0)
<div class="row">
	<div class="callout callout-warning col-sm-12">
		<h4>Verified and not assigned by  {{ $placement_form->modifyUser->name }}</h4>
	</div>
</div>
@else
<div class="row">
	<div class="callout callout-danger col-sm-12">
		<h4>No language course assigned </h4>
	</div>
</div>
@endif

<div class="row">
	<form method="POST" action="{{ route('placement-form-assign-course', $placement_form->id) }}">
	{{ csrf_field() }}
	<input name="INDEXID" type="hidden" value="{{ $placement_form->INDEXID }}">
	<input name="L" type="hidden" value="{{ $placement_form->L }}">
	<input name="Term" type="hidden" value="{{ $placement_form->Term }}">

	<div class="col-sm-6"> <!-- 1st SECTION -->
		<div class="form-group">
		    <label class="control-label" for="id_show">Name:</label>
		    <div class="">
		        <input name="nom" type="text" class="form-control"  value="{{ $placement_form->users->name }}" readonly>
		    </div>
		</div>
		<div class="form-group">
		    <label class="control-label" for="email">Email:</label>
		    <div class="">
		        <input name="email" type="text" class="form-control"  value="{{ $placement_form->users->email }}" readonly>
		    </div>
		</div>
		<div class="form-group">
		    <label class="control-label" for="contact_num">Contact Number:</label>
		    <div class="">
		        <input name="contact_num" type="text" class="form-control"  value="{{ $placement_form->users->sddextr->PHONE }}" readonly>
		    </div>
		</div>
		<div class="form-group">
		    <label class="control-label" for="course_show">Language:</label>
		    <div class="">
		        <input type="text" class="form-control" name="course_show" value="{{ $placement_form->languages->name }}" readonly>
		    </div>
		</div>

		@if(is_null($placement_form->is_self_pay_form))
		@else
		<div class="form-group">
			<label class="control-label" for="org_show">ID Proof:</label>
			<td>@if(empty($placement_form->filesId->path)) None @else <a href="{{ Storage::url($placement_form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif </td>	
		</div>
		<div class="form-group">	
			<label class="control-label" for="org_show">Payment Proof:</label>
			<td>@if(empty($placement_form->filesPay->path)) None @else <a href="{{ Storage::url($placement_form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif </td>
		</div>
		@endif	

		<div class="form-group">
		    <label class="control-label" for="profile_show">Profile:</label>
		    <div class="">
		        <input type="text" class="form-control" name="profile_show" value="{{ $placement_form->profile }}" readonly>
		    </div>
		</div>
		<div class="form-group">
		    <label class="control-label" for="org_show">Organization:</label>
		    <div class="">
		        <input type="text" class="form-control" name="org_show" value="{{ $placement_form->DEPT }}" readonly>
		    </div>
		</div>
		<div class="form-group">	
			<label class="control-label" for="show_sched">Exam Date:</label>
		    <input type="text" class="form-control" name="org_show" value="@if ($placement_form->placementSchedule->is_online == 1) Online from {{ $placement_form->placementSchedule->date_of_plexam }} to {{ $placement_form->placementSchedule->date_of_plexam_end }} @else {{ $placement_form->placementSchedule->date_of_plexam }} @endif" readonly>

		</div>
		<div class="form-group">
		    <label class="control-label" for="result_comment">Placement Test Result:</label>
		    <div class="">
		        <textarea class="form-control" name="result_comment" cols="40" rows="3" readonly  placeholder="no comment">{{ $placement_form->Result }}</textarea>
		    </div>
		</div>
	</div> <!-- EOF 1st SECTION -->
	
	<div class="col-sm-6"> <!-- 2nd SECTION -->
		<div class="form-group">
			<button type="button" class="show-modal-history btn btn-info btn-space" data-toggle="modal"><span class="glyphicon glyphicon-time"></span>  View Course History</button>
		</div>
		<div class="form-group">
		    <label class="control-label" for="flexible_show">Waitlist Information: </label>
				<div class="panel panel-body">
			    	@if (count($waitlists) > 0)
				    	<div class="alert alert-info">
					    	<label for="">Waitlisted in </label>
					    		@foreach($waitlists as $waitlisted)
					    			{{ $waitlisted->Term }} : {{ $waitlisted->terms->Comments }} {{ date('Y', strtotime($waitlisted->terms->Term_Begin)) }} for 
					    			{{ $waitlisted->languages->name }}
					    			{{ $waitlisted->courses->Description }}
					    			@if (is_null($waitlisted->classrooms->Tch_ID))
										NULL
									@else {{ $waitlisted->classrooms->Tch_ID }}
					    			@endif
					    		@endforeach 
			    		</div>
			    	@else Not Waitlisted
			    	@endif
				</div>
		</div>
		<div class="form-group">
		    <label class="control-label" for="flexible_show">Is Flexible: @if($placement_form->flexibleBtn == 1)<span class="glyphicon glyphicon-ok text-success"></span> Yes @else <span class="glyphicon glyphicon-remove text-danger"></span> Not flexible @endif</label>
		</div>
		<div class="form-group">
		    <label class="control-label" for="student_comment_show">Preferred Days:</label>
		    <div class="">
		        <textarea class="form-control" name="student_comment_show" cols="40" rows="3" readonly placeholder="no comment">{{ $placement_form->dayInput }}</textarea>
		    </div>
		</div>
		<div class="form-group">
		    <label class="control-label" for="student_comment_show">Preferred Time:</label>
		    <div class="">
		        <textarea class="form-control" name="student_comment_show" cols="40" rows="3" readonly placeholder="no comment">{{ $placement_form->timeInput }}</textarea>
		    </div>
		</div>
		<div class="form-group">
		    <label class="control-label" for="student_comment_show">Student Comment:</label>
		    <div class="">
		        <textarea class="form-control" name="student_comment_show" cols="40" rows="3" readonly  placeholder="no comment">{{ $placement_form->std_comments }}</textarea>
		    </div>
		</div>
		<div class="form-group">
		    {{-- <div class="col-sm-12"><button type="button" class="show-modal btn btn-info pull-right" data-toggle="modal"><span class="glyphicon glyphicon-comment"></span>  View All Admin Notes</button></div> --}}
		    <label class="control-label" for="admin_comment_show">Course Preference:</label>
		    <div class="">
		        <textarea class="form-control" name="admin_comment_show" cols="40" rows="3" readonly  placeholder="no comment">{{ $placement_form->course_preference_comment }}</textarea>
		    </div>
		</div>
	</div> <!-- EOF 2nd SECTION -->

	<div class="col-sm-12">
		<!-- MAKE A DECISION SECTION -->

		<div class="box box-info operation-section">
		  <div class="box-header with-border">
		    <h3>Operation:</h3>
		  </div>
		  <div class="box-body">

		    <div class="form-group">
				<label for="">Did the student take a placement test? </label>
				<div class="col-sm-12">
			        <div class="col-md-4">
                      <input id="decisionConvoked1" name="convoked" class="with-font" type="radio" value="1">
                      <label for="decisionConvoked1" class="form-control-static">YES</label>
                    </div>

                    <div class="col-md-4">
                      <input id="decisionConvoked0" name="convoked" class="with-font" type="radio" value="0">
                      <label for="decisionConvoked0" class="form-control-static">NO</label>
                    </div>
				</div>

				{{-- <div class="col-sm-12 radio-click-assign" style="display: none;">
				        <input id="decision2" name="decision" class="with-font dno" type="radio" value="0" required="required">
				        <label for="decision2" class="form-control-static">Click to assign course</label>
				</div> --}}
			</div>
			
			<div class="form-group">
				<label for="flexibleQuestion">Is the student's schedule flexible? </label>
				<div class="col-sm-12">
			        <div class="col-md-4">
                      <input id="flexibleYes" name="flexible" class="with-font" type="radio" value="1">
                      <label for="flexibleYes" class="form-control-static">YES</label>
                    </div>

                    <div class="col-md-4">
                      <input id="flexibleNo" name="flexible" class="with-font" type="radio" value="0">
                      <label for="flexibleNo" class="form-control-static">NO</label>
                    </div>
				</div>
		    </div>

			{{-- start of hidden fields --}}
			<div class="regular-enrol"> 
			
				<div class="form-group placement-result">
					<label for="inputResult">Placement Test Result: </label>
					<textarea name="Result" id="inputResult" class="form-control" maxlength="3500" placeholder="Enter result of the placement test and other relevant information in this field, i.e. appropriate level, course, etc. or reason why student does not need to take a placement test"></textarea>
				</div>

				<div class="form-group">
			      <label for="L" class="control-label"> Language:</label>
			      <div class="col-sm-12">
			        @foreach ($languages as $id => $name)
			        <div class="col-sm-4">
			            <div class="input-group"> 
			              <span class="input-group-addon">       
			                <input type="radio" name="L" value="{{ $id }}" >                 
			              </span>
			                <label type="text" class="form-control">{{ $name }}</label>
			            </div>
			        </div>
			        @endforeach 
			      </div>
			    </div>

				<div class="form-group">
				    <label for="course_id" class="control-label">Assign Course: </label>
				    
				      <div class="dropdown">
				        <select class="form-control course_select_no wx" style="width: 100%;" name="course_id" autocomplete="off" >
				            <option value="">--- Select ---</option>
				        </select>
				      </div>
				    
				</div>

				<div class="form-group">
				    <label for="schedule_id" class="control-label">Assign Schedule: </label>
				    
				      <div class="dropdown">
				        <select class="form-control schedule_select_no wx" style="width: 100%; " name="schedule_id" autocomplete="off" >
				            <option value="">--- Select ---</option>
				        </select>
				      </div>
				    
				</div>

				<div class="form-group">
					<label class="control-label">Admin Comments: (optional) </label>

					<textarea id="textarea-{{$placement_form->eform_submit_count}}" name="admin_plform_comment" class="form-control" maxlength="3500" @if(is_null($placement_form->admin_plform_comment)) placeholder="Place important information to note about this student, enrolment form, etc." @else placeholder="{{$placement_form->admin_plform_comment}}" @endif></textarea>
					
				</div>

			</div> {{-- end of hidden fields --}}

			<div class="form-group col-sm-12">
				{{-- <a href="{{ route('placement-form.index') }}" class="btn btn-danger" ><span class="glyphicon glyphicon-arrow-left"></span>  Back</a> --}}
				<div class="col-sm-5 col-sm-offset-4">
					<button type="submit" class="btn btn-success button-prevent-multi-submit" style="margin: 2px 2px" name="submit-approval" value="1" disabled=""><span class="glyphicon glyphicon-check"></span> Assign </button>	
					<button type="submit" class="btn btn-warning button-prevent-multi-submit" style="margin: 2px 2px" name="submit-approval" value="0"><span class="glyphicon glyphicon-remove"></span>  Verify and Not Assign </button>
				</div>
				{{-- <button type="submit" class="btn btn-warning" name="submit-approval" value="2"><span class="glyphicon glyphicon-stop"></span>  Pending</button> --}}
			</div>
			<input type="hidden" name="_token" value="{{ Session::token() }}">
			        {{ method_field('PUT') }}
		  </div>
		</div>            
    </div>

	</form>	
</div>

<!-- Modal form to show history -->
<div id="showModalHistory" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title-history"></h4>
            </div>
            <div class="modal-body">

	            {{-- <div class="panel panel-info"> --}}
	                {{-- <div class="panel-heading"><strong>Past Language Course Enrolment for {{ $student->name }}
	                </div> --}}
	                <div class="panel-body panel-info">
	                    @if($historical_data->isEmpty())
	                    <div class="alert alert-warning">
	                        <p>There were no historical records found.</p>
	                    </div>
	                    @else
	                    <ul  class="list-group">
	                        @foreach($historical_data as $hist_datum)
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
	            {{-- </div> --}}
                	  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

@stop
@section('java_script')
<script src="{{ asset('js/submit.js') }}"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script>
	$(document).ready(function(){
	  $(".wx").select2({   
	    minimumResultsForSearch: -1,
	    placeholder: 'Select Here',
	    "language": {
	        "noResults": function(){
	            return "<strong class='text-danger'>Sorry No Courses Offered for this Language this Semester. </strong><br> <a href='https://learning.unog.ch/language-index' target='_blank' class='btn btn-info'>click here to see the availability of courses and classes</a>";
	            }
	    },

	    escapeMarkup: function (markup) {
	    return markup;
	    }
	  });
	}); 

	$(document).on('click', '.show-modal-history', function() {
		$('.modal-title-history').text('Past Language Course Enrolment for {{ $placement_form->users->name }}');
	    $('#showModalHistory').modal('show'); 
	});
</script>
<script>
	$(document).ready(function() {
		var term = $("input[name='Term']").val();
		var token = $("input[name='_token']").val();
		$.ajax({
	    	url: '{{ route('ajax-check-batch-has-ran') }}',
	    	type: 'GET',
	    	data: {Term:term,_token: token},
	    })
	    .done(function(data) {
	    	if (!jQuery.isEmptyObject( data )) {
	    		// $('.operation-section').append('<div class="overlay"><i class="fa fa-remove"></i><br><br><br><p class="text-center text-danger"><strong>Changes cannot be made. Please check with the system administrators.</strong></p></div>');

	    	}
	    })
	    .fail(function() {
	    	console.log("error");
	    })
	    .always(function() {
	    	console.log("complete check if batch has ran");
	    });
	});
</script>
<script>
	$(document).on('click', '#decision1', function() {
	    $(".regular-enrol").attr('style', 'display: none;');
	    $('.wx').val([]).trigger('change');
	});

	$("input[name='convoked']").on('click', function() {
			// $(".radio-click-assign").removeAttr('style');
			$(".regular-enrol").removeAttr('style');
	});

	$(document).on('click', "input[name='L']", function() {
	    // $(".regular-enrol").removeAttr('style');
	    var L = $(this).val();
		var term = $("input[name='Term']").val();
		var token = $("input[name='_token']").val();
	    console.log(L);
		$.ajax({
		  url: "{{ route('select-ajax') }}", 
		  method: 'POST',
		  data: {L:L, term_id:term, _token:token},
		  success: function(data, status) {
		  	console.log("success");
		    $("select[name='course_id']").html('');
		    $("select[name='course_id']").html(data.options);
		  }
		}); 
	});
</script>
<script>
	$("select[name='course_id']").on('change',function(){
		var course_id = $(this).val();
		var term = $("input[name='Term']").val();
		var token = $("input[name='_token']").val();
		console.log(course_id)
		$.ajax({
		  url: "{{ route('select-ajax2') }}", 
		  method: 'POST',
		  data: {course_id:course_id, term_id:term, _token:token},
		  success: function(data) {
		  	console.log("success on schedule");
		    $("select[name='schedule_id']").html('');
		    $("select[name='schedule_id']").html(data.options);
		    $("button[name='submit-approval']").removeAttr('disabled');
		  }
		});
  	}); 
</script>
<script>
	localStorage.setItem("update", "0");
	$("button[name='submit-approval']").click(function(){//target element and request click event
		localStorage.setItem("update", "1");//set localStorage of parent page
		// setTimeout(function(){
		// 	window.close();},
		// 	800);//timeout code to close window
		});
</script>
@stop