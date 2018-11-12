@extends('admin.no_sidebar_admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<form method="POST" action="{{ route('placement-form-assign-course', $placement_form->id) }}">
	{{ csrf_field() }}
	<input name="INDEXID" type="hidden" value="{{ $placement_form->INDEXID }}">
	<input name="L" type="hidden" value="{{ $placement_form->L }}">
	<input name="Term" type="hidden" value="{{ $placement_form->Term }}">

	<div class="col-sm-12">
		<!-- MAKE A DECISION SECTION -->

		<div class="panel panel-default">
		  <div class="panel-heading">
		    <h3 class="panel-title">Operation:</h3>
		  </div>
		  <div class="panel-body">

		    <div class="form-group">
				<div class="col-sm-12">
				        <input id="decision2" name="decision" class="with-font dno" type="radio" value="0" required="required">
				        <label for="decision2" class="form-control-static">Assign course to student</label>
				</div>
		    </div>

			<div class="regular-enrol" style="display: none"> {{-- start of hidden fields --}}

				<div class="form-group">
				    <label for="course_id" class="control-label">Assign Course: </label>
				    
				      <div class="dropdown">
				        <select class="form-control course_select_no wx" style="width: 100%;" name="course_id" autocomplete="off" required="">
				            <option value="">--- Select ---</option>
				        </select>
				      </div>
				    
				</div>

				<div class="form-group">
				    <label for="schedule_id" class="control-label">Assign Schedule: </label>
				    
				      <div class="dropdown">
				        <select class="form-control schedule_select_no wx" style="width: 100%; " name="schedule_id" autocomplete="off" required="">
				            <option value="">--- Select ---</option>
				        </select>
				      </div>
				    
				</div>

			</div> {{-- end of hidden fields --}}

			<div class="form-group col-sm-12">
				{{-- <a href="{{ route('placement-form.index') }}" class="btn btn-danger" ><span class="glyphicon glyphicon-arrow-left"></span>  Back</a> --}}
				{{-- <button type="submit" class="btn btn-danger" name="submit-approval" value="0"><span class="glyphicon glyphicon-remove"></span>  Disapprove</button> --}}
				<button type="submit" class="btn btn-success pull-right button-prevent-multi-submit" name="submit-approval" value="1"><span class="glyphicon glyphicon-ok"></span>  Submit </button>	
				{{-- <button type="submit" class="btn btn-warning" name="submit-approval" value="2"><span class="glyphicon glyphicon-stop"></span>  Pending</button> --}}
			</div>
			<input type="hidden" name="_token" value="{{ Session::token() }}">
			        {{ method_field('PUT') }}
		  </div>
		</div>            
    </div>
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
			{{-- @foreach($placement_form as $show_sched) --}}
		    <div class="col-sm-12">
				<ul>
		    		<li>@if($placement_form->L == 'F') <strong>ONLINE</strong> from {{ $placement_form->placementSchedule->date_of_plexam }} to {{ $placement_form->placementSchedule->date_of_plexam_end }} @else {{ $placement_form->placementSchedule->date_of_plexam }} @endif</li>
				</ul>
			</div>
			{{-- @endforeach --}}
		</div>
	</div> <!-- EOF 1st SECTION -->
	
	<div class="col-sm-6"> <!-- 2nd SECTION -->
		<div class="form-group">
		    <label class="control-label" for="flexible_show">Placement Test/ Waitlist Information: </label>
				<div class="panel panel-body">
			    	@if ($waitlists)
			    		@foreach($waitlists as $waitlisted)
			    			@foreach($waitlisted->waitlist as $info_details)
			    			<ul>
			    				<li>Term Code: {{ $info_details->Term }}</li>
			    				<li>Term: {{ $info_details->terms->Comments }}</li>
			    				<li>Remark: {{ $info_details->Comments }}</li>
			    			</ul>
			    			<hr>
			    			@endforeach 
			    		@endforeach 
			    	@else -- 
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

	</form>	
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
</script>
<script>
	$(document).on('click', '#decision1', function() {
	    $(".regular-enrol").attr('style', 'display: none;');
	    $('.wx').val([]).trigger('change');
	});

	$(document).on('click', '#decision2', function() {
	    $(".regular-enrol").removeAttr('style');
	    var L = $("input[name='L']").val();
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
		  }
		});
  	}); 
</script>
<script>
	localStorage.setItem("update", "0");
	$("button[name='submit-approval']").click(function(){//target element and request click event
		localStorage.setItem("update", "1");//set localStorage of parent page
		setTimeout(function(){window.close();},500);//timeout code to close window
		});
</script>
@stop