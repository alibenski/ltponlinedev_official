@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <!-- icheck checkboxes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/yellow.css">
    <!-- toastr notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop
@section('content')
@include('admin.partials._termSessionMsg')
<div class="container">
	<div class="row">
        <div class="alert bg-gray col-sm-12">
            <h4 class="text-center"><i class="fa fa-pencil-square-o"></i><strong> Classes - Assign Rooms and Teachers</strong></h4>
        </div>
	</div>
    <div class="row">
        @if(Session::has('Term'))
        <div class="box box-default">
            <div class="box-body">
                <a href="{{ route('index-calendar')}}" class="filter-reset btn btn-default btn-space"><i class="fa fa-calendar"></i></a>
                <div class="col-sm-12">
                    <form method="GET" action="{{ route('classrooms.index',['Term' => Request::input('Te_Term')]) }}">
                        <input type="hidden" name="term_id" value="{{ Session::get('Term') }}">
                            <div class="form-group">
                                <label for="L" class="control-label"> Language:</label>
                                <div class="col-sm-12">
                                @foreach ($languages as $id => $name)
                                <div class="col-sm-4">
                                    <div class="input-group"> 
                                    <span class="input-group-addon">       
                                        <input type="radio" name="L" value="{{ $id }}" >                 
                                    </span>
                                        <label type="text" class="form-control">{{ $name }}</label>
                                    </div>
                                </div>
                                @endforeach 
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="Te_Code_New" class="control-label"> Course: </label>
                                <div class="form-group">
                                <div class="dropdown">
                                    <select class="col-md-10 form-control select2-basic-single" style="width: 100%;" name="Te_Code_New" autocomplete="off">
                                        <option value="">--- Select ---</option>
                                    </select>
                                </div>
                                </div>
                            </div>

                        <div class="form-group col-sm-12 add-margin">           
                            <button type="submit" class="btn btn-success">Submit</button>
                            <a href="/admin/classrooms/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
	</div> 
    {{-- End of Filter Row --}}
    
	<div class="row">
		<div class="col-sm-12">
            <div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
		    <div class="filtered-table table-responsive">
			<table id="sampol" class="table display" style="width:100%">
				<thead>
					<th>#</th>
					<th>Term</th>
					{{-- <th>Class Code</th> --}}
					<th>Course Name</th>
					<th>Schedule</th>
                    <th>Format</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Teacher</th>
                    <th>Room</th>
					<th>Show Sections</th>
					<th>Add Sections</th>
				</thead>

				<tbody>
					@foreach($classrooms as $classroom)
						
						<tr  class="item{{$classroom->id}}">
							<th>{{ $classroom->id }}</th>
							<th>{{ $classroom->Te_Term }}</th>
							{{-- <th>{{ $classroom->Code }}</th> --}}
							<td>
                                @if($classroom->course->Description)
                                <p>{{ $classroom->course->Description }}</p>
                                @endif
                                @if($classroom->course->FDescription)
                                <p>{{ $classroom->course->FDescription }}</p>
                                @endif
                            </td>
							<td>
								@if(empty( $classroom->scheduler->name ))
								null
								@else 
								{{ $classroom->scheduler->name }}
								@endif
							</td>
                            <td>
                                @if(empty( $classroom->courseformat->format_name_en ))
                                null
                                @else 
                                {{ $classroom->courseformat->format_name_en }}
                                @endif
                            </td>
                            <td>
                                @if(empty( $classroom->courseduration->duration_name_en ))
                                null
                                @else 
                                {{ $classroom->courseduration->duration_name_en }}
                                @endif
                            </td>
                            <td>
                                @if(empty( $classroom->prices->price ))
                                null
                                @else 
                                {{ $classroom->prices->price }}
                                @endif
                            </td>
                            <td>
                                @if ($classroom->classroom)
                                    @foreach ($classroom->classroom as $item)
                                        <p>
                                        @if ($item->Tch_ID)
                                        <span class="badge badge-primary">{{$item->sectionNo}}</span> {{$item->teachers->Tch_Name}}
                                        @else
                                        <span class="badge badge-primary">{{$item->sectionNo}}</span> no teacher assigned
                                        @endif
                                        </p>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if ($classroom->classroom)
                                    @foreach ($classroom->classroom as $item)
                                        <p>
                                            <div>Section <span class="badge badge-primary">{{$item->sectionNo}}</span></div>
                                            @if ($item->Te_Mon_Room)
                                            <span class="label label-primary">Mon</span> {{$item->roomsMon->Rl_Room}}
                                            @endif
                                            @if($item->Te_Tue_Room)
                                            <span class="label label-primary">Tue</span> {{$item->roomsTue->Rl_Room}}
                                            @endif
                                            @if($item->Te_Wed_Room)
                                            <span class="label label-primary">Wed</span> {{$item->roomsWed->Rl_Room}}
                                            @endif
                                            @if($item->Te_Thu_Room)
                                            <span class="label label-primary">Thu</span> {{$item->roomsThu->Rl_Room}}
                                            @endif
                                            @if($item->Te_Fri_Room)
                                            <span class="label label-primary">Fri</span> {{$item->roomsFri->Rl_Room}}
                                            @endif
                                            @if($item->Te_Sat_Room)
                                            <span class="label label-primary">Sat</span> {{$item->roomsSat->Rl_Room}}
                                            @endif
                                        </p>
                                    @endforeach
                                @endif
                            </td>
							<td align="center">
								<button class="show-modal btn btn-warning" data-id="{{$classroom->id}}" data-title="{{ $classroom->course->Description }} {{ $classroom->scheduler->name }}" data-csunique="{{ $classroom->cs_unique }}"><i class="fa fa-eye"></i></button>
                            </td>
							<td align="center">
								<button class="add-section edit-modal btn btn-info hidden" data-id="{{$classroom->id}}" data-title="{{ $classroom->course->Description }} {{ $classroom->scheduler->name }}" data-term="{{ $classroom->Te_Term }}" data-language="{{ $classroom->L }}" data-tecode="{{ $classroom->Te_Code_New }}" data-schedule="{{ $classroom->schedule_id }}" data-csunique="{{ $classroom->cs_unique }}"><i class="fa fa-plus"></i></button>
							</td>
						</tr>
					@endforeach

				</tbody>
			</table>
		    </div>		
		</div>
	</div>
    <!-- Modal form to show a post -->
    <div id="showModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
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
                            <label class="control-label col-sm-2" for="content">Class Info:</label>
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
    @endif
</div>
@endsection

@section('java_script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<!-- toastr notifications -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!-- icheck checkboxes -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

<script>
$(document).ready(function() {
    var Term = "{{ Session::get('Term') }}";
    var token = $("input[name='_token']").val();
    console.log(Term)
    $.ajax({
        url: '{{ route('ajax-check-batch-has-ran') }}',
        type: 'GET',
        data: {Term:Term,_token: token},
    })
    .done(function(data) {
        if (!jQuery.isEmptyObject( data )) {
            $("button.add-section").removeClass('hidden');
        }

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete check if batch has ran");
    });

    $('#sampol').DataTable({
        "fixedHeader": true,
        "deferRender": true,
        "dom": 'B<"clear">lfrtip',
        "buttons": [
                'copy', 'csv', 'excel', 'pdf'
            ],
        "oLanguage": {
            "sSearch": "Search Filter:"
            },
        "columnDefs": [
            { "width": "20%", "targets": 7 },
            { "width": "25%", "targets": 8 }
        ]
    });
    $(".preloader2").fadeOut(600);

});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });
});
</script>
<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("select[name='Te_Code_New']").html('');
            $("select[name='Te_Code_New']").html(data.options);
          }
      });
  }); 
</script>

<script>
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


            $('.monday').removeClass('hidden');
            $('.tuesday').removeClass('hidden');
            $('.wednesday').removeClass('hidden');
            $('.thursday').removeClass('hidden');
            $('.friday').removeClass('hidden');

            $.ajax({
                url: '{{ route('get-section-param-ajax') }}',
                type: 'GET',
                data: {'cs_unique' : $('#cs_unique').val(),
                },
            })
            .done(function(data) {
                console.log("get classroom param success");
                console.log(data.options);
                        if (!data.options.Te_Mon_BTime) {
                            $('.monday').addClass('hidden');
                        }
                        if (!data.options.Te_Tue_BTime) {
                            $('.tuesday').addClass('hidden');
                        }
                        if (!data.options.Te_Wed_BTime) {
                            $('.wednesday').addClass('hidden');
                        }
                        if (!data.options.Te_Thu_BTime) {
                            $('.thursday').addClass('hidden');
                        }
                        if (!data.options.Te_Fri_BTime) {
                            $('.friday').addClass('hidden');
                        }
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
                    }
                }
            });
        });	
</script>


@stop