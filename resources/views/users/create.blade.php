@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add User (Auth & SDDEXTR)</h1>
    <hr>
	    <form method="POST" action="{{ route('users.store') }}">
        {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label">Index: </label>
            <input name="indexno" type="text" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="control-label">First Name: </label>
  				  <input name="nameFirst" type="text" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="control-label">Last Name: </label>
            <input name="nameLast" type="text" class="form-control" value="">
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

          <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
              <label for="gender" class=" control-label">Gender</label>
              <div class="dropdown">
                  <select class="form-control select2-basic-single" style="width: 50%;" name="gender" autocomplete="off" >
                      <option value="">--- Please Select ---</option>
                      <option value="F">Female</option>
                      <option value="M">Male</option>
                  </select>
              </div>

              <div class="col-md-6">
                  {{-- <input id="gender" type="text" class="form-control" name="gender" value="{{ old('gender') }}" required autofocus> --}}

                  @if ($errors->has('gender'))
                      <span class="help-block">
                          <strong>{{ $errors->first('gender') }}</strong>
                      </span>
                  @endif
              </div>
          </div>

          <div class="form-group{{ $errors->has('org') ? ' has-error' : '' }}">
              <label for="org" class=" control-label">Organization</label>

                  {{-- <input id="org" type="text" class="form-control" name="org" value="{{ old('org') }}" required autofocus> --}}

                  <div class="dropdown">
                      <select class="form-control select2-basic-single" style="width: 100%;" name="org" autocomplete="off" >
                          <option value="">--- Please Select Organization ---</option>
                              @if(!empty($org))
                                @foreach($org as $value)
                                  <option class="wx" value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{$value['Org Full Name']}}</option>
                                @endforeach
                              @endif
                      </select>
                  </div>

                  @if ($errors->has('org'))
                      <span class="help-block">
                          <strong>{{ $errors->first('org') }}</strong>
                      </span>
                  @endif
          </div>

          <div class="form-group{{ $errors->has('contact_num') ? ' has-error' : '' }}">
              <label for="contact_num" class=" control-label">Contact Number</label>

                  <input id="contact_num" type="text" class="form-control" name="contact_num" value="{{ old('contact_num') }}" required autofocus>

                  @if ($errors->has('contact_num'))
                      <span class="help-block">
                          <strong>{{ $errors->first('contact_num') }}</strong>
                      </span>
                  @endif
          </div>

          <div class="form-group{{ $errors->has('cat') ? ' has-error' : '' }}">
              <label for="cat" class=" control-label">Category</label>
                  <div class="dropdown">
                      <select class="form-control select2-basic-single" style="width: 50%;" name="cat" autocomplete="off" >
                          <option value="">--- Please Select Category ---</option>
                              @if(!empty($cat))
                                @foreach($cat as $key => $value)
                                  <option class="wx" value="{{ $key }}">{{ $value}}</option>
                                @endforeach
                              @endif
                      </select>
                  </div>

              <div class="col-md-6">
                  {{-- <input id="cat" type="text" class="form-control" name="cat" value="{{ old('cat') }}" required autofocus> --}}

                  @if ($errors->has('cat'))
                      <span class="help-block">
                          <strong>{{ $errors->first('cat') }}</strong>
                      </span>
                  @endif
              </div>
          </div>

          <div class="form-group">
            <label class="control-label">Password: </label>
    				<input name="password" type="password" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="control-label">Confirm Password: </label>
    				<input name="password_confirmation" type="password" class="form-control" value="">
          </div>
          
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