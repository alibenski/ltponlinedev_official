@extends('admin.admin')

@section('content')
<div class="row">
	<div class="input-group col-sm-6">
		<h4>Filters:</h4>
		<a href="/admin/preenrolment/?L=A" class="filter-lang btn btn-info">Arabic</a>
		<a href="/admin/preenrolment/?L=C" class="filter-lang btn btn-info">Chinese</a>
		<a href="/admin/preenrolment/?L=E" class="filter-lang btn btn-info">English</a>
		<a href="/admin/preenrolment/?L=F" class="filter-lang btn btn-info">French</a>
		<a href="/admin/preenrolment/?L=R" class="filter-lang btn btn-info">Russian</a>
		<a href="/admin/preenrolment/?L=S" class="filter-lang btn btn-info">Spanish</a>
		<a href="/admin/preenrolment/" class="filter-reset btn btn-danger">Reset</a>
	</div>
{{-- 	<div class="col-sm-6 text-right">
		<h4>Sort:</h4>
		<a href="{{ route('preenrolment.index'), ['L' => Request::has('L'), 'sort' => 'asc'] }}" class="btn btn-success">Asc</a>
		<a href="{{ route('preenrolment.index'), ['L' => Request::has('L'), 'sort' => 'desc'] }}" class="btn btn-danger">Desc</a>
	</div> --}}
</div>


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
		$(".filter-lang").on('click', function() {
			var L = $(this).val();
			console.log(L);
		});
	});
</script>
@stop