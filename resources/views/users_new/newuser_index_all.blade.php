@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
@include('users_new.new_user_nav')

<h1><i class="fa fa-eye"></i> Viewing All New User Requests and Status </h1>
    <hr>
    <div class="row col-sm-12">
    	<div class="alert alert-info">
    		<p><strong>NOTE: </strong>If the new user requestor already has an account in the system, please contact him and provide him with his credentials. Reset his password if necessary.</p>
    	</div>
    </div>
    <div class="form-group col-lg-4">
        <form method="GET" action="{{ route('newuser-index-all') }}">
            {{-- search by name or email--}}
            <div class="input-group">           
                <input type="text" name="search" class="form-control">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-info button-prevent-multi-submit">Search by Name</button>
                    <a href="{{ route('newuser-index-all')}}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                </div>
            </div>
        </form>    
    </div>
<div class="table-responsive col-lg-12">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>#</th>
	            <th>Name</th>
	            <th>Email</th>
	            <th>Status</th>
	            <th>Date/Time Requested</th>
	            <th>Operations</th>
	        </tr>
	    </thead>
	    <tbody>
	        @foreach ($users as $user)
	        <tr>
	            <td>{{ $user->id }}</td>
	            <td>{{ $user->name }}</td>
	            <td>{{ $user->email }}</td>
	            <td>
					@if ($user->approved_account == 1)
						<span class="label label-success">Approved</span>
					@elseif ($user->approved_account == 2)
						<span class="label label-danger">Disapproved</span>
					@elseif ($user->approved_account == 3)
						<span class="label label-warning">Pending</span>
					@else
						<span class="label label-info">Waiting for Admin</span>
					@endif
	            </td>
	            <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
	            <td>
	            <button class="show-modal btn btn-warning" data-id="{{$user->id}}"
	            	data-fullname="{{$user->name}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button>
	            </form>
	            </td>
	        </tr>
	        @endforeach
	    </tbody>

	</table>
	{{ $users->links() }}     
</div>
<!-- Modal form to show a post -->
<div id="showModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="id_show" disabled>
                <div class="form-group class-list"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/submit.js') }}"></script>
<script>
$(document).on('click', '.show-modal', function() {
    $('.modal-title').text('Show Details');
    $('#id_show').val($(this).data('id'));
    $('#fullName').val($(this).data('fullname'));
    var id = $(this).data('id');
    $('#showModal').modal('show');
	    $.ajax({
	        url: '{{ route('edit-new-user') }}',
	        type: 'GET',
	        data: {'id' : id, 
	        },
	    })
	    .done(function(data) {
	        console.log("success");
	        // console.log(data);
	        $(".class-list").html('');
	        $(".class-list").html(data.options);
	        $( '#accordion' ).accordion({collapsible: true,heightStyle: "content"});
	    })
	    .fail(function() {
	        console.log("error");
	    })
	    .always(function() {
	        console.log("complete");
	    });    
});
</script>

@stop