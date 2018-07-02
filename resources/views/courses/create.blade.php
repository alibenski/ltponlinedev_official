@extends('admin.admin')

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
						<select class="col-md-8 form-control" name="L" autocomplete="off" required="required">
			                <option value="">--- Select Course Type ---</option>
			                <option value="">Advanced</option>
			                <option value="">Regular</option>
			                <option value="">Oral</option>
		                </select>
					</div>
					<div class="form-group">
						<label>Select Level</label>
						<select class="col-md-8 form-control" name="L" autocomplete="off" required="required">
		                    <option value="">--- Select Level Type ---</option>
		                    <option value="">Level 1</option>
		                    <option value="">Intermediate</option>
		                    <option value="">Advance</option>
		                </select>
					</div>
					<div class="form-group">
						<label>Select Order</label>
						<select class="col-md-8 form-control" name="L" autocomplete="off" required="required">
		                    <option value="">--- Select Order ---</option>
		                    <option value="">1</option>
		                    <option value="">2</option>
		                    <option value="">3</option>
		                </select>
					</div>
					
					<div class="form-group">
						<label for="">Course Description</label>
						<div>
							<input type="text" style="width: 100%">
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