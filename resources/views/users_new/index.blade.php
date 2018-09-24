@extends('admin.admin')

@section('content')
<h1><i class="fa fa-users"></i> New User Administration </h1>
    <hr>
    
    <div class="form-group col-lg-4">
        <form method="GET" action="{{ route('newuser.index') }}">
            {{-- search by name or email--}}
            <div class="input-group">           
                <input type="text" name="search" class="form-control">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-info button-prevent-multi-submit">Search by Name/Email</button>
                    <a href="/admin/newuser/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                </div>
            </div>
        </form>    
    </div>
<div class="table-responsive col-lg-12">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Index</th>
	            <th>Name</th>
	            <th>Email</th>
	            <th>DOB</th>
	            <th>Approved</th>
	            <th>Date/Time Added</th>
	            <th>Operations</th>
	        </tr>
	    </thead>
	    <tbody>
	        @foreach ($users as $user)
	        <tr>
	            <td>{{ $user->indexno_new }}</td>
	            <td>{{ $user->name }}</td>
	            <td>{{ $user->email }}</td>
	            <td>@if(empty($user->dob)) n/a @else {{ $user->dob->format('F d, Y') }} @endif</td>
	            <td>{{ $user->approved_account }}</td>
	            <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
	            <td>
	            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-left" style="margin: 1px;" @if($user->approved_account === 0) @else disabled @endif>Edit</a>
	            {{-- <a href="{{ route('manage-user-enrolment-data', $user->id) }}" class="btn btn-success pull-left" style="margin: 1px;">LTP Data</a> --}}
				{{-- <form method="POST" action="{{ route('users.destroy',  $user->id) }}">
	              <input type="submit" value="Delete" class="btn btn-danger" style="margin: 1px;" disabled="">
	              <input type="hidden" name="_token" value="{{ Session::token() }}">
	             {{ method_field('DELETE') }}
	          	</form> --}}
	            </td>
	        </tr>
	        @endforeach
	    </tbody>

	</table>
	{{ $users->links() }}     
</div>

@stop