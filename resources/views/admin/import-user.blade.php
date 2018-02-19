@extends('admin.admin')

@section('content')
<div class='col-lg-4 col-lg-offset-4'>
	<h1 class="text-danger">Import Users</h1>
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
@endsection