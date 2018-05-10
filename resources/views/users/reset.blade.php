@extends('admin.admin')

@section('content')

<div class='col-lg-6 col-lg-offset-3'>

    <h1><i class='fa fa-user-plus'></i> Reset Password for {{$user->name}}</h1>
    <hr>
    
    <form method="POST" action="{{ route('users.resetpassword', $user->id) }}">
        {{ csrf_field() }}
        <div class="form-group">
        <label class="control-label">Password: </label>
        <input name="password" type="password" class="form-control" value="">
        </div>

        <div class="form-group">
        <label class="control-label">Confirm Password: </label>
        <input name="password_confirmation" type="password" class="form-control" value="">
        </div>
        
        <button type="submit" class="btn btn-danger btn-block button-prevent-multi-submit">Reset Password</button>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        {{ method_field('PUT') }}
    </form>

</div>


@stop