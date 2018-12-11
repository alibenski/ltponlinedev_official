@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content')
<form class="form-horizontal" role="form" method="POST" action="{{ route('classrooms.update', $classroom->id) }}">{{ csrf_field() }}
    <div class="form-group">
        <div class="col-sm-12">
        <label class="control-label" for="id">ID:</label>
            <input type="text" class="form-control class-id" value="{{$classroom->id}}" disabled>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
        <label class="control-label" for="title">Title:</label>
            <input type="text" class="form-control" value="{{ $classroom->course->Description }} {{ $classroom->scheduler->name }}" disabled>
        </div>
        <div class="col-sm-6 add-margin">
			<div class="panel panel-primary">
				<div class="panel-heading"><strong>Existing Values</strong></div>
		        <div class="panel-body existing-content">
		            <p>Teacher: @if($classroom->Tch_ID) <strong>{{ $classroom->teachers->Tch_Name }}</strong> @else <span class="label label-danger">none assigned</span> @endif</p>
						@if(!empty($classroom->Te_Mon_Room))
						<p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
						<p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
						<p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>
						@endif
						@if(!empty($classroom->Te_Tue_Room))
						<p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
						<p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
						<p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>
						@endif
						@if(!empty($classroom->Te_Wed_Room))
						<p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
						<p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
						<p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>
						@endif
						@if(!empty($classroom->Te_Thu_Room))
						<p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
						<p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
						<p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>
						@endif
						@if(!empty($classroom->Te_Fri_Room))
						<p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
						<p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
						<p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>
						@endif
		        </div>
		    </div>
        </div>
    </div>

    <div id="section-accordion">
        <div id="sectionCount" class="content-clone">
            <div class="col-sm-12"><hr></div>
            <h4><strong>Section # <input type="text" class="btn" id="sectionValue" name="sectionNo" value="{{$classroom->sectionNo}}" required="" disabled=""  /></strong></h4>
            <div class="form-group class-section">
				<div class="form-group col-sm-12">
                    <label class="control-label col-sm-2" for="Tch_ID">Teacher:</label>
                    <div class="col-sm-10">
                        <select class="form-control select2" name="Tch_ID" autocomplete="off" style="width: 100%;">
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
					<label class="col-sm-12">Assign Room and Time:</label>

					@if(!empty($classroom->Te_Mon_BTime))
					<div class="form-group monday col-sm-12">
	                    <div class="col-sm-12">
	                        <div class="checkbox">
	                            <label>
	                                <input type="checkbox" name="Te_Mon" value="2" /> Monday
	                                <br>
	                            </label>
			                    <div class="content-hide content-params">
									<div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Mon_Room">Room:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Mon_Room" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($rooms as $room)
			                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
			                                    @endforeach
			                                </select>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Mon_BTime">Begin Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Mon_BTime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($btimes as $id => $val)
			                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
			                                    @endforeach
			                                </select>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Mon_ETime">End Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Mon_ETime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($etimes as $id => $val)
			                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
			                                    @endforeach
			                                </select>
			                            </div>
									</div>
									
									<div class="form-group btn-space pull-right">
										<button class="delete-day-param btn btn-danger" value="2">
                                        <span class="glyphicon glyphicon-trash"></span> Delete</button>
									</div>

			                    </div>
	                        </div>
	                    </div>
                    </div>
					@endif

					@if(!empty($classroom->Te_Tue_BTime))
                    <div class="form-group tuesday col-sm-12">
	                    <div class="col-sm-12">
	                        <div class="checkbox">    
	                            <label>
	                                <input type="checkbox" name="Te_Tue" value="3" /> Tuesday
	                                <br>
	                            </label>
								<div class="content-hide content-params">
									<div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Tue_Room">Room:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Tue_Room" autocomplete="off" style="width: 100%;">
                                                <option></option>
			                                    @foreach ($rooms as $room)
			                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
			                                    @endforeach
			                                </select>
			                                <p class="errorRoom text-center alert alert-danger hidden"></p>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Tue_BTime">Begin Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Tue_BTime" autocomplete="off" style="width: 100%;">
                                                <option></option>
			                                    @foreach ($btimes as $id => $val)
			                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
			                                    @endforeach
			                                </select>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Tue_ETime">End Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Tue_ETime" autocomplete="off" style="width: 100%;">
                                                <option></option>
			                                    @foreach ($etimes as $id => $val)
			                                    <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
			                                    @endforeach
			                                </select>
			                            </div>
									</div>
									<div class="form-group btn-space pull-right">
										<button class="delete-day-param btn btn-danger" value="3">
                                        <span class="glyphicon glyphicon-trash"></span> Delete</button>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
					@endif
					
					@if(!empty($classroom->Te_Wed_BTime))
                    <div class="form-group wednesday col-sm-12">
                    	<div class="col-sm-12">
	                        <div class="checkbox">    
	                            <label>
	                                <input type="checkbox" name="Te_Wed" value="4" /> Wednesday
	                                <br>
	                            </label>
								<div class="content-hide content-params">
									<div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Wed_Room">Room:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Wed_Room" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($rooms as $room)
			                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
			                                    @endforeach
			                                </select>
			                                <p class="errorRoom text-center alert alert-danger hidden"></p>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Wed_BTime">Begin Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Wed_BTime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($btimes as $id => $val)
                                                <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                @endforeach
			                                </select>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Wed_ETime">End Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Wed_ETime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($etimes as $id => $val)
                                                <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                @endforeach
			                                </select>
			                            </div>
									</div>
									<div class="form-group btn-space pull-right">
										<button class="delete-day-param btn btn-danger" value="4">
                                        <span class="glyphicon glyphicon-trash"></span> Delete</button>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($classroom->Te_Thu_BTime))
                    <div class="form-group thursday col-sm-12">
	                    <div class="col-sm-12">
	                        <div class="checkbox"> 
	                            <label>
	                                <input type="checkbox" name="Te_Thu" value="5" /> Thursday
	                                <br>
	                            </label>
								<div class="content-hide content-params">
									<div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Thu_Room">Room:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Thu_Room" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($rooms as $room)
			                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
			                                    @endforeach
			                                </select>
			                                <p class="errorRoom text-center alert alert-danger hidden"></p>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Thu_BTime">Begin Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Thu_BTime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($btimes as $id => $val)
                                                <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                @endforeach
			                                </select>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Thu_ETime">End Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Thu_ETime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($etimes as $id => $val)
                                                <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                @endforeach
			                                </select>
			                            </div>
									</div>
									<div class="form-group btn-space pull-right">
										<button class="delete-day-param btn btn-danger" value="5">
                                        <span class="glyphicon glyphicon-trash"></span> Delete</button>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($classroom->Te_Fri_BTime))
                    <div class="form-group friday col-sm-12">
	                    <div class="col-sm-12">
	                        <div class="checkbox"> 
	                            <label>
	                                <input type="checkbox" name="Te_Fri" value="6" /> Friday
	                                <br>
	                            </label>
								<div class="content-hide content-params">
									<div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Fri_Room">Room:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Fri_Room" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($rooms as $room)
			                                    <option value="{{ $room->id}}"> {{ $room->Rl_Room }} ({{ $room->Rl_Location }})</option>
			                                    @endforeach
			                                </select>
			                                <p class="errorRoom text-center alert alert-danger hidden"></p>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Fri_BTime">Begin Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Fri_BTime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($btimes as $id => $val)
                                                <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                @endforeach
			                                </select>
			                            </div>
			                        </div>
			                        <div class="btn-space col-sm-12">
			                            <label class="control-label col-sm-2" for="Te_Fri_ETime">End Time:</label>
			                            <div class="col-sm-10">
			                                <select class="form-control " name="Te_Fri_ETime" autocomplete="off" style="width: 100%;">
			                                    <option></option>
                                                @foreach ($etimes as $id => $val)
                                                <option value="{{ $id}}"> {{ date('h:i:sa', strtotime($val)) }}</option>
                                                @endforeach
			                                </select>
			                            </div>
									</div>
									<div class="form-group btn-space pull-right">
										<button class="delete-day-param btn btn-danger" value="6">
                                        <span class="glyphicon glyphicon-trash"></span> Delete</button>
									</div>
								</div>
                            </div>
                        </div>
                	</div>
                	@endif

                </div> 
			</div> 
        </div>
    </div>
	<div class="modal-footer">
	    <button type="submit" class="btn btn-success"><span class='glyphicon glyphicon-check'></span> Save</button>
	    <input type="hidden" name="_token" value="{{ Session::token() }}">
	    {{ method_field('PUT') }}
	    <a href="{{ route('classrooms.index') }}" class="btn btn-danger">
            <span class='glyphicon glyphicon-remove'></span> Back
        </a>
	</div>
</form>
@stop
@section('java_script')
<script>
	// Show parameters
        $("input[type='checkbox']").on('click', function() {
            $(this).parent().next(".content-params").toggle();
        });
    // Delete day parameters
    	$('.delete-day-param').on('click', function(event) {
    		event.preventDefault();
    		var dayID = $(this).val();

    		$.ajax({
    			url: '{{ route('delete-day-param-ajax') }}',
    			type: 'POST',
    			data: {
    				'_token': $('input[name=_token]').val(),
    				'dayID' : dayID,
    				'id' : $('.class-id').val(),
    			},
    		})
    		.done(function(data) {
    			console.log("success");
    			console.log(data);
    			location.reload(true);
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