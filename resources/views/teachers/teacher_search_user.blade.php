@extends('teachers.teacher_template')

@section('content')

<div class="col-sm-12">
    <h1><i class="fa fa-users"></i> User Search </h1>
    <hr>
    
    <div class="form-group col-sm-12">
        <form method="GET" action="{{ route('teacher-search-user', ['search' => \Request::input('search')]) }}">
            {{-- search by name or email--}}
                <label for="search" class="control-label"></label>         
            <div class="input-group">  
                <input type="text" name="search" class="form-control">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-info button-prevent-multi-submit">Search by Name/Email</button>
                    <a href="/admin/users/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                </div>
            </div>
        </form>    
    </div>

    {{ $users->links() }}     
    <div class="table-responsive col-sm-12">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Operations</th>
                    <th>Index</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Member Since</th>
                    <th>Has logged in?</th>
                    <th>Last Login At</th>
                    <th>User Roles</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>
                    <a href="{{ route('teacher-ltpdata-view', $user->id) }}" class="btn btn-warning pull-left" style="margin: 1px;">LTP Data</a>
                    </td>
                    <td>{{ $user->indexno }}</td>
                    <td>{{ $user->nameLast }}</td>
                    <td>{{ $user->nameFirst }}</td>
                    <td>{{ $user->email }}</td>
                    <td>@if(empty($user->sddextr->PHONE )) none @else <strong> {{$user->sddextr->PHONE}} </strong>@endif</td>
                    <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>                    
                    <td>
                        @if( $user->created_at != $user->updated_at)
                            <h4><span class="label label-success">Yes</span></h4>
                        @else
                            <h4><span class="label label-danger">Never</span></h4>
                        @endif
                    </td>
                    <td>
                        @if ($user->last_login_at)
                            {{ $user->last_login_at}}
                        @endif
                    </td>                    
                    <td>{{  $user->roles()->pluck('name')->implode(' ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@stop

@section('java_script')
<script>
$(document).ready(function(){
    $('input[name=search]').focus();
});

</script>
@stop