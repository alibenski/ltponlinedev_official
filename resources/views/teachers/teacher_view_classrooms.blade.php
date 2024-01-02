@extends('teachers.teacher_template')
@section('customcss')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    body.modal-open {
        overflow: hidden;
    }
    .close {
        color: #fff; 
        opacity: 1;
    }
    </style>
@stop
@section('content')

<div class="row">
	<div class="col-md-9">
        @foreach($assigned_classes as $classroom)
		<div class="col-md-4">
			<div class="box box-success">
				<div class="box-header with-border">
                    @if ($classroom->course)
					<h3>{{ $classroom->course->Description }}</h3>
                    @elseif ($classroom->course_old) 
                    <h3>{{ $classroom->course_old->Description }}</h3>
                    @endif
					<small>{{ $classroom->terms->Comments }} {{ date('Y', strtotime($classroom->terms->Term_Begin)) }}</small>
				</div>
				<div class="box-body no-padding">
					<div class="col-md-12">
						<p>Teacher: @if(empty($classroom->teachers)) 
							@else <span><strong>{{ $classroom->teachers->Tch_Firstname }} {{ $classroom->teachers->Tch_Lastname }}
							</strong></span>
							@endif
						</p>
						

						@if(!empty($classroom->Te_Mon_Room))

                        <p>Monday Room: 
                            <strong>
                                @if ($classroom->roomsMon)
                                {{ $classroom->roomsMon->Rl_Room }}
                                @else
                                {{ $classroom->Te_Mon_Room }}
                                @endif
                            </strong>
                        </p>
						<p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
						<p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>

						@endif

						@if(!empty($classroom->Te_Tue_Room))

						<p>Tuesday Room: 
                            <strong>
                                @if ($classroom->roomsTue)
                                {{ $classroom->roomsTue->Rl_Room }}
                                @else
                                {{ $classroom->Te_Tue_Room }} 
                                @endif
                            </strong>
                        </p>
						<p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
						<p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>

						@endif

						@if(!empty($classroom->Te_Wed_Room))

						<p>Wednesday Room: 
                            <strong>
                                @if ($classroom->roomsWed)
                                {{ $classroom->roomsWed->Rl_Room }}
                                @else
                                {{ $classroom->Te_Wed_Room }} 
                                @endif
                            </strong>
                        </p>
						<p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
						<p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>

						@endif

						@if(!empty($classroom->Te_Thu_Room))

						<p>Thursday Room: 
                            <strong>
                                @if ($classroom->roomsThu)
                                {{ $classroom->roomsThu->Rl_Room }}
                                @else
                                {{ $classroom->Te_Thu_Room }} 
                                @endif
                            </strong>
                        </p>
						<p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
						<p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>

						@endif

						@if(!empty($classroom->Te_Fri_Room))

						<p>Friday Room: 
                            <strong>
                                @if ($classroom->roomsFri)
                                {{ $classroom->roomsFri->Rl_Room }}
                                @else
                                {{ $classroom->Te_Fri_Room }}
                                @endif
                            </strong>
                        </p>
						<p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
						<p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>

						@endif		

						@if(!empty($classroom->Te_Sat_Room))

						<p>Saturday Room: 
                            <strong>
                                @if ($classroom->roomsSat)
                                {{ $classroom->roomsSat->Rl_Room }}
                                @else
                                {{ $classroom->Te_Sat_Room }}
                                @endif
                            </strong>
                        </p>
						<p>Saturday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Sat_BTime ))}}</strong></p>
						<p>Saturday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Sat_ETime)) }}</strong></p>

						@endif		
						
					</div>
				</div>
				<div class="box-footer">
						
						<button id="showStudentsBtn" value="{{ $classroom->Code}}" class="btn btn-default btn-space">Show Students</button>
						{{-- <button id="manageAttendanceBtn" value="{{ $classroom->Code}}" class="btn btn-default">Manage Attendance</button> --}}
						<a href="{{ route('teacher-select-week', $classroom->Code) }}" class="btn btn-default btn-space">Manage Attendance</a>
						<button id="enterResultsBtn" value="{{ $classroom->Code}}" class="btn btn-default btn-space">Enter Results</button>
                        <button id="showStudentEmailsBtn" value="{{ $classroom->Code}}" class="btn btn-default btn-space">Show Emails for Moodle</button>
						{{-- <form action="{{ route('teacher-manage-attendance') }}" method="GET">
							<button type="submit" class="btn btn-default btn-space">Manage Attendance</button>
							<input type="hidden" value="{{ $classroom->Code}}" name="Code">
							<input type="hidden" name="_token" value="{{ Session::token() }}">
						</form> --}}
				</div>
			</div>
		</div>	
        @endforeach
    </div>
    
    <div class="col-md-3 col-xs-6">
        <div id="" class="small-box bg-red" data-teacher="0">
            @foreach ($assigned_classes->groupBy('Te_Code_New') as $element)
            <div class="inner">
                <input type="hidden" name="Te_Code" value="{{$element->first()->Te_Code_New}}" />
                <h3 class="count-waitlist-{{$element->first()->Te_Code_New}}">--</h3>
                <h4>{{ $element->first()->course->Description }}</h4>                    
            </div>
            @endforeach
            <div class="icon">
            <i class="ion ion-android-hand"></i>
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
    <div class="col-md-12">
        <div id="" class="box box-danger" data-teacher="0">
            <div class="box-header with-border">
                    Waitlist Information
				</div>
				<div class="box-body no-padding">
        @foreach ($assigned_classes->groupBy('Te_Code_New') as $element)
        <div class="col-md-3">
            <div class="inner">
            <input type="hidden" name="Te_Code" value="{{$element->first()->Te_Code_New}}" />
            <h3 class="count-waitlist-{{$element->first()->Te_Code_New}}">--</h3>
            <h4>{{ $element->first()->course->Description }}</h4>        
            </div>
        </div>
        @endforeach
                </div>
        </div>
</div> --}}
<div class="row">
	<div class="col-md-12">
		<div class="students-here">
			
		</div>
	</div>
</div>

@stop

@section('java_script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var token = $("input[name='_token']").val();
    var arrTeCode = [];
    $("input[name='Te_Code']").each(function() {
        var Te_Code = $(this).val();
        arrTeCode.push(Te_Code); //insert values to array per iteration
    });
    console.log(arrTeCode)

    if (arrTeCode.length > 0) {

        $.ajax({
            url: '{{ route('waitListOneListCount') }}',
            type: 'GET',
            data: {arrTeCode: arrTeCode, _token: token},
        })
        .done(function(data) {
            console.log(data);
            if (data.status == "fail") {
                alert(data.message);
            }
            $.each(data, function(x, y) {
                $("input[name='Te_Code']").each(function() {
                    if ($(this).val() == x) {
                        $('h3.count-waitlist-'+x).html(y+' Waitlisted')
                    }
                });
            });
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    }
});

$("button[id='manageAttendanceBtn']").click(function(){
  var Code = $(this).val();
  var token = $("input[name='_token']").val();

  $("button[id='manageAttendanceBtn'][value='"+Code+"']").addClass('btn-success');
  $("button[id='manageAttendanceBtn'][value='"+Code+"']").removeClass('btn-default');
  $("button").not("button[id='manageAttendanceBtn'][value='"+Code+"']").addClass('btn-default');
  $("button").not("button[id='manageAttendanceBtn'][value='"+Code+"']").removeClass('btn-success');


  $.ajax({
      url: "", 
      method: 'GET',
      data: {Code:Code, _token:token},
      success: function(data, status) {
        // console.log(data)
        $(".students-here").html(data);
        $(".students-here").html(data.options);
      }
  });
}); 

$("button[id='showStudentsBtn']").click(function(){
  var Code = $(this).val();
  var token = $("input[name='_token']").val();

  $("button[id='showStudentsBtn'][value='"+Code+"']").addClass('btn-success');
  $("button[id='showStudentsBtn'][value='"+Code+"']").removeClass('btn-default');
  $("button").not("button[id='showStudentsBtn'][value='"+Code+"']").addClass('btn-default');
  $("button").not("button[id='showStudentsBtn'][value='"+Code+"']").removeClass('btn-success');


  $.ajax({
      url: "{{ route('teacher-show-students') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
  })
  .done(function(data) {
  		// console.log(data)
        $(".students-here").html(data);
        $(".students-here").html(data.options);
        
        if (!$.isArray(data)) {
            alert("An error occured while loading Show Students Page. Click OK to reload.");
            window.location.reload();
        }
  })
  .fail(function(data) {
      console.log("error");
      alert("An error occured while loading Show Students Page. Click OK to reload.");
      window.location.reload();
  })
  .always(function(data) {
      console.log("complete show students");
  });
}); 

$("button[id='showStudentEmailsBtn']").click(function(){
  var Code = $(this).val();
  var token = $("input[name='_token']").val();

  $("button[id='showStudentEmailsBtn'][value='"+Code+"']").addClass('btn-success');
  $("button[id='showStudentEmailsBtn'][value='"+Code+"']").removeClass('btn-default');
  $("button").not("button[id='showStudentEmailsBtn'][value='"+Code+"']").addClass('btn-default');
  $("button").not("button[id='showStudentEmailsBtn'][value='"+Code+"']").removeClass('btn-success');


  $.ajax({
      url: "{{ route('teacher-show-student-emails-only') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
  })
  .done(function(data) {
  		// console.log(data)
        $(".students-here").html(data);
        $(".students-here").html(data.options);
        
        if (!$.isArray(data)) {
            alert("An error occured while loading Show Students Page. Click OK to reload.");
            window.location.reload();
        }
  })
  .fail(function(data) {
      console.log("error");
      alert("An error occured while loading Show Students Page. Click OK to reload.");
      window.location.reload();
  })
  .always(function(data) {
      console.log("complete show students");
  });
});

$("button[id='enterResultsBtn']").click(function(){
  var Code = $(this).val();
  var token = $("input[name='_token']").val();

  $("button[id='enterResultsBtn'][value='"+Code+"']").addClass('btn-success').attr('disabled', true);
  $("button[id='enterResultsBtn'][value='"+Code+"']").removeClass('btn-default');
  $("button").not("button[id='enterResultsBtn'][value='"+Code+"']").addClass('btn-default');
  $("button").not("button[id='enterResultsBtn'][value='"+Code+"']").removeClass('btn-success');


  $.ajax({
      url: "{{ route('teacher-enter-results') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
  })
  .done(function(data) {
        $(".students-here").html(data);
        $(".students-here").html(data.options);
        setTimeout(function() {
            $("button[id='enterResultsBtn'][value='"+Code+"']").removeAttr('disabled');
        }, 2000);
  		console.log("loading students after click Enter Results button")
        
        if (!$.isArray(data)) {
            alert("An error occured while loading Enter Results Page. Click OK to reload.");
            window.location.reload();
        }
  })
  .fail(function(data) {
      console.log("error");
      alert("An error occured while loading Enter Results Page. Click OK to reload.");
      window.location.reload();
  })
  .always(function(data) {
      console.log("complete load enter results view");
  });
}); 
</script>
@stop