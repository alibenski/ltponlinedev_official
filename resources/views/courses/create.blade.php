@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
		<div class="col-md-10 col-md-offset-1">
			{{-- legend for course creation --}}
			<div class="col-sm-12">
				<div class="alert alert-info">
					<h5>Code Legend:</h5>

				</div>
			</div>

			{{-- create course section --}}
			<div class="col-sm-12">
				<form method="POST" action="{{ route('courses.store') }}">
					<label>Select Language</label>
					<div class="form-group">
						@foreach ($languages as $id => $name)
						<div class="col-sm-4">
							<div class="input-group"> 
			                  <span class="input-group-addon">       
			                    <input type="radio" name="L" value="{{ $id }}" >                 
			                  </span>
			                    <label type="text" class="form-control">{{ $name }}</label>
			              	</div>
						</div>
			            @endforeach					
					</div>
					<div class="form-group">
						<label>Select Course Type</label>
						<select class="col-md-8 form-control select2-basic-single" name="CourseType" autocomplete="off" required="required">
							@if(!empty($course_type))
							@foreach($course_type as $key => $value)
			                <option></option>
			                <option value="{{ $key }}">{{ $value }}</option>
			                @endforeach
			                @endif
		                </select>
					</div>
					<div class="form-group">
						<label>Select Level</label>
						<select class="col-md-8 form-control select2-basic-single" name="LevelType" autocomplete="off" required="required">
		                    @if(!empty($course_level_type))
							@foreach($course_level_type as $keyLevel => $valueLevel)
		                    <option></option>
		                    <option value="{{$keyLevel}}">{{$valueLevel}}</option>
		                    @endforeach
		                    @endif
		                </select>
					</div>
					<div class="form-group">
						<label>Select Order</label>
						<select class="col-md-8 form-control select2-basic-single" name="Order" autocomplete="off" required="required">
		                    @if(!empty($course_order))
							@foreach($course_order as $keyOrder => $valueOrder)
		                    <option></option>
		                    <option value="{{$keyOrder}}">{{$valueOrder}}</option>
		                    @endforeach
		                    @endif
		                </select>
					</div>
					
					<div class="form-group">
						<label for="">Course Description</label>
						<div>
							<input name="Description" type="text" style="width: 100%" required="">
						</div>
					</div>

					<div class="row">
			          <div class="col-md-2 col-md-offset-4">
			            <a href="{{ route('courses.index') }}" class="btn btn-danger btn-block">Back</a>
			          </div>
			          <div class="col-md-2">  
			            <button id="setVal" type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save</button>
			            <input type="hidden" name="_token" value="{{ Session::token() }}">
			          </div>
			        </div>
				</form>
			</div>
		</div>
@stop
@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/submit.js') }}"></script>
<script>
	$(document).ready(function() {
    //  select2 dropdown init
	    $('.select2-basic-single').select2({
	    placeholder: "Select Here",
	    });
  	});
</script>
@stop