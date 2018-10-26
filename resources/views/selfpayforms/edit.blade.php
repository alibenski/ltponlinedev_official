@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
	<form method="POST" action="{{ route('selfpayform.update', $selfpay_student->INDEXID) }}">
	{{ csrf_field() }}
	<input name="INDEXID" type="hidden" value="{{ $selfpay_student->INDEXID }}">
	<input name="Te_Code" type="hidden" value="{{ $selfpay_student->Te_Code }}">
	<input name="Term" type="hidden" value="{{ $selfpay_student->Term }}">
	<div class="form-group">
	    <label class="control-label" for="id_show">Name:</label>
	    <div class="">
	        <input name="nom" type="text" class="form-control"  value="{{ $selfpay_student->users->name }}" readonly>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="course_show">Course:</label>
	    <div class="">
	        <input type="name" class="form-control" name="course_show" value="{{ $selfpay_student->courses->Description }}" readonly>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="profile_show">Profile:</label>
	    <div class="">
	        <input type="name" class="form-control" name="profile_show" value="{{ $selfpay_student->profile }}" readonly>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="org_show">Organization:</label>
	    <div class="">
	        <input type="name" class="form-control" name="org_show" value="{{ $selfpay_student->DEPT }}" readonly>
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
	    <label class="control-label" for="student_comment_show">Student Comment:</label>
	    <div class="">
	        <textarea class="form-control" name="student_comment_show" cols="40" rows="5" readonly></textarea>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label" for="admin_comment_show">Admin Comment:</label>
	    <div class="">
	        <textarea class="form-control" name="admin_comment_show" cols="40" rows="5" readonly></textarea>
	    </div>
	</div>
	<div class="col-sm-12">
		<button type="submit" class="show-modal btn btn-danger" name="submit-approval" value="0"><span class="glyphicon glyphicon-remove"></span>  Disapprove</button>
		<button type="submit" class="show-modal btn btn-success" name="submit-approval" value="1"><span class="glyphicon glyphicon-ok"></span>  Approve</button>	
	</div>
	<input type="hidden" name="_token" value="{{ Session::token() }}">
	        {{ method_field('PUT') }}
	</form>	
	</div>
</div>
@stop