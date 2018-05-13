@extends('admin.admin')

@section('content')
<div class="row">
	<div class="input-group col-sm-8">
		<h4>Filters:</h4>
        <form method="GET" action="{{ route('preenrolment.index') }}">
            <div class="input-group">           
                <div class="input-group-btn">
					<div class="dropdown">
					    <select class="col-md-4 form-control select2-basic-single" style="width: 100%;" name="language" autocomplete="off" >
					        <option value="">--- Please Select ---</option>
					        <option value="A">Arabic</option>
					        <option value="C">Chinese</option>
					        <option value="E">English</option>
					        <option value="F">French</option>
					        <option value="R">Russian</option>
					        <option value="S">Spanish</option>
					    </select>
					</div>
                    <button type="submit" class="btn btn-info button-prevent-multi-submit">Filter Language</button>
                    <a href="/admin/preenrolment/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                </div>
            </div>
        </form>    
    </div>
</div>

{{ $enrolment_forms->links() }}
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
			@foreach($enrolment_forms as $form)
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
	{{ $enrolment_forms->links() }}
</div>
@stop

@section('java_script')
<script>
$(document).ready(function() {
	$("select[name='language']").on('change', function() {
		console.log('get data');
	});
});
</script>
@stop