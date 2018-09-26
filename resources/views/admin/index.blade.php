@extends('admin.admin')

@section('customcss')
<style>
	#placeholder {
    width: 500px;
    height: 300px;
	}
	#placeholder2 {
    width: 500px;
    height: 300px;
	}
</style>
@stop

@section('content')

<h1 class="text-danger">Administrator Dashboard</h1>

<p>Choose Term here</p>
<div class="row">
	<div class="col-sm-12">
		@if ($new_user_count < 5)
		<a href="{{ route('newuser.index') }}" class="btn btn-primary"><span>New User Request </span><span class="badge badge-danger">{{ $new_user_count }} </span></a>
		@else
		<a href="{{ route('newuser.index') }}" class="btn btn-danger"><span><i class="fa fa-lg fa-warning btn-space"></i>  New User Request </span><span class="badge badge-danger">{{ $new_user_count }} </span></a>
		@endif
	</div>
</div>

@endsection