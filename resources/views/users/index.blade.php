@extends('admin.admin')

@section('content')

<div class="col-sm-10 col-sm-offset-1">
    <h1><i class="fa fa-users"></i> User Administration <a href="{{ route('roles.index') }}" class="btn btn-default pull-right" style="margin: 1px;">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right" style="margin: 1px;">Permissions</a></h1>
    <hr>
    
    <div class="form-group col-sm-12">
        <form method="GET" action="{{ route('users.index') }}">
            {{-- search by name or email--}}
                <label for="search" class="control-label">Search here to check if the student has a login account in the system:</label>         
            <div class="input-group">  
                <input type="text" name="search" class="form-control">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-info button-prevent-multi-submit">Search by Name/Email</button>
                    <a href="/admin/users/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                </div>
            </div>
        </form>    
    </div>

    <div class="form-group col-sm-12">
        <a href="{{ route('users.create') }}" class="btn btn-success">Add Single User</a>
        <a href="{{ route('import-user') }}" class="btn btn-primary">Bulk Import New Users</a>
        <a href="{{ route('import-existing-user') }}" class="btn btn-warning">Bulk Import Existing Users</a>  
    </div>
    {{ $users->links() }}     
    <div class="table-responsive col-sm-12">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Index</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Member Since</th>
                    <th>User Roles</th>
                    <th>Operations</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>

                    <td>{{ $user->indexno }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>@if(empty($user->sddextr->PHONE )) none @else <strong> {{$user->sddextr->PHONE}} </strong>@endif</td>
                    <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
                    <td>{{  $user->roles()->pluck('name')->implode(' ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                    <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-left" style="margin: 1px;">Edit</a>
                    <a href="{{ route('manage-user-enrolment-data', $user->id) }}" class="btn btn-warning pull-left" style="margin: 1px;">LTP Data</a>

					<form method="POST" action="{{ route('users.destroy',  $user->id) }}">
                      <input type="submit" value="Delete" class="btn btn-danger" style="margin: 1px;" disabled="">
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                     {{ method_field('DELETE') }}
                  	</form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>


@stop