@extends('admin.admin')

@section('content')

{{ $enrolment_forms->links() }}
<div class="dropdown">
	<select name="language" id="language">
		<option value="">Select Language</option>
		<option value="A">Arabic</option>
		<option value="C">Chinese</option>
		<option value="E">English</option>
		<option value="F">French</option>
		<option value="R">Russian</option>
		<option value="S">Spanish</option>
	</select>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Language</th>
            <th>Course</th>
            <th>Schedule</th>
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
@stop

@section('java_script')
<script>
	$("#language").on('change',function() {
		var L = $("select[name='language']").val();
		console.log(L);
	});
</script>
@stop