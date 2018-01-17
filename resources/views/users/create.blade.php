@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add User</h1>
    <hr>
	    <form method="POST" action="{{ route('users.store') }}">
        {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label">Name: </label>
  				  <input name="name" type="text" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="control-label">Email: </label>
  				  <input name="email" type="email" class="form-control" readonly onfocus="this.removeAttribute('readonly');">
          </div>

			    <div class='form-group'>
			    		<label class="control-label">Role: </label>
              <div class="checkbox">
                  <label>
                    @foreach ($roles as $role)
                      <input type="checkbox" name="roles[]" value="{{ $role->id }}" /> {{ ucfirst($role->name) }}
                      <br>
                    @endforeach
                  </label>
						  </div>
			    </div>

          <!-- remove password fields

          <div class="form-group">
            <label class="control-label">Password: </label>
    				<input name="password" type="password" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="control-label">Confirm Password: </label>
    				<input name="password_confirmation" type="password" class="form-control" value="">
          </div>
          
          EOF remove fields -->

          <div class="row">
            <div class="col-sm-4 col-md-offset-2">
              <a href="{{ route('users.index') }}" class="btn btn-danger btn-block">Back</a>
            </div>
            <div class="col-sm-4">
              <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Add User</button>
              <input type="hidden" name="_token" value="{{ Session::token() }}">
            </div>
          </div>
      </form>
</div>

@stop