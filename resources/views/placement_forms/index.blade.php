@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<h2>Placement Test Forms</h2>
<div class="row col-sm-12">
	@if(Request::input('Term'))<h4 class="alert alert-info pull-right">Currently Viewing: {{ Request::input('Term') }} </h4>@else @endif
<ul class="nav nav-pills">
	<li role="presentation" class="{{ Request::is('home') ? "active" : ""}}"><a href="/home">Approved</a></li>
	<li role="presentation" class="{{ Request::is('students') ? "active" : ""}}"><a href="{{ route('students.index') }}">Cancelled</a></li>
	<li role="presentation" class="{{ Request::is('history') ? "active" : ""}}"><a href="/history">Pending</a></li>
	<li role="presentation" class="{{ Request::is('history') ? "active" : ""}}"><a href="{{ route('index-placement-selfpay') }}">Manage Self-Paying Placement Forms</a></li>
</ul>

    <form method="GET" action="{{ route('placement-form.index',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Request::input('Term')]) }}">
		
		<div class="form-group input-group col-sm-12">
			<h4><strong>Filters:</strong></h4>

			<div class="form-group">
	          <label for="Term" class="col-sm-12 control-label">Term Select:</label>
	          <div class="form-group col-sm-12">
	            <div class="dropdown">
	              <select id="Term" name="Term" class="col-sm-8 form-control select2-basic-single" style="width: 100%;">
	                @foreach($terms as $value)
	                    <option></option>
	                    <option value="{{ $value->Term_Code }}">{{$value->Comments}} - {{$value->Term_Name}}</option>
	                @endforeach
	              </select>
	            </div>
	          </div>
	        </div>
	        
	        <div class="form-group col-sm-12">
	        	<label for="search" class="control-label">Search by Name:</label>
				<input type="text" name="search" class="form-control" placeholder="Enter name here">
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
		      <label for="organization" class="col-sm-12 control-label"> Organization:</label>
		      <div class="form-group col-sm-12">
	            <div class="dropdown">
	              <select id="input" name="DEPT" class="col-sm-10 form-control select2-basic-single" style="width: 100%;">
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
        	<a href="/admin/placement-form/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>

        <div class="form-group">    
            <div class="input-group-btn">
		        <a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Request::input('Term'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
		        <a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Request::input('Term'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
            </div>
        </div>
    </form>
</div>

@if(is_null($placement_forms))

@else
{{ $placement_forms->links() }}
<div class="filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	        	<th>Operation</th>
	            <th>Name</th>
	            <th>Organization</th>
	            <th>Language</th>
	            <th>Student Cancelled?</th>
	            <th>Exam Date</th>
	            <th>Manager Approval</th>
	            <th>HR Approval</th>
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($placement_forms as $form)
			<tr>
				<td>
					{{-- <button class="show-modal btn btn-warning" data-index="{{$form->INDEXID}}" data-tecode="{{$form->Te_Code}}" data-term="{{$form->Term}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button> --}}
                    <a href="{{ route('placement-form.edit', [$form->id]) }}" class="btn btn-warning"><span class="glyphicon glyphicon-eye-open"></span> Show</a> 
                </td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>
				@if(empty($form->DEPT)) None @else {{ $form->DEPT }}  @endif
				</td>
				<td>{{ $form->L }}</td>
				<td>
					@if( is_null($form->cancelled_by_student))
					@else <span id="status" class="label label-danger margin-label">YES</span>
					@endif
				</td>
				<td>@if ($form->L === "F") Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} @else {{ $form->placementSchedule->date_of_plexam }} @endif</td>
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
	{{ $placement_forms->links() }}
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