@extends('admin.admin')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-key"></i>Available Permissions

    <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
    <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Permissions</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td> 
                    <td>
                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

					<form method="POST" action="{{ route('permissions.destroy',  $permission->id) }}">
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

    <a href="{{ route('permissions.create') }} " class="btn btn-success">Add Permission</a>

</div>


@stop