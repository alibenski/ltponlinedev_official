@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="col-md-12">
	{{-- legend for course creation --}}
	<div class="col-md-3">

		<div class="box box-primary">
		    <div class="box-header ui-sortable-handle" style="cursor: move;">
		      <i class="ion ion-clipboard"></i>

		      <h3 class="box-title">Legend:</h3>

		    </div>
		    <!-- /.box-header -->
		    <div class="box-body">
		      <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
		      <ul class="todo-list ui-sortable">
		        		        
		        <li>
					<span class="text"><strong>Level</strong> for specialize courses is defined as elementary, intermediate, or advance.</span>
		        </li>

		        <li>
					<span class="text"><strong>Course Type</strong> defines how the course is categorized.</span>
		        </li>


		        <li>
					<span class="text"><strong>Numerical Category</strong> defines the specificity of a course which is already catergorized under an existing Course Type, e.g. an english writing course for a specific agency, a new french course for level 3 similar to "Cours 3", etc. </span>
		        </li>


		      </ul>
		    </div>
		    <!-- /.box-body -->
		</div>
		
	</div> {{-- end legend section --}}


	{{-- create course section --}}
	<div class="col-sm-9">
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
				<label>Select Level</label>
				<select class="form-control select2-basic-single" style="width: 100%" name="LevelType" autocomplete="off" required="required">
                    @if(!empty($course_level_type))
					@foreach($course_level_type as $keyLevel => $valueLevel)
                    <option></option>
                    <option value="{{$keyLevel}}">{{$valueLevel}}</option>
                    @endforeach
                    @endif
                </select>
			</div>

			<div class="form-group">
				<label>Select Course Type</label>
				<select class="form-control select2-basic-single" style="width: 100%" name="CourseType" autocomplete="off" required="required">
					@if(!empty($course_type))
					@foreach($course_type as $value)
	                <option></option>
	                <option value="{{ $value->CourseType }}">{{ $value->CourseType }} - {{ $value->DescriptionEn }}</option>
	                @endforeach
	                @endif
                </select>
			</div>

			<div class="form-group">
				<label>Select Numerical Category</label>
				<select class="form-control select2-basic-single" style="width: 100%" name="Order" autocomplete="off" required="required">
                    @if(!empty($course_order))
					@foreach($course_order as $keyOrder => $valueOrder)
                    <option></option>
                    <option value="{{$keyOrder}}">{{$valueOrder}}</option>
                    @endforeach
                    @endif
                </select>
			</div>
			
			<div class="form-group">
				<label for="">Course Name</label>
				<div>
					<input name="Description" type="text" style="width: 100%" required="">
				</div>
			</div>

			<div class="form-group">
				<label for="">Course Name (French)</label>
				<div>
					<input name="FDescription" type="text" style="width: 100%" required="">
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