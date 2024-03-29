@extends('admin.no_sidebar_admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<div class="alert alert-selfpay col-sm-10 col-sm-offset-1">
	<h4 class="text-center"><strong>Payment-based <u>Placement Form</u>:</strong> Confirm if ID and payment proof attachments are valid or not.</h4>
	</div>
	<div class="col-sm-10 col-sm-offset-1">
	<form method="POST" action="{{ route('post-placement-selfpay', $selfpay_student->id) }}">
	{{ csrf_field() }}

	<input name="INDEXID" type="hidden" value="{{ $selfpay_student->INDEXID }}">
	<input name="Term" type="hidden" value="{{ $selfpay_student->Term }}">
	<input name="L" type="hidden" value="{{ $selfpay_student->L }}">
	<input name="eform_submit_count" type="hidden" value="{{ $selfpay_student->eform_submit_count }}">
	
	<div class="form-group">
	    <label class="control-label" for="id_show">Name:</label>
	    <div class="">
	        <input name="nom" type="text" class="form-control"  value="{{ $selfpay_student->users->name }}" readonly>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="course_show">Language:</label>
	    <div class="">
	        <input type="text" class="form-control" name="Lstring" value="{{ $selfpay_student->languages->name }}" readonly>
	        <input type="hidden" class="form-control" name="L" value="{{ $selfpay_student->L }}" readonly>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label" for="org_show">ID Proof:</label>
		<td>@if(empty($selfpay_student->filesId->path)) None @else <a href="{{ Storage::url($selfpay_student->filesId->path) }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif </td>	
	</div>
	<div class="form-group">	
		<label class="control-label" for="org_show">ID (Back Side):</label>
		<td>@if (empty($backSideId->count())) None @else @foreach ($backSideId as $item) @if ($loop->first)<a href="{{ Storage::url($item->path) }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a>@endif @endforeach 
			@endif
		</td>
	</div>	
	<div class="form-group">	
		<label class="control-label" for="org_show">Contract:</label>
		<td>@if (empty($contractFiles->count())) None @else @foreach ($contractFiles as $item) @if ($loop->first)<a href="{{ Storage::url($item->path) }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a>@endif @endforeach 
			@endif
		</td>
	</div>	
	<div class="form-group">	
		<label class="control-label" for="org_show">Payment Proof:</label>
		<td>@if(empty($selfpay_student->filesPay->path)) None @else <a href="{{ Storage::url($selfpay_student->filesPay->path) }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif </td>
	</div>
	<div class="form-group">	
		<label class="control-label" for="org_show">Additional Files:</label>
		<td>@if (empty($additionalFiles->count())) None @else @foreach ($additionalFiles as $item) <a href="{{ Storage::url($item->path) }}" onclick="window.open(this.href, '', 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endforeach 
			@endif
		</td>
	</div>
	<div class="form-group">
	    <label class="control-label" for="profile_show">Profile:</label>
	    <div class="">
	        <input type="text" class="form-control" name="profile_show" value="{{ $selfpay_student->profile }}" readonly>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="org_show">Organization:</label>
	    <div class="">
	        <input type="text" class="form-control" name="org_show" value="{{ $selfpay_student->DEPT }} @if($selfpay_student->users->sddextr->DEPT === 'MSU') @if($selfpay_student->users->sddextr->countryMission) - {{ $selfpay_student->users->sddextr->countryMission->ABBRV_NAME }} @else - (country update needed) @endif @endif @if ($selfpay_student->users->sddextr->DEPT === 'NGO') @if ($selfpay_student->users->sddextr->ngo_name)- {{ $selfpay_student->users->sddextr->ngo_name }} @else - (NGO name update needed) @endif @endif " readonly>
	    </div>
	</div>
	<div class="form-group">	
		<label class="control-label" for="show_sched">Exam Date:</label>
		@foreach($show_sched_selfpay as $show_sched)
	    <div class="">
			{{-- <input type="text" class="form-control" name="show_sched" value="{{ $show_sched->placementSchedule->date_of_plexam }}" readonly> --}}
			<ul>
	    		<li>@if($show_sched->L == 'F') <strong>ONLINE</strong> from {{ $show_sched->placementSchedule->date_of_plexam }} to {{ $show_sched->placementSchedule->date_of_plexam_end }} @else {{ $show_sched->placementSchedule->date_of_plexam }} @endif</li>
			</ul>
		</div>
		@endforeach
	</div>
{{-- 	<div class="form-group">
	    <label class="control-label" for="flexible_show">Is Flexible: @if($selfpay_student->flexibleBtn == 1)<span class="glyphicon glyphicon-ok text-success"></span> Yes @else <span class="glyphicon glyphicon-remove text-danger"></span> Not flexible @endif</label>
	</div>
	<div class="form-group">
	    <label class="control-label" for="student_comment_show">Student Comment on Placement Exam:</label>
	    <div class="">
	        <textarea class="form-control" name="student_comment_show" cols="40" rows="5" readonly>{{$selfpay_student->std_comments}}</textarea>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="student_comment_show">Student Comment on Course Preference:</label>
	    <div class="">
	        <textarea class="form-control" name="course_preference_show" cols="40" rows="5" readonly>{{$selfpay_student->course_preference_comment}}</textarea>
	    </div>
	</div> --}}
	<div class="form-group">
	    <div class="button-div"><button type="button" class="show-modal btn btn-info" data-toggle="modal"><span class="glyphicon glyphicon-comment"></span>  View All Admin Notes/Comments</button></div>
	    <label class="control-label" for="admin_comment_show">Admin Comment: </label>
	    <p class="text-danger"><strong>If pending/disapproved status, please write the reason on the text area below. The text below will be included in the email communication to the student. </strong></p>
	    <div class="">
	    <div class="">
	        <textarea class="form-control" name="admin_comment_show" cols="40" rows="5"></textarea>
	    </div>
	</div>
	<div class="col-sm-12">
		<button type="submit" class="btn btn-danger" name="submit-approval" value="0"><span class="glyphicon glyphicon-remove"></span>  Disapprove</button>
		<button type="submit" class="btn btn-success" name="submit-approval" value="1"><span class="glyphicon glyphicon-ok"></span>  Approve</button>	
		<button type="submit" class="btn btn-warning" name="submit-approval" value="2"><span class="glyphicon glyphicon-stop"></span>  Pending</button>
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
            		
                	@foreach($show_admin_comments as $comment)
                		@if(is_null($comment->comments)) no comment made 
                		@else
                    	{{ $comment->comments }} @endif 
                    	<br> at {{ $comment->created_at }} by {{ $comment->user->name }} <br><br>
                    @endforeach
                    
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
<script>
	localStorage.setItem("update", "0");
	$("button[name='submit-approval']").click(function(){//target element and request click event
		localStorage.setItem("update", "1");//set localStorage of parent page
		setTimeout(function(){window.close();},500);//timeout code to close window
		});
</script>
@stop