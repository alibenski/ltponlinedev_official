@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <!-- icheck checkboxes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/yellow.css">

    <!-- toastr notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>All Course + Schedule</h1>
		</div>
		
		<div class="col-md-2">
			<a href="{{ route('classrooms.create') }}" class="btn btn-lg btn-block btn-primary btn-h1-spacing">Create Classes</a>

		</div>
		<div class="col-md-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 class-md-offset-2">
			<table class="table">
				<thead>
					<th>Class Code</th>
					<th>Course Name</th>
					<th>Schedule</th>
					<th>Room</th>
					<th>Teacher</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($classrooms as $classroom)
						
						<tr  class="item{{$classroom->id}}">
							<th>{{ $classroom->Code }}</th>
							<td>{{ $classroom->course->Description }}</td>
							<td>
								@if(empty( $classroom->scheduler->name ))
								null
								@else 
								{{ $classroom->scheduler->name }}
								@endif
							</td>
							<td>@if(empty( $classroom->room_id ))
								<span class="label label-danger">none assigned</span>
								@else 
								{{ $classroom->room_id }}
								@endif</td>
							<td>@if(empty( $classroom->Tch_ID ))
								<span class="label label-danger">none assigned</span>
								@else 
								{{ $classroom->Tch_ID }}
								@endif</td>
							<td>
								<button class="edit-modal btn btn-info" data-id="{{$classroom->id}}" data-title="{{ $classroom->course->Description }} {{ $classroom->scheduler->name }}" data-content=""><span class="glyphicon glyphicon-edit"></span> Edit</button>
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $classrooms->links() }}		
		</div>
	</div>
	<!-- Modal form to edit a form -->
    <div id="editModal" class="modal fade" role="dialog">
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
                                <input type="text" class="form-control" id="id_edit" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="title">Title:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title_edit" disabled>
                                <p class="errorTitle text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
							
						<div class="form-group">
                            <label class="control-label col-sm-2" for="room_id">Room:</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="room_id" autocomplete="off" style="width: 100%;">
                                    @foreach ($rooms as $room)
                                    <option></option>
                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
                                    @endforeach
                                </select>
                                <p class="errorRoom text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="teacher_id">Teacher:</label>
                            <div class="col-sm-10">
                                <select class="form-control select2" name="teacher_id" autocomplete="off" style="width: 100%;">
                                    @foreach ($teachers as $teacher)
                                    <option></option>
                                    <option value="{{ $teacher->Tch_ID }}"> {{ $teacher->Tch_Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Edit
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<!-- toastr notifications -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!-- icheck checkboxes -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

<script>
	$(document).ready(function() {
	    $('.select2').select2({
	    placeholder: "Select Here",
	    });
	});
	// Edit a post
        $(document).on('click', '.edit-modal', function() {
        	$('.select2').val(null).trigger('change');
            $('.modal-title').text('Assign Room/Teacher');
            $('#id_edit').val($(this).data('id'));
            $('#title_edit').val($(this).data('title'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            $.ajax({
                type: 'PUT',
                url: 'classrooms/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'title': $('#title_edit').val(),
                    'room_id' : $("select[name='room_id']").val(),
                    'teacher_id' : $("select[name='teacher_id']").val(),
                    // 'content': $('#content_edit').val()
                },
                success: function(data) {
                	console.log(data)
                    $('.errorTitle').addClass('hidden');
                    $('.errorRoom').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.title) {
                            $('.errorTitle').removeClass('hidden');
                            $('.errorTitle').text(data.errors.title);
                        }
                        if (data.errors.room_id) {
                            $('.errorRoom').removeClass('hidden');
                            $('.errorRoom').text(data.errors.room_id);
                        }
                    } else {
                        toastr.success('Successfully updated class!', 'Success Alert', {timeOut: 5000});
                        $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.title + "</td><td>" + data.content + "</td><td class='text-center'><input type='checkbox' class='edit_published' data-id='" + data.id + "'></td><td>Right now</td><td><button class='show-modal btn btn-success' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-eye-open'></span> Show</button> <button class='edit-modal btn btn-info' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");

                        if (data.is_published) {
                            $('.edit_published').prop('checked', true);
                            $('.edit_published').closest('tr').addClass('warning');
                        }
                        $('.edit_published').iCheck({
                            checkboxClass: 'icheckbox_square-yellow',
                            radioClass: 'iradio_square-yellow',
                            increaseArea: '20%'
                        });
                        $('.edit_published').on('ifToggled', function(event) {
                            $(this).closest('tr').toggleClass('warning');
                        });
                        $('.edit_published').on('ifChanged', function(event){
                            id = $(this).data('id');
                            $.ajax({
                                type: 'POST',
                                url: "",
                                data: {
                                    '_token': $('input[name=_token]').val(),
                                    'id': id
                                },
                                success: function(data) {
                                    // empty
                                },
                            });
                        });
                    }
                }
            });
        });	
</script>


@stop