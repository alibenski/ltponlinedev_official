@extends('admin.admin')

@section('content')

<table class="table table-bordered table-striped">

    <thead>
        <tr>
            <th>Name</th>
            <th>Language</th>
            <th>Course</th>
            <th>Schedule</th>
            <th>Attachment ID</th>
            <th>Attachment Payment</th>
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