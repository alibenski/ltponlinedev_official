@extends('admin.admin')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<style>
    .admin-index-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
	.admin-index-column-1 {
		padding: 5px;
		flex: 1;
		margin: 5px 5px ;
		border-radius: 10px;
	  	border: 2px solid whitesmoke;
		min-height: 10em;
	}
</style>
@stop

@section('content')

<h2>System Operations</h2>

@include('admin.partials._termSessionMsg')

<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box box-body">
				<form class="admin-index-container">
						<div class="admin-index-column-1">
							<h4><a href="{{ route('preview-vsa-page-1') }}" class="btn btn-default"><i class="fa fa-cogs" aria-hidden="true"></i> Run batch to assign students to classrooms with priority </a></h4>
						</div>
	
						<div class="admin-index-column-1">
							<h4>
								@if (is_null($term))
									<a href="#" class="text-danger"><i class="fa fa-ban"></i> Send Convocation Email to students (Term is not set)</a>
								@else
									<a href="{{route('send-convocation')}}" class="send-convocation send-emails btn btn-default"><i class="fa fa-envelope-o"></i> Send Convocation Email to students of  {{ $term->Comments }} {{ date('Y', strtotime($term->Term_Begin)) }}  [ {{ $term->Term_Code }} ]</a>
								@endif
							</h4>
								@if (!is_null($term))
									<a href="{{ route('view-convocation-email-text') }}" class="btn btn-info"><i class="fa fa-eye"></i> View Convocation Email Text</a>
								@endif
						</div>
	
						<div class="admin-index-column-1">
							<h4><i class="fa fa-telegram" aria-hidden="true"></i> Send Broadcast Email </h4>
							<h4>
								<div class="dropdown">
									<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success btn-space">
									<i class="fa fa-envelope"></i> Send to...
									<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="dLabel">
									<li><a href="{{route('send-broadcast-enrolment-is-open')}}" class="send-broadcast-enrolment-is-open send-emails btn-space"><i class="fa fa-envelope"></i> Send Broadcast Email That Enrolment is Open</a></li>
									<li><a href="{{route('send-broadcast-reminder')}}" class="send-broadcast-reminder send-emails btn-space"><i class="fa fa-envelope"></i> Send Reminder Email to All Students Not Yet Enrolled</a></li>
									<li><a href="{{route('send-reminder-to-current-students')}}" class="send-reminder-to-current-students send-emails btn-space"><i class="fa fa-envelope"></i> Send Reminder Email to Current Students Not Yet Enrolled</a></li>
									</ul>
									<a href="{{ route('view-enrolment-is-open-text', ['id' => 1]) }}" class="btn btn-info btn-space"><i class="fa fa-eye"></i> View</a>
									<a href="{{ route('edit-enrolment-is-open-text', ['id' => 1]) }}" class="btn btn-warning btn-space"><i class="fa fa-pencil"></i> Edit</a>
								</div>
							</h4>
						</div>
						
						<div class="admin-index-column-1">
							<h4><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Email </h4>
							<h4>
								<div class="dropdown">
									<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success btn-space">
									<i class="fa fa-envelope"></i> Send to...
									<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" aria-labelledby="dLabel">
									<li><a href="{{route('send-general-email')}}" class="send-send-general-email send-emails btn-space"><i class="fa fa-envelope"></i> Send Email to All Students (Current and Past)</a></li>
									{{-- <li><a href="{{route('send-broadcast-reminder')}}" class="send-broadcast-reminder send-emails btn-space"><i class="fa fa-envelope"></i> Send Email to <strong> Students Not Yet Enrolled </strong></a></li>
									<li><a href="{{route('send-reminder-to-current-students')}}" class="send-reminder-to-current-students send-emails btn-space"><i class="fa fa-envelope"></i> Send Email to <strong> Current Students </strong> Not Yet Enrolled</a></li> --}}
									</ul>
									<a href="{{ route('view-general-email-text', ['id' => 2]) }}" class="btn btn-info btn-space"><i class="fa fa-eye"></i> View</a>
									<a href="{{ route('edit-enrolment-is-open-text', ['id' => 2]) }}" class="btn btn-warning btn-space"><i class="fa fa-pencil"></i> Edit</a>
								</div>
							</h4>
						</div>
				</form>
			</div>
			<div class="overlay">
        		<i class="fa fa-refresh fa-spin"></i>
        	</div>
		</div>
		
	</div>
</div>


@stop

@section('java_script')

<script>
	$(document).ready(function() {
		$(".overlay").fadeOut(600);
	});

	$('a.send-emails').on('click', function() {
        var c = confirm("This is a mass email function. Are you sure?");
        $(".overlay").fadeIn(600);
        window.location.reload();
        return c; //you can just return c because it will be true or false
    });	
</script>

@stop