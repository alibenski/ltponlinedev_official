@extends('admin.admin')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$user->name}} [index # {{ $user->indexno}}]</h1>
    <hr>
    
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label">Name: </label>
            {{-- <input name="name" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $user->name) }}"> --}}
            <input name="name" type="text" class="form-control" readonly value="{{ old('name', $user->name) }}">
            <p class="small text-danger text-justify">The Name field cannot be modified. Please use the fields below instead or ask the users to modify it themselves via the profile page. </p>
        </div>

        <div class="form-group">
            <label class="control-label">Last Name: </label>
            <input name="nameLast" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $user->nameLast) }}">
        </div>
        <div class="form-group">
            <label class="control-label">First Name: </label>
            <input name="nameFirst" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $user->nameFirst) }}">
        </div>

        <div class="form-group">
            <label class="control-label">Email: </label>
            <input name="email" type="email" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('email', $user->email) }}"> 
            {{-- <input name="email" type="email" class="form-control" readonly value="{{ old('email', $user->email) }}">  --}}
            <p class="small text-danger text-justify"><strong>IMPORTANT NOTE:</strong> Once the email address has been changed, this will become <strong>the user's login and official email address</strong> to which we will be sending notifications and other future correspondences. <strong>There will be no email notification sent to the user for this change.</strong> Please communicate this accordingly.</p>
        </div>

        <div class="form-group">
            <input type="checkbox" name="mailing_list" @if ($user->mailing_list == 1) checked="true" 
            @endif/> Subscribed to Mailing List
        </div>

        @if (!$user->roles->isEmpty())
        <h5><b>Current Role</b></h5>
        <div class='form-group'>
            <ul  class="list-group">
            @foreach ($user->roles as $item)
                <li class="list-group-item">
                   {{$item->name}}  <i class="fa fa-check" aria-hidden="true"></i>
                </li>
            @endforeach
            </ul>
        </div>
        @endif

        <h5><b>Give Role</b></h5>
        
        <div class='form-group'>
            <div class="checkbox">
                <label>
                    @foreach ($roles as $role)
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                        @foreach ($user->roles as $item)
                            @if ($item->id == $role->id)
                                checked="true"
                            @endif
                        @endforeach
                    /> {{ ucfirst($role->name) }}
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