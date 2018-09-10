@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content')
@if(is_null($selfpayforms))

@else
{{-- {{ $selfpayforms->links() }} --}}
<div class="filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Operation</th>
	            <th>Status</th>
	            <th>Name</th>
	            <th>Term</th>
	            <th>Language</th>
	            <th>Course</th>
	            {{-- <th>Schedule</th> --}}
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($selfpayforms as $form)
			<tr>
				<td>
					<button class="show-modal btn btn-warning" data-index="{{$form->INDEXID}}" data-tecode="{{$form->Te_Code}}" data-term="{{$form->Term}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button>
				<td>
				@if(empty($form->selfpay_approval)) None @else {{ $form->selfpay_approval }} @endif	
				</td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>{{ $form->Term }}</td>
				<td>{{ $form->L }}</td>
				<td>{{ $form->courses->Description }}</td>
				{{-- <td>{{ $form->schedule->name }}</td> --}}
				<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank">carte attachment</a> @endif</td>
				<td>@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank">payment attachment</a> @endif </td>
				<td>{{ $form->created_at}}</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
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
@endif

@stop

@section('java_script')
<script>
// Show a post
$(document).on('click', '.show-modal', function() {
    $('.modal-title').text('Show');
    $('#id_show').val($(this).data('id'));
    $('#title_show').val($(this).data('title'));
    var index = $(this).data('index');
    var tecode = $(this).data('tecode');
    var term = $(this).data('term');
    $('#showModal').modal('show');

    $.ajax({
        url: '{{ route('show-schedule-selfpay') }}',
        type: 'GET',
        data: {'index' : index, 'tecode' : tecode, 'term' : term,
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