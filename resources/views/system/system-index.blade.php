@extends('admin.admin')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
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
<div class="row">
	<div class="col-md-12">
	@include('admin.partials._termSessionMsg')
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	@include('admin.partials._dropdownSetSessionTerm')
	</div>
</div>

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
									<li><a href="{{route('send-broadcast-enrolment-is-open')}}" class="send-broadcast-enrolment-is-open send-emails btn-space"><i class="fa fa-envelope"></i> Send Broadcast Email That Enrolment is Open (Current and Past Students)</a></li>
									<li><a href="{{route('send-broadcast-reminder')}}" class="send-broadcast-reminder send-emails btn-space"><i class="fa fa-envelope"></i> Send Reminder Email to All Current and Past Students Not Yet Enrolled [ {{Session::get('Term')}} ]</a></li>
									<li><a href="{{route('send-reminder-to-current-students')}}" class="send-reminder-to-current-students send-emails btn-space"><i class="fa fa-envelope"></i> Send Reminder Email to Current Students Not Yet Enrolled [ {{Session::get('Term')}} ]</a></li>
									</ul>
									<a href="{{ route('view-enrolment-is-open-text', ['id' => 1]) }}" class="btn btn-info btn-space"><i class="fa fa-eye"></i> View</a>
									<a href="{{ route('edit-enrolment-is-open-text', ['id' => 1]) }}" class="btn btn-warning btn-space"><i class="fa fa-pencil"></i> Edit</a>
								</div>
							</h4>
							@if ($onGoingTerm)
							<h5>
								<i class="fa fa-info-circle"></i>
									Queries related to on-going term:<strong> {{$onGoingTerm->Term_Code }} / {{$onGoingTerm->Comments }} {{ date('Y', strtotime($onGoingTerm->Term_End)) }}
								</strong>
							</h5>
							@endif
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
									<li><a href="{{route('send-general-email')}}" class="send-send-general-email send-emails btn-space"><i class="fa fa-envelope"></i> Send Email to All Current and Past Students</a></li>
									@if (Session::has('Term'))
									<li><a href="{{route('send-email-to-enrolled-students-of-selected-term')}}" class="send-emails btn-space"><i class="fa fa-envelope"></i> Send Email to Students Who Have Enrolled [ {{Session::get('Term')}} ]</a></li>
									@endif
									{{-- <li><a href="{{route('send-reminder-to-current-students')}}" class="send-reminder-to-current-students send-emails btn-space"><i class="fa fa-envelope"></i> Send Email to <strong> Current Students </strong> Not Yet Enrolled</a></li> --}}
									</ul>
									<a href="{{ route('view-general-email-text', ['id' => 2]) }}" class="btn btn-info btn-space"><i class="fa fa-eye"></i> View</a>
									<a href="{{ route('edit-enrolment-is-open-text', ['id' => 2]) }}" class="btn btn-warning btn-space"><i class="fa fa-pencil"></i> Edit</a>
								</div>
							</h4>
						</div>
				</form>
			</div>
			@if (!Session::has('Term'))
				<div class="overlay"></div>
			@endif
				<div class="overlay overlay-sending">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
		</div>
		
	</div>
</div>


@stop

@section('java_script')
<script src="{{ asset('js/select2.full.js') }}"></script>
<script>
	$(document).ready(function() {
		$(".overlay.overlay-sending").fadeOut(600);
		$('.select2-basic-single').select2({
		placeholder: "select here",
		});
	});

	$('a.send-emails').on('click', function(e) {
        var c = confirm("This is a mass email function. Are you sure?");
        if (c === true) {
			$(".overlay").fadeIn(400);
			window.location.reload();
			return c; //you can just return c because it will be true or false  
		} else {
			e.preventDefault();
		}
    });	
</script>

@stop