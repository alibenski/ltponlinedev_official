@extends('admin.no_sidebar_admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<div class="alert bg-purple col-sm-10 col-sm-offset-1">
	<h4 class="text-center"><strong>Payment-based <u>Regular Enrolment Form</u>:</strong> Confirm if ID and payment proof attachments are valid or not.</h4>
	</div>
	<div class="col-sm-10 col-sm-offset-1">
	<form method="POST" action="{{ route('selfpayform.update', $selfpay_student->INDEXID) }}">
	{{ csrf_field() }}

	<input name="INDEXID" type="hidden" value="{{ $selfpay_student->INDEXID }}">
	<input name="L" type="hidden" value="{{ $selfpay_student->L }}">
	<input name="Te_Code" type="hidden" value="{{ $selfpay_student->Te_Code }}">
	<input name="Term" type="hidden" value="{{ $selfpay_student->Term }}">
	<input name="eform_submit_count" type="hidden" value="{{ $selfpay_student->eform_submit_count }}">
	{{-- <input name="UpdatedOn" type="hidden" value="{{ $selfpay_student->UpdatedOn }}"> --}}

	<div class="form-group">
	    <label class="control-label" for="id_show">Name:</label>
	    <div class="">
	        <input name="nom" type="text" class="form-control"  value="{{ $selfpay_student->users->name }}" readonly>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="course_show">Course:</label>
	    <div class="">
	        <input type="text" class="form-control" name="course_show" value="{{ $selfpay_student->courses->Description }}" readonly>
	    </div>
	</div>
	<div class="form-group">
		<label class="control-label" for="org_show">ID (Front Side):</label>
		<td>@if(empty($selfpay_student->filesId->path)) None @else <a href="{{ Storage::url($selfpay_student->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif </td>	
	</div>
	<div class="form-group">	
		<label class="control-label" for="org_show">ID (Back Side):</label>
		<td>@if (empty($backSideId->count())) None @else @foreach ($backSideId as $item) <a href="{{ Storage::url($item->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endforeach 
			@endif
		</td>
	</div>	
	<div class="form-group">	
		<label class="control-label" for="org_show">Contract:</label>
		<td>@if (empty($contractFiles->count())) None @else @foreach ($contractFiles as $item) <a href="{{ Storage::url($item->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endforeach 
			@endif
		</td>
	</div>	
	<div class="form-group">	
		<label class="control-label" for="org_show">Payment Proof:</label>
		<td>@if(empty($selfpay_student->filesPay->path)) None @else <a href="{{ Storage::url($selfpay_student->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif </td>
	</div>	

	<div class="form-group">	
		<label class="control-label" for="org_show">Additional Files:</label>
		<td>@if (empty($additionalFiles->count())) None @else @foreach ($additionalFiles as $item) <a href="{{ Storage::url($item->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endforeach 
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
	        <input type="text" class="form-control" name="org_show" value="{{ $selfpay_student->DEPT }}" readonly>
	    </div>
	</div>
	<div class="form-group">	
		<label class="control-label" for="show_sched">Schedule(s):</label>
		@foreach($show_sched_selfpay as $show_sched)
	    <div class="col-sm-12">
			<ul>
	    		<li>{{ $show_sched->schedule->name }}</li>
			</ul>
		</div>
		@endforeach
	</div>
	<div class="form-group">
	    <label class="control-label" for="flexible_show">Is Flexible: @if($selfpay_student->flexibleBtn == 1)<span class="glyphicon glyphicon-ok text-success"></span> Yes @else <span class="glyphicon glyphicon-remove text-danger"></span> Not flexible @endif</label>
	</div>
	<div class="form-group">
	    <label class="control-label" for="flexible_format">Is Flexible Format (In-person or Online): @if($selfpay_student->flexibleFormat == 1)<span class="glyphicon glyphicon-ok text-success"></span> Yes @else <span class="glyphicon glyphicon-remove text-danger"></span> Not flexible @endif</label>
	</div>
	{{-- <div class="form-group">
		<label class="control-label" for="optradio">Choose Default Text:</label>
		<div class="col-md-12">
                  <input id="option1" name="optionRadio" class="with-font" type="radio" value="1">
                  <label for="option1" class="form-control-static">Option 1</label>
        </div>

        <div class="col-md-12">
                  <input id="option2" name="optionRadio" class="with-font" type="radio" value="2">
                  <label for="option2" class="form-control-static">Option 2</label>
        </div>

        <div class="col-md-12">
                  <input id="option3" name="optionRadio" class="with-font" type="radio" value="3">
                  <label for="option3" class="form-control-static">Option 3</label>
        </div>
	</div> --}}
	{{-- <div class="form-group">
	    <label class="control-label" for="student_comment_show">Student Comment:</label>
	    <div class="">
	        <textarea class="form-control" name="student_comment_show" cols="40" rows="5" readonly placeholder="field not yet functioning"></textarea>
	    </div>
	</div> --}}
	<div class="form-group">
	    <div class="col-sm-12"><button type="button" class="show-modal btn btn-info pull-right" data-toggle="modal"><span class="glyphicon glyphicon-comment"></span>  View All Admin Notes</button></div>
	    <label class="control-label" for="admin_comment_show">Admin Comment: </label>
	    <p class="text-danger"><strong>If pending/disapproved status, please write the reason on the text area below. The text below will be included in the email communication to the student. </strong></p>
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