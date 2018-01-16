@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-key'></i> Add Permission</h1>
    <br>
	<form method="POST" action="{{ route('permissions.store') }}">
        {{ csrf_field() }}
    	<div class="form-group">
        	<label class="control-label">Name: </label>
			<input name="name" type="text" class="form-control" value="">
      	</div>
	    @if(!$roles->isEmpty()) //If no roles exist yet
	    
		    <h4>Assign Permission to Roles</h4>

		    <div class="form-gourp">
		    	<div class="checkbox">
	                  <label>
	                    @foreach ($roles as $role)
	                      <input type="checkbox" name="roles[]" value="{{ $role->id }}" /> {{ ucfirst($role->name) }}
	                      <br>
	                    @endforeach
	                  </label>
				</div>
		    </div>
		@endif
		<br>

		<div class="row">
			<div class="col-sm-4 col-md-offset-2">
			  <a href="{{ route('permissions.index') }}" class="btn btn-danger btn-block">Back</a>
			</div>
			<div class="col-sm-4">
			  <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Add Permission</button>
			  <input type="hidden" name="_token" value="{{ Session::token() }}">
			</div>
		</div>
    </form>
</div>

@stop