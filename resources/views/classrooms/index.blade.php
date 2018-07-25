@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- icheck checkboxes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/yellow.css">

    <!-- toastr notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h1>Classes - Assign Rooms and Teachers</h1>
		</div>

		<div class="form-group">
        <form method="GET" action="{{ route('classrooms.index',['Term' => Request::input('Te_Term')]) }}">
          <label name="Te_Term" class="col-sm-4 control-label" style="margin: 5px 5px;">Term: </label>
            <select class="col-sm-8 form-control select2-filter" name="Te_Term" autocomplete="off" required="required" style="width: 100%">
                <option value="">--- Select Term ---</option>
                @foreach ($terms as $value)
                    <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
            </select>
            <div class="form-group col-sm-12 add-margin">           
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="/admin/classrooms/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
            </div>
        </form>
        </div>
		{{-- <div class="col-md-2">
			<a href="{{ route('classrooms.create') }}" class="btn btn-block btn-primary btn-h1-spacing">Create Classes</a>

		</div> --}}
		<div class="col-sm-12">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-10 class-sm-offset-2">
			<table class="table">
				<thead>
					<th>#</th>
					<th>Term</th>
					<th>Class Code</th>
					<th>Course Name</th>
					<th>Schedule</th>
					<th>Sections</th>
					<th>Operation</th>
				</thead>

				<tbody>
					@foreach($classrooms as $classroom)
						
						<tr  class="item{{$classroom->id}}">
							<th>{{ $classroom->id }}</th>
							<th>{{ $classroom->Te_Term }}</th>
							<th>{{ $classroom->Code }}</th>
							<td>{{ $classroom->course->Description }}</td>
							<td>
								@if(empty( $classroom->scheduler->name ))
								null
								@else 
								{{ $classroom->scheduler->name }}
								@endif
							</td>
							<td>
								<button class="show-modal btn btn-warning" data-id="{{$classroom->id}}" data-title="{{ $classroom->course->Description }} {{ $classroom->scheduler->name }}" data-csunique="{{ $classroom->cs_unique }}"><span class="glyphicon glyphicon-eye-open"></span> Show</button>
                            </td>
							<td>
								<button class="edit-modal btn btn-info" data-id="{{$classroom->id}}" data-title="{{ $classroom->course->Description }} {{ $classroom->scheduler->name }}" data-term="{{ $classroom->Te_Term }}" data-language="{{ $classroom->L }}" data-tecode="{{ $classroom->Te_Code_New }}" data-schedule="{{ $classroom->schedule_id }}" data-csunique="{{ $classroom->cs_unique }}"><span class="glyphicon glyphicon-edit"></span> Create</button>
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
			{{ $classrooms->links() }}		
		</div>
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
                                <input type="hidden" class="form-control" id="term" disabled>
                                <input type="hidden" class="form-control" id="Code" disabled>
                                <input type="hidden" class="form-control" id="L" disabled>
                                <input type="hidden" class="form-control" id="tecode" disabled>
                                <input type="hidden" class="form-control" id="schedule" disabled>
                                <input type="hidden" class="form-control" id="cs_unique" disabled>
                                <br>
                                <p class="errorTitle text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <button id="addSection" type="button" class="btn btn-success pull-left">Add Section</button>
                        </div>
                        <div id="section-accordion">
                            <div id="sectionCount" class="content-clone">
                                <div class="col-sm-12"><hr></div>
                                <h4><strong>Section # <input type="text" class="btn" id="sectionValue" name="sectionNo[]" value="" required="" disabled=""  /></strong></h4>
                                <div class="form-group class-section">
    								<div class="form-group col-sm-12">
    		                            <label class="control-label col-sm-2" for="teacher_id">Teacher:</label>
    		                            <div class="col-sm-10">
    		                                <select class="form-control select2" name="teacher_id[]" autocomplete="off" style="width: 100%;">
                                                <option value="">--- Select Teacher ---</option>
    		                                    @foreach ($teachers as $teacher)
    		                                    <option value="{{ $teacher->Tch_ID }}"> {{ $teacher->Tch_Name }}</option>
    		                                    @endforeach
    		                                </select>
                                            <br>
                                            <p class="errorTeacher text-center alert alert-danger hidden"></p>
    		                            </div>
    		                        </div>

    								<div class="days-rooms-section">
    									<label class="col-sm-12">Choose Days:</label>

    									<div class="form-group monday col-sm-12">
    					                    <div class="col-sm-12">
    					                        <div class="checkbox">
    					                            <label>
    					                                <input type="checkbox" name="Te_Mon" value="2" /> Monday
    					                                <br>
    					                            </label>
            					                    <div class="content-hide content-params">
            											<div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Mon_Room">Room:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Mon_Room[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($rooms as $room)
            					                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
            					                                    @endforeach
            					                                </select>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Mon_BTime">Begin Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Mon_BTime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($btimes as $id => $val)
            					                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
            					                                    @endforeach
            					                                </select>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Mon_ETime">End Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Mon_ETime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($etimes as $id => $val)
            					                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
            					                                    @endforeach
            					                                </select>
            					                            </div>
            											</div>
            					                    </div>
    					                        </div>
    					                    </div>
    			                        </div>

    			                        <div class="form-group tuesday col-sm-12">
    					                    <div class="col-sm-12">
    					                        <div class="checkbox">    
    					                            <label>
    					                                <input type="checkbox" name="Te_Tue" value="3" /> Tuesday
    					                                <br>
    					                            </label>
            										<div class="content-hide content-params">
            											<div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Tue_Room">Room:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Tue_Room[]" autocomplete="off" style="width: 100%;">
                                                                    <option></option>
            					                                    @foreach ($rooms as $room)
            					                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
            					                                    @endforeach
            					                                </select>
            					                                <p class="errorRoom text-center alert alert-danger hidden"></p>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Tue_BTime">Begin Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Tue_BTime[]" autocomplete="off" style="width: 100%;">
                                                                    <option></option>
            					                                    @foreach ($btimes as $id => $val)
            					                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
            					                                    @endforeach
            					                                </select>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Tue_ETime">End Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Tue_ETime[]" autocomplete="off" style="width: 100%;">
                                                                    <option></option>
            					                                    @foreach ($etimes as $id => $val)
            					                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
            					                                    @endforeach
            					                                </select>
            					                            </div>
            											</div>
            										</div>
                                                </div>
                                            </div>
    			                        </div>

    			                        <div class="form-group wednesday col-sm-12">
    			                        	<div class="col-sm-12">
    					                        <div class="checkbox">    
    					                            <label>
    					                                <input type="checkbox" name="Te_Wed" value="4" /> Wednesday
    					                                <br>
    					                            </label>
            										<div class="content-hide content-params">
            											<div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Wed_Room">Room:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Wed_Room[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($rooms as $room)
            					                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
            					                                    @endforeach
            					                                </select>
            					                                <p class="errorRoom text-center alert alert-danger hidden"></p>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Wed_BTime">Begin Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Wed_BTime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($btimes as $id => $val)
                                                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                                    @endforeach
            					                                </select>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Wed_ETime">End Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Wed_ETime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($etimes as $id => $val)
                                                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                                    @endforeach
            					                                </select>
            					                            </div>
            											</div>
            										</div>
                                                </div>
                                            </div>
    			                        </div>

    			                        <div class="form-group thursday col-sm-12">
    					                    <div class="col-sm-12">
    					                        <div class="checkbox"> 
    					                            <label>
    					                                <input type="checkbox" name="Te_Thu" value="5" /> Thursday
    					                                <br>
    					                            </label>
            										<div class="content-hide content-params">
            											<div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Thu_Room">Room:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Thu_Room[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($rooms as $room)
            					                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
            					                                    @endforeach
            					                                </select>
            					                                <p class="errorRoom text-center alert alert-danger hidden"></p>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Thu_BTime">Begin Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Thu_BTime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($btimes as $id => $val)
                                                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                                    @endforeach
            					                                </select>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Thu_ETime">End Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Thu_ETime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($etimes as $id => $val)
                                                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                                    @endforeach
            					                                </select>
            					                            </div>
            											</div>
            										</div>
                                                </div>
                                            </div>
    			                        </div>

    			                        <div class="form-group friday col-sm-12">
    					                    <div class="col-sm-12">
    					                        <div class="checkbox"> 
    					                            <label>
    					                                <input type="checkbox" name="Te_Fri" value="6" /> Friday
    					                                <br>
    					                            </label>
            										<div class="content-hide content-params">
            											<div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Fri_Room">Room:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Fri_Room[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($rooms as $room)
            					                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
            					                                    @endforeach
            					                                </select>
            					                                <p class="errorRoom text-center alert alert-danger hidden"></p>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Fri_BTime">Begin Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Fri_BTime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($btimes as $id => $val)
                                                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                                    @endforeach
            					                                </select>
            					                            </div>
            					                        </div>
            					                        <div class="btn-space col-sm-12">
            					                            <label class="control-label col-sm-4" for="Te_Fri_ETime">End Time:</label>
            					                            <div class="col-sm-8">
            					                                <select class="form-control " name="Te_Fri_ETime[]" autocomplete="off" style="width: 100%;">
            					                                    <option></option>
                                                                    @foreach ($etimes as $id => $val)
                                                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                                    @endforeach
            					                                </select>
            					                            </div>
            											</div>
            										</div>
                                                </div>
                                            </div>
    			                    	</div>
    			                    </div> 
    							</div> 
                            </div>
                            <div class="clone-paste-here"></div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
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
            $('.select2-filter').select2({placeholder: "Select Here",});
        });
    // Show a post
        $(document).on('click', '.show-modal', function() {
            $('.modal-title').text('Show');
            $('#id_show').val($(this).data('id'));
            $('#title_show').val($(this).data('title'));
            $('#content_show').val($(this).data('csunique'));
            $('#showModal').modal('show');

            $.ajax({
                url: '{{ route('show-section-ajax') }}',
                type: 'GET',
                data: {'cs_unique' : $('#content_show').val(),
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
    // Show parameters
        $("input[type='checkbox']").on('click', function() {
            $(this).parent().next(".content-params").toggle();
        });
	// Create (Edit) a classroom 
        $(document).on('click', '.edit-modal', function() {
            // reset values
        	$('.select2').val(null).trigger('change');
            $('.clone-paste-here').remove();
            $('.content-clone').after('<div class="clone-paste-here"></div>')
            // get values
            $('.modal-title').text('Assign Room/Teacher');
            $('#id_edit').val($(this).data('id'));
            $('#title_edit').val($(this).data('title'));
            $('#term').val($(this).data('term'));
            $('#L').val($(this).data('language'));
            $('#tecode').val($(this).data('tecode'));
            $('#schedule').val($(this).data('schedule'));
            $('#cs_unique').val($(this).data('csunique'));
            $('#Code').val(null);
            id = $('#id_edit').val();
            $('#editModal').modal('show');

            $.ajax({
                url: '{{ route('get-section-no-ajax') }}',
                type: 'GET',
                data: {'cs_unique' : $('#cs_unique').val(),
                },
            })
            .done(function(data) {
                console.log("success");
                console.log(data);
                $("#sectionValue").attr('value', data);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });     
        });

        // Clone section
        var incrementValue = 2;
        $("#addSection").on('click', function() {
            $("#sectionCount").clone(true).attr('id', 'sectionCount'+ incrementValue++).appendTo('.clone-paste-here')
            // insertAfter("[id^=sectionCount]:last");
            var sectionCountValue = +$("#sectionValue").val() + 1;
            $("#sectionValue").attr('value', sectionCountValue);
        });

        $('.modal-footer').on('click', '.edit', function() {
            var teacher = $("select[name='teacher_id[]']").map(function(){
                             return this.value; }).get();
            var section = $("input[name='sectionNo[]']").map(function(){
                             return this.value; }).get();

            var Te_Mon_Room = $("select[name='Te_Mon_Room[]']").map(function(){
                             return this.value; }).get();
            var Te_Mon_BTime = $("select[name='Te_Mon_BTime[]']").map(function(){
                             return this.value; }).get();
            var Te_Mon_ETime = $("select[name='Te_Mon_ETime[]']").map(function(){
                             return this.value; }).get();
                    
            var Te_Tue_Room = $("select[name='Te_Tue_Room[]']").map(function(){
                             return this.value; }).get();
            var Te_Tue_BTime = $("select[name='Te_Tue_BTime[]']").map(function(){
                             return this.value; }).get();
            var Te_Tue_ETime = $("select[name='Te_Tue_ETime[]']").map(function(){
                             return this.value; }).get();
                    
            var Te_Wed_Room = $("select[name='Te_Wed_Room[]']").map(function(){
                             return this.value; }).get();
            var Te_Wed_BTime = $("select[name='Te_Wed_BTime[]']").map(function(){
                             return this.value; }).get();
            var Te_Wed_ETime = $("select[name='Te_Wed_ETime[]']").map(function(){
                             return this.value; }).get();
                    
            var Te_Thu_Room = $("select[name='Te_Thu_Room[]']").map(function(){
                             return this.value; }).get();
            var Te_Thu_BTime = $("select[name='Te_Thu_BTime[]']").map(function(){
                             return this.value; }).get();
            var Te_Thu_ETime = $("select[name='Te_Thu_ETime[]']").map(function(){
                             return this.value; }).get();
                    
            var Te_Fri_Room = $("select[name='Te_Fri_Room[]']").map(function(){
                             return this.value; }).get();
            var Te_Fri_BTime = $("select[name='Te_Fri_BTime[]']").map(function(){
                             return this.value; }).get();
            var Te_Fri_ETime = $("select[name='Te_Fri_ETime[]']").map(function(){
                             return this.value; }).get();
                    



            $.ajax({
                type: 'POST',
                url: "{{ route('classrooms.store') }}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'title': $('#title_edit').val(),
                    'teacher_id[]' : teacher,
                    'term_id' : $('#term').val(),
                    'L' : $('#L').val(),
                    'Code' : $('#Code').val(),
                    'tecode' : $('#tecode').val(),
                    'schedule_id' : $('#schedule').val(),
                    'cs_unique' : $('#cs_unique').val(),
                    'sectionNo[]' : section,
                    'Te_Mon' : $("input[name='Te_Mon']").val(),
                    'Te_Mon_Room[]' : Te_Mon_Room,
                    'Te_Mon_BTime[]' : Te_Mon_BTime,
                    'Te_Mon_ETime[]' : Te_Mon_ETime,
                    'Te_Tue' : $("input[name='Te_Tue']").val(),
                    'Te_Tue_Room[]' : Te_Tue_Room,
                    'Te_Tue_BTime[]' : Te_Tue_BTime,
                    'Te_Tue_ETime[]' : Te_Tue_ETime,
                    'Te_Wed' : $("input[name='Te_Wed']").val(),
                    'Te_Wed_Room[]' : Te_Wed_Room,
                    'Te_Wed_BTime[]' : Te_Wed_BTime,
                    'Te_Wed_ETime[]' : Te_Wed_ETime,
                    'Te_Thu' : $("input[name='Te_Thu']").val(),
                    'Te_Thu_Room[]' : Te_Thu_Room,
                    'Te_Thu_BTime[]' : Te_Thu_BTime,
                    'Te_Thu_ETime[]' : Te_Thu_ETime,
                    'Te_Fri' : $("input[name='Te_Fri']").val(),
                    'Te_Fri_Room[]' : Te_Fri_Room,
                    'Te_Fri_BTime[]' : Te_Fri_BTime,
                    'Te_Fri_ETime[]' : Te_Fri_ETime,
                    
                },
                success: function(data) {
                	console.log(data)
                    $('.errorTitle').addClass('hidden');
                    $('.errorTeacher').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);
            
                        if (data.errors.Code) {
                            $('.errorTitle').removeClass('hidden');
                            $('.errorTitle').text(data.errors.Code);
                        }
                        if (data.errors.Tch_ID) {
                            $('.errorTeacher').removeClass('hidden');
                            $('.errorTeacher').text(data.errors.Tch_ID);
                        }
                    } else {
                        toastr.success('Successfully created!', 'Success Alert', {timeOut: 5000});
                        location.reload(true);
                        // $('.item' + data.id).replaceWith("<tr class='item" + data.id + "'><td>" + data.id + "</td><td>" + data.title + "</td><td>" + data.content + "</td><td class='text-center'><input type='checkbox' class='edit_published' data-id='" + data.id + "'></td><td>Right now</td><td><button class='show-modal btn btn-success' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-eye-open'></span> Show</button> <button class='edit-modal btn btn-info' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-title='" + data.title + "' data-content='" + data.content + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");

                        // if (data.is_published) {
                        //     $('.edit_published').prop('checked', true);
                        //     $('.edit_published').closest('tr').addClass('warning');
                        // }
                        // $('.edit_published').iCheck({
                        //     checkboxClass: 'icheckbox_square-yellow',
                        //     radioClass: 'iradio_square-yellow',
                        //     increaseArea: '20%'
                        // });
                        // $('.edit_published').on('ifToggled', function(event) {
                        //     $(this).closest('tr').toggleClass('warning');
                        // });
                        // $('.edit_published').on('ifChanged', function(event){
                        //     id = $(this).data('id');
                        //     $.ajax({
                        //         type: 'POST',
                        //         url: "",
                        //         data: {
                        //             '_token': $('input[name=_token]').val(),
                        //             'id': id
                        //         },
                        //         success: function(data) {
                        //             // empty
                        //         },
                        //     });
                        // });
                    }
                }
            });
        });	
</script>


@stop