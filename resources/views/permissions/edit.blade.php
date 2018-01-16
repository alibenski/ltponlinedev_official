@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-key'></i> Edit {{$permission->name}}</h1>
    <br>

    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
		{{ csrf_field() }}
        <div class="form-group">
            <label class="control-label">Permission Name: </label>
            <input name="name" type="text" class="form-control" value="">
        </div>
		<br>
        <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Edit Save</button>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
		{{ method_field('PUT') }}
	</form>  
</div>

@stop