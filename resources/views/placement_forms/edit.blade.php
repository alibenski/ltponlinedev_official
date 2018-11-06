@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
	<form method="POST" action="{{ route('placement-form.update', $placement_form->INDEXID) }}">
	{{ csrf_field() }}
	<input name="INDEXID" type="hidden" value="{{ $placement_form->INDEXID }}">
	<input name="L" type="hidden" value="{{ $placement_form->L }}">
	<input name="Term" type="hidden" value="{{ $placement_form->Term }}">
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
	        <textarea class="form-control" name="student_comment_show" cols="40" rows="5" readonly placeholder="no comment">{{ $placement_form->dayInput }}</textarea>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="student_comment_show">Preferred Time:</label>
	    <div class="">
	        <textarea class="form-control" name="student_comment_show" cols="40" rows="5" readonly placeholder="no comment">{{ $placement_form->timeInput }}</textarea>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="student_comment_show">Student Comment:</label>
	    <div class="">
	        <textarea class="form-control" name="student_comment_show" cols="40" rows="5" readonly  placeholder="no comment">{{ $placement_form->std_comments }}</textarea>
	    </div>
	</div>
	<div class="form-group">
	    {{-- <div class="col-sm-12"><button type="button" class="show-modal btn btn-info pull-right" data-toggle="modal"><span class="glyphicon glyphicon-comment"></span>  View All Admin Notes</button></div> --}}
	    <label class="control-label" for="admin_comment_show">Course Preference:</label>
	    <div class="">
	        <textarea class="form-control" name="admin_comment_show" cols="40" rows="5" readonly  placeholder="no comment">{{ $placement_form->course_preference_comment }}</textarea>
	    </div>
	</div>

	<!-- MAKE A DECISION SECTION -->
                
    <div class="form-group">
        <label class="control-label">Will Student Take Placement Test?</label>

          <div class="col-sm-12">
                    <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" required="required">
                    <label for="decision1" class="form-control-static">Yes</label>
          </div>

          <div class="col-sm-12">
                    <input id="decision2" name="decision" class="with-font dno" type="radio" value="0" required="required">
                    <label for="decision2" class="form-control-static">No</label>
          </div>
    </div>

	<div class="form-group col-sm-12">
		<a href="{{ route('placement-form.index') }}" class="btn btn-danger" ><span class="glyphicon glyphicon-arrow-left"></span>  Back</a>
		{{-- <button type="submit" class="btn btn-danger" name="submit-approval" value="0"><span class="glyphicon glyphicon-remove"></span>  Disapprove</button> --}}
		<button type="submit" class="btn btn-success pull-right button-prevent-multi-submit" name="submit-approval" value="1"><span class="glyphicon glyphicon-ok"></span>  Submit and Send Email</button>	
		{{-- <button type="submit" class="btn btn-warning" name="submit-approval" value="2"><span class="glyphicon glyphicon-stop"></span>  Pending</button> --}}
	</div>
	<input type="hidden" name="_token" value="{{ Session::token() }}">
	        {{ method_field('PUT') }}
	</form>	
	</div>
</div>
<!-- Modal form to show a post -->
<div id="showModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
            		
                	{{-- @foreach($show_admin_comments as $comment)
                    	{{ $comment->comments }} <br> at {{ $comment->created_at }} by {{ $comment->user->name }} <br><br>
                    @endforeach --}}
                    
            	</form>
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
<script>
// Show a post
$(document).on('click', '.show-modal', function() {
    $('.modal-title').text('Admin Notes');
    console.log('click');
    // $('#id_show').val($(this).data('id'));
    // $('#title_show').val($(this).data('title'));
    // // $('#form-filter').removeAttr('action');
    // // $('.filter-submit-btn').replaceWith('<a class="btn btn-success next-link btn-default btn-block button-prevent-multi-submit" disabled>Next</a>');
    // var index = $(this).data('index');
    // var tecode = $(this).data('tecode');
    // var term = $(this).data('term');
    $('#showModal').modal('show'); 
});
</script>
@stop