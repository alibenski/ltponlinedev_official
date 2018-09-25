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
                    <button type="submit" class="btn btn-info button-prevent-multi-submit">Search by Name</button>
                    <a href="/admin/newuser/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                </div>
            </div>
        </form>    
    </div>
<div class="table-responsive col-lg-12">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>#</th>
	            <th>Index</th>
	            <th>Name</th>
	            <th>Email</th>
	            <th>Org</th>
	            <th>DOB</th>
	            <th>Approved</th>
	            <th>Date/Time Added</th>
	            <th>Operations</th>
	        </tr>
	    </thead>
	    <tbody>
	        @foreach ($users as $user)
	        <tr>
	            <td>{{ $user->id }}</td>
	            <td>{{ $user->indexno_new }}</td>
	            <td>{{ $user->name }}</td>
	            <td>{{ $user->email }}</td>
	            <td>{{ $user->org }}</td>
	            <td>@if(empty($user->dob)) n/a @else {{ $user->dob->format('F d, Y') }} @endif</td>
	            <td>{{ $user->approved_account }}</td>
	            <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
	            <td>
	            <button class="show-modal btn btn-warning" data-id="{{$user->id}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button>
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
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="id">ID:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_show" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="title">Title:</label>
                        <div class="col-sm-10">
                            <input type="name" class="form-control" id="title_show" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="content">Content:</label>
                        <div class="col-sm-10">
                            <textarea style="display: none;" class="form-control" id="content_show" cols="40" rows="5" disabled></textarea>
                        </div>
                    </div>
                    <div class="form-group class-list"></div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('java_script')
<script>
// Show a post
$(document).on('click', '.show-modal', function() {
    $('.modal-title').text('Show Details');
    $('#id_show').val($(this).data('id'));
    $('#title_show').val($(this).data('title'));
    var id = $(this).data('id');
    $('#showModal').modal('show');

    $.ajax({
        url: '{{ route('newuser.show', $user->id) }}',
        type: 'GET',
        data: {'id' : id,
        },
    })
    .done(function(data) {
        console.log("success");
        console.log(data);
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