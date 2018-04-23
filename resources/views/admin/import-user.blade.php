@extends('admin.admin')

@section('content')
<h1 class="text-danger">Import Users</h1>
<div class='col-sm-4'>
	<div class="box box-primary">
		<div class="box-body">
			<form action="{{ route('bulk-import-user') }}" method="POST" enctype="multipart/form-data">
				{{ csrf_field() }}
					<div class="form-group">
						<label for="file">Select a file to import</label>
						<input type="file" name="file" class="form-control" placeholder="Input field">
					</div>
					<div class="form-group">
						<button class="btn btn-primary">
							<i class="fa fa-upload"></i> Upload
						</button>
					</div>
			</form>		
		</div>
	</div>
</div>

<div class="col-sm-8">
	@if ($errors_arr = Session::get('error_rows'))
	<div class="box box-danger">
		<div class="box-header with-border">
			<h3 class="box-title">Error in data</h3>
		</div>
		<div class="box-body">
			<table class="table table-hover table-striped">
{{-- 				<thead>
					<tr>
					@foreach ($errors_arr[0] as $key => $value)
					<th>{{ ucfirst($key) }}</th>
					@endforeach
					</tr>
				</thead> --}}
				<tbody>
					@foreach($errors_arr as $key => $value)
					<tr>
						@foreach($value as $data)
						<td>{{ $data }}</td>
						@endforeach
						<td></td>
					</tr>
					@endforeach
				</tbody>				
			</table>
		</div>
		<div class="box-footer">
			
		</div>
	</div>
{{-- 		@foreach ($errors_arr as $error_arr_again)
			<p class="alert alert-danger alert-block"><strong>Details & Error Msg:</strong></p>
			@foreach ($error_arr_again as $key => $arr)
				<ul>
					<li>{{ $key }} - {{ $arr }}</li>
				</ul>
			@endforeach
		@endforeach --}}
	@endif
</div>
@endsection