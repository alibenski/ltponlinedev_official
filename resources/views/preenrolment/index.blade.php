@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<h2>Enrolment Forms</h2>
<div class="row col-sm-12">
	@if(Request::input('Term'))<h4 class="alert alert-info pull-right">Currently Viewing: {{ Request::input('Term') }} </h4>@else <h4 class="alert alert-info">Please Choose Term</h4> @endif
	{{-- <ul class="nav nav-pills">
		<li role="presentation" class="{{ Request::is('home') ? "active" : ""}}"><a href="#">Approved</a></li>
		<li role="presentation" class="{{ Request::is('students') ? "active" : ""}}"><a href="#">Cancelled</a></li>
		<li role="presentation" class="{{ Request::is('history') ? "active" : ""}}"><a href="#">Pending</a></li>
	</ul> --}}
	<a href="{{ route('selfpayform.index') }}"  class="btn btn-info pull-right">Manage Self-Paying Enrolments</a>

    <form method="GET" action="{{ route('preenrolment.index',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Request::input('Term')]) }}">
		
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
	        </div>

		</div> {{-- end filter div --}}


        <div class="form-group">           
	        <button type="submit" class="btn btn-success" value="UNOG">Submit</button>
        	<a href="/admin/preenrolment/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>

        <div class="form-group">    
            <div class="input-group-btn">
		        <a href="{{ route('preenrolment.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Request::input('Term'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
		        <a href="{{ route('preenrolment.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Request::input('Term'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
            </div>
        </div>
    </form>
</div>

@if(is_null($enrolment_forms))

@else
{{ $enrolment_forms->links() }}
<div class="filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Term</th>
	            <th>Organization</th>
	            <th>Course</th>
	            <th>Schedule</th>
	            <th>Student Cancelled?</th>
	            <th>Manager Approval</th>
	            <th>HR Approval</th>
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($enrolment_forms as $form)
			<tr>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>{{ $form->Term }}</td>
				<td>{{ $form->DEPT }}</td>
				<td>{{ $form->courses->Description }}</td>
				<td>{{ $form->schedule->name }}</td>
				<td>
					@if( is_null($form->cancelled_by_student))
					@else <span id="status" class="label label-danger margin-label">YES</span>
					@endif
				</td>
				<td>
					@if($form->is_self_pay_form == 1)
					<span id="status" class="label label-info margin-label">
					N/A - Self Payment</span>
					@elseif(is_null($form->approval))
					<span id="status" class="label label-warning margin-label">
					Pending Approval</span>
					@elseif($form->approval == 1)
					<span id="status" class="label label-success margin-label">
					Approved</span>
					@elseif($form->approval == 0)
					<span id="status" class="label label-danger margin-label">
					Disapproved</span>
					@endif 
				</td>
				<td>
					@if(is_null($form->is_self_pay_form))
						@if(in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
							<span id="status" class="label label-info margin-label">
							N/A - Non-paying organization</span>
						@else
							@if(is_null($form->approval) && is_null($form->approval_hr))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($form->approval == 0 && (is_null($form->approval_hr) || isset($form->approval_hr)))
							<span id="status" class="label label-danger margin-label">
							N/A - Disapproved by Manager</span>
							@elseif($form->approval == 1 && is_null($form->approval_hr))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($form->approval == 1 && $form->approval_hr == 1)
							<span id="status" class="label label-success margin-label">
							Approved</span>
							@elseif($form->approval == 1 && $form->approval_hr == 0)
							<span id="status" class="label label-danger margin-label">
							Disapproved</span>
							@endif
						@endif
					@else
					<span id="status" class="label label-info margin-label">
					N/A - Self Payment</span>
					@endif
				</td>
				<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
				</td>
				<td>
				@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
				</td>
				<td>{{ $form->created_at}}</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
	{{ $enrolment_forms->links() }}
</div>
@endif
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });
});
</script>
@stop