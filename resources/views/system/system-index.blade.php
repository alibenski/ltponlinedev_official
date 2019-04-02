@extends('admin.admin')

@section('content')

<h2>System Operations</h2>

@include('admin.partials._termSessionMsg')

<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box box-body">
				<h4><a href="{{ route('preview-vsa-page-1') }}"><i class="fa fa-play"></i> Run batch to assign students to classrooms with priority </a></h4>
				<h4>
					@if (is_null($term))
						<a href="#" class="text-danger"><i class="fa fa-ban"></i> Send Convocation Email to students (Term is not set)</a>
					@else
						<a href="{{route('send-convocation')}}"><i class="fa fa-envelope-o"></i> Send Convocation Email to students of  {{ $term->Comments }} {{ date('Y', strtotime($term->Term_Begin)) }}  [ {{ $term->Term_Code }} ]</a>
					@endif
				</h4>

				<h4><a href="{{route('send-broadcast-enrolment-is-open')}}"><i class="fa fa-arrow-right"></i> Send Broadcast Email that Enrolment is Open</a></h4>
				
				<h4><a href="{{route('send-reminder-to-current-students')}}"><i class="fa fa-envelope"></i> Send Reminder Email to Current Students Not Yet Enrolled</a> (Email text needs to be updated programmatically)</h4>
			</div>
		</div>
		
	</div>
</div>


@stop