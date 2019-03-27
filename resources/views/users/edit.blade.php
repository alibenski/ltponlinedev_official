@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$user->name}}</h1>
    <hr>
    
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label">Name: </label>
            {{-- <input name="name" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $user->name) }}"> --}}
            <input name="name" type="text" class="form-control" readonly value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-group">
            <label class="control-label">Email: </label>
            {{-- <input name="email" type="email" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('email', $user->email) }}">  --}}
            <input name="email" type="email" class="form-control" readonly value="{{ old('email', $user->email) }}"> 
        </div>

        <h5><b>Give Role</b></h5>
        
        <div class='form-group'>
            <div class="checkbox">
                <label>
                    @foreach ($roles as $role)
                      <input type="checkbox" name="roles[]" value="{{ $role->id, $user->roles }}" /> {{ ucfirst($role->name) }}
                      <br>
                    @endforeach
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <a href="{{ route('users.passwordreset', $user->id) }}" class="btn btn-danger"><i class='fa fa-undo'></i> Password Reset Here</a>
        </div>
        
        <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save</button>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        {{ method_field('PUT') }}
    </form>

</div>


@stop