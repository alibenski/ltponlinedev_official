@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-key'></i> Add Role</h1>
    <hr>
    {{-- @include ('errors.list') --}}
    <form method="POST" action="{{ route('roles.store') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label">Name: </label>
            <input name="name" type="text" class="form-control" value="">
        </div>

        <h5><b>Assign Permissions</b></h5>

        <div class='form-group'>
            <div class="checkbox">
              <label>
                @foreach ($permissions as $permmission)
                  <input type="checkbox" name="permissions[]" value="{{ $permmission->id }}" /> {{ ucfirst($permmission->name) }}
                  <br>
                @endforeach
              </label>
            <div>
        </div>
        
        <div class="row">
            <div class="col-sm-4 col-md-offset-2">
              <a href="{{ route('roles.index') }}" class="btn btn-danger btn-block">Back</a>
            </div>
            <div class="col-sm-4">
              <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Add Role</button>
              <input type="hidden" name="_token" value="{{ Session::token() }}">
            </div>
        </div>
    </form>


</div>

@stop