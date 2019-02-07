@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
@stop

@section('content')

<div class='col-md-12'>
    <h2><i class='fa fa-book'></i> Edit Course Name: {{ $course->Description }}</h2>
    <hr>
    <div class="row">
      <div class="col-md-12">
      	<div class="box box-default box-solid">
      		<div class="box-header with-border">
      			<h3 class="box-title">Current Course Parameters:</h3>
      		</div>
      		<div class="box-body no-padding">
				<div class="col-md-12">
					<div class="form-group col-md-4">
			            <label for="title" class="control-label">Course Code:</label>

			            <div class="form-control-static">
			                <p>@if(empty ( $course->Te_Code_New )) - @else {{ $course->Te_Code_New }} @endif</p>
			            </div>
			        </div>
					
					<div class="form-group col-md-4">
			            <label for="title" class="control-label">Course Name:</label>

			            <div class="form-control-static">
			                <p>@if(empty ( $course->Te_Code_New )) - @else {{ $course->Description }} @endif</p>
			            </div>
			        </div>
					
					<div class="form-group col-md-4">
			            <label for="title" class="control-label">Course Name (French):</label>

			            <div class="form-control-static">
			                <p>@if(empty ( $course->Te_Code_New )) - @else {{ $course->FDescription }} @endif</p>
			            </div>
			        </div>

				</div>
      		</div>
      	</div>      
      </div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<form class="form-prevent-multi-submit" method="POST" action="{{ route('courses.update', $course->id) }}">
			    {{ csrf_field() }}
				<div class="form-group">
					<label for="CourseName" class="control-label">Course Name: </label>
					<input name="CourseName" type="text" class="form-control" value="">
				</div>

				<div class="form-group">
					<label for="FrenchCourseName" class="control-label">Course Name (French): </label>
					<input name="FrenchCourseName" type="text" class="form-control" value="">
				</div>

				<div class="row">
		          <div class="col-md-2 col-md-offset-4">
		            <a href="{{ route('courses.index') }}" class="btn btn-danger btn-block">Back</a>
		          </div>
		          <div class="col-md-2">  
		            <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save</button>
		            <input type="hidden" name="_token" value="{{ Session::token() }}">
		            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
		            {{ method_field('PUT') }}
		          </div>
		        </div>
			</form>
		</div>
	</div>
</div>

@stop

@section('java_script')

<script src="{{ asset('js/submit.js') }}"></script>

@stop