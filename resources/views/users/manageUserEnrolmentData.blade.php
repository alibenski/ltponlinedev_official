@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="row col-sm-12">
	<h3>Viewing: <strong>{{ $student->name }}</strong></h3>
	<h3>@if(Request::input('Term'))Term: {{ Request::input('Term') }} @else Please Choose Term @endif</h3>
   	<div class="row col-sm-3">
		<form method="GET" action="{{ route('manage-user-enrolment-data', $id) }}">
			
			<div class="form-group input-group col-sm-12">
				<h4><strong>Filters:</strong></h4>

				<div class="form-group">
		          <label for="Term" class="col-md-12 control-label">Term Select:</label>
		          <div class="form-group col-sm-12">
		            <div class="dropdown">
		              <select id="Term" name="Term" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
		                @foreach($terms as $value)
		                    <option></option>
		                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} - {{$value->Comments}} - {{$value->Term_Name}}</option>
		                @endforeach
		              </select>
		            </div>
		          </div>
		        </div>

				{{-- <div class="form-group">
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
			      <label for="organization" class="col-md-12 control-label"> Organization:</label>
			      <div class="form-group col-sm-12">
		            <div class="dropdown">
		              <select id="input" name="DEPT" class="col-md-10 form-control select2-basic-single" style="width: 100%;">
		                @if(!empty($org))
		                  @foreach($org as $value)
		                    <option></option>
		                    <option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
		                  @endforeach
		                @endif
		              </select>
		            </div>
		          </div>
		        </div> --}}

			</div> {{-- end filter div --}}


		    <div class="form-group">           
		        <button type="submit" class="btn btn-success">Submit</button>
		    	<a href="{{ route('manage-user-enrolment-data', $id) }}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
		    </div>
		</form>
	</div>
    @if(is_null($student_enrolments))

	@else
	<div class="filtered-table">
		<table class="table table-bordered table-striped">
		    <thead>
		        <tr>
		            <th>Name</th>
		            <th>Language</th>
		            <th>Course</th>
		            <th>Schedule</th>
		            <th>Manager Approval</th>
		            <th>HR Approval</th>
		            <th>ID Proof</th>
		            <th>Payment Proof</th>
		            <th>Time Stamp</th>
		        </tr>
		    </thead>
		    <tbody>
				@foreach($student_enrolments as $form)
				<tr>
					<td>
					@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
					</td>
					<td>{{ $form->L }}</td>
					<td>{{ $form->courses->Description }}</td>
					<td>{{ $form->schedule->name }}</td>
					<td>{{ $form->approval }}</td>
					<td>{{ $form->approval_hr }}</td>
					<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank">carte attachment</a> @endif
					</td>
					<td>
					@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank">payment attachment</a> @endif
					</td>
					<td>{{ $form->created_at}}</td>
				</tr>
				@endforeach
		    </tbody>
		</table>
	</div>
	@endif
</div>
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "--- Select Here ---",
    });
});
</script>
@stop