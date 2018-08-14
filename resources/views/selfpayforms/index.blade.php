@extends('admin.admin')

@section('content')
@if(is_null($selfpayforms))

@else
{{ $selfpayforms->links() }}
<div class="filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Operation</th>
	            <th>Name</th>
	            <th>Term</th>
	            <th>Language</th>
	            <th>Course</th>
	            <th>Schedule</th>
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($selfpayforms as $form)
			<tr>
				<td>
					<button class="edit-modal btn btn-success"> <span class="glyphicon glyphicon-check"></span> Approve</button>
				</td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>{{ $form->Term }}</td>
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
</div>
@endif

@stop