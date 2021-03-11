@extends('admin.admin')

@section('customcss')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h3><i class="fa fa-pied-piper-alt"></i> <span>Shows Teachers and their Courses</span></h3>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">

			@include('admin.partials._dropdownSetSessionTerm')

		</div>
	</div>

	@if (Session::has('Term'))
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-info-circle"></i> Selected Term</h3>
					</div>
					<div class="panel-body">
						<h4>You are currently viewing teachers and their respective classes for <strong> [{{ $selectedTerm->Term_Code }}] - {{ $selectedTerm->Comments }}: {{ $selectedTerm->Term_Name }} </strong> </h4>
						
						<a href="{{ route('teacher-email-classrooms-to-teachers') }}" class="btn btn-primary"><i class="fa fa-envelope"></i> send email</a><span class="text-danger"> <i class="fa fa-info-circle"></i> send classroom info to teachers via email</span>
					</div>
				</div>
			</div>
		</div>
	@endif
{{-- 
	<div class="box box-widget widget-user-2">
	    <!-- Add the bg color to the header using any of the bg-* classes -->
	    <div class="widget-user-header bg-yellow">
	      <div class="widget-user-image">
	        <img class="img-circle" src="../dist/img/user7-128x128.jpg" alt="User Avatar">
	      </div>
	      <!-- /.widget-user-image -->
	      <h3 class="widget-user-username">Nadia Carmichael</h3>
	      <h5 class="widget-user-desc">Lead Developer</h5>
	    </div>
	    <div class="box-footer no-padding">
	      <ul class="nav nav-stacked">
	        <li><a href="#">Projects <span class="pull-right badge bg-blue">31</span></a></li>
	        <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
	        <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
	        <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li>
	      </ul>
	    </div>
	</div>
 --}}
	@if (Session::has('Term'))
	<table class="table table-condensed">
		<thead>
			<tr>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($teachers as $value)
				@foreach ($value as $teacher)
				<tr>
					<td>
						<div class="box box-widget widget-user-2">
						    <!-- Add the bg color to the header using any of the bg-* classes -->
						    <div class="widget-user-header" 
							@if ($teacher->Tch_L == 'A')
								style="background-color: #ff7243;color: #fff;" 
							@elseif($teacher->Tch_L == 'C')
								style="background-color: #B22222;color: #fff;"
							@elseif($teacher->Tch_L == 'E')
								style="background-color: #0079c1;color: #fff;"
							@elseif($teacher->Tch_L == 'F')
								style="background-color: #338d11;color: #fff;"
							@elseif($teacher->Tch_L == 'R')
								style="background-color: #6347b2;color: #fff;"
							@elseif($teacher->Tch_L == 'S')
								style="background-color: #bf8124;color: #fff;"
							@endif
							>
						      <div class="widget-user-image">
						        <img class="img-circle" src="../img/generic-profile-icon-10.jpg" alt="User Avatar">
						      </div>
						      <!-- /.widget-user-image -->
						      <h3 class="widget-user-username">{{$teacher->Tch_Name}}</h3>
						      <h5 class="widget-user-desc">{{$teacher->languages->name}}</h5>
						    </div>
						    @foreach ($teacher->classrooms as $element)
						    <div class="box-footer no-padding">
						      <ul class="nav nav-stacked">
						        <h5 style="margin-left: 1.5rem">LTP-{{$element->Te_Term}}-{{$element->Tch_ID}}-{{$element->course->Description}}-{{$element->Te_Code_New}}:{{$element->scheduler->name}} <a href="{{ route('view-classrooms-per-section', $element->Code) }}" target="_blank"><i class="fa fa-external-link"></i></a></h5>
						        {{-- <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
						        <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
						        <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li> --}}
						      </ul>
						    </div>
						    @endforeach
						</div>
					</td>
				</tr>
				@endforeach
			@endforeach
				
		</tbody>
	</table>
	@endif
</div>
@endsection

@section('java_script')
<script src="{{ asset('js/select2.full.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "select here",
    });
});
</script>

@stop