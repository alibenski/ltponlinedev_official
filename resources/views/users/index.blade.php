@extends('admin.admin')

@section('content')

<div class="col-sm-12">
    <h1><i class="fa fa-users"></i> User Administration 
    @hasrole('Admin')
    <a href="{{ route('roles.index') }}" class="btn btn-default pull-right" style="margin: 1px;">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right" style="margin: 1px;">Permissions</a>
    {{-- <a href="user/switch/start/271" class="btn btn-default pull-right" style="margin: 1px;">Login as 1</a>

        @if( Session::has('orig_user') )
        <a href="user/switch/stop" class="btn btn-default pull-right" style="margin: 1px;">Switch back to orig</a>
        @endif --}}
    @endhasrole
    </h1>
    <hr>
    
    <div class="form-group col-sm-12">
        <form method="GET" action="{{ route('users.index', ['search' => \Request::input('search')]) }}">
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
        <a href="{{ route('late-user-management') }}" class="btn btn-danger">Manage Late User Accounts</a>
        @if (Auth::id() === 701)
        <a href="{{ route('import-user') }}" class="btn btn-primary">Bulk Import New Users</a>
        <a href="{{ route('import-existing-user') }}" class="btn btn-warning">Bulk Import Existing Users</a>  
        @endif
    </div>
    {{ $users->links() }}     
    <div class="table-responsive col-sm-12">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Operations</th>
                    <th>Index</th>
                    <th>Name</th>
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
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-left" style="margin: 1px;">Edit</a>
                    <a href="{{ route('manage-user-enrolment-data', $user->id) }}" class="btn btn-warning pull-left" style="margin: 1px;">LTP Data</a>
                    <button id="confirmBtn{{ $user->id }}" data-id="{{ $user->id }}" data-email="{{ $user->email }}" type="button" class="btn btn-space btn-danger  button-prevent-multi-submit confirm" title="Send Late Enrolment Form"><i class="fa fa-envelope"></i> Late</button>

					{{-- <form method="POST" action="{{ route('users.destroy',  $user->id) }}">
                      <input type="submit" value="Delete" class="btn btn-danger" style="margin: 1px;" disabled="">
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                     {{ method_field('DELETE') }}
                  	</form> --}}
                    </td>
                    <td>{{ $user->indexno }}</td>
                    <td>{{ $user->name }}</td>
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

<div id="showModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Send Late Registration Form</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form class="text-left">
                        <div class="form-group col-md-12">
                            <p>You are sending this late enrolment form to <strong><span class="modal-span-email"></span></strong></p>
                            <p>
                            You confirm that this has been approved by the CLM LTP chief and if necessary, the HR Focal Point of the student's organization/agency.  
                            </p>
                            <div class="col-md-12">
                            <input type="hidden" id="email" name="email" class="col-md-6 modal-input-email" value="" autofocus required>
                            </div>
                            <button id="saveBtn" type="button" class="btn btn-space btn-success btn-block button-prevent-multi-submit send-late-btn" style="margin: 1px;">
                            <i class="fa fa-envelope"></i> Send Email
                            </button>
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('java_script')
<script>
$(document).ready(function(){
    $('input[name=search]').focus();
});

$('button.confirm').on('click', function(event) {
    event.preventDefault();
    let id = $(this).attr("data-id");
    let email = $(this).attr("data-email");
    
    $('#showModal').modal('show');
    $('span.modal-span-email').text(email);
    $('input.modal-input-email').val(email);
});

$('button.send-late-btn').on('click', function(event) {
    event.preventDefault();
    $(this).attr('disabled', 'disabled');
    let email = $('input.modal-input-email').val();
    let token = $("input[name='_token']").val();
    
    if (email) {
        $.ajax({
            url: '{{ route('generate-URL-late-enrolment') }}',
            type: 'POST',
            data: {email:email, _token:token},
        })
        .done(function(data) {
            $("input#email").val('');
            $("input.url-link").val(data);
            $("button#generateLink").removeAttr("disabled");
        })
        .fail(function() {
            console.log("error");
            $("button#generateLink").removeAttr("disabled");
        })
        .always(function() {
            console.log("complete");
            alert('email with link has been sent');
        });  

        return true;
    }

    return alert('email address missing'); 
});

$('#showModal').on('hidden.bs.modal', function (e) {
    $('button.send-late-btn').removeAttr('disabled');
  })
</script>
@stop