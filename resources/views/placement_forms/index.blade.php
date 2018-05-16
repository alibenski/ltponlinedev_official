@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="row col-sm-12">
    <form method="GET" action="{{ route('placement-form.index',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT')]) }}">
		
		<div class="input-group col-sm-12">
			<h4><strong>Filters:</strong></h4>
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
		</div>

        <div class="form-group">           
	      <label for="organization" class="control-label">Filter Organization:</label>
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

        <div class="form-group">           
	        <button type="submit" class="btn btn-success" value="UNOG">Submit</button>
        	<a href="/admin/placement-form/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>

        <div class="form-group">    
            <div class="input-group-btn">
		        <a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
		        <a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
            </div>
        </div>
    </form>
</div>

{{ $placement_forms->links() }}
<div class="filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Organization</th>
	            <th>Language</th>
	            <th>Schedule</th>
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
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>
				@if(empty($form->DEPT)) None @else <strong> {{ $form->DEPT }} </strong> @endif
				</td>
				<td>{{ $form->L }}</td>
				<td>@if ($form->L === "F") Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} @else {{ $form->placementSchedule->date_of_plexam }} @endif</td>
				<td>@if ($form->approval === 1) approved @elseif ($form->approval === 0) disapproved @else pending @endif</td>
				<td>@if ($form->approval_hr === 1) approved @elseif ($form->approval_hr === 0) disapproved @else pending @endif</td>
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
	{{ $placement_forms->links() }}
</div>
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Organization",
    });
});
</script>
@stop