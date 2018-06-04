@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>
    <h1><i class='fa fa-key'></i> Edit Role: {{$role->name}}</h1>
    <hr>
	<form method="POST" action="{{ route('roles.update', $role->id) }}">
		{{ csrf_field() }}
        <div class="form-group">
            <label class="control-label">Role Name: </label>
            <input name="name" type="text" class="form-control" value="">
        </div>

		<h5><b>Assign Permissions</b></h5>

		<div class='form-group'>
            <div class="checkbox">
                <label>
                    @foreach ($permissions as $permission)
                      <input type="checkbox" name="permissions[]" value="{{ $permission->id, $role->permissions }}" /> {{ ucfirst($permission->name) }}
                      <br>
                    @endforeach
                </label>
            </div>
        </div>
		<br>

        <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Edit Save</button>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
		{{ method_field('PUT') }}
	</form>  
</div>

@stop