@extends('teachers.teacher_template')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<h3><i class="fa fa-binoculars"></i> Viewing All {{Auth::user()->teachers->languages->name}} Classes</h3>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
		  <thead>
		      <tr>
		          <th>Class</th>
		          <th>Term</th>
		          <th>Teacher</th>
		          <th>Operation</th>              
		      </tr>
		  </thead>
		  <tbody>
		    @foreach($assigned_classes->groupBy('Te_Code_New') as $element)
		    @foreach ($element as $classroom)
		    
		    <tr class="table-row-all-class" @if(empty($classroom->teachers)) @elseif($classroom->Tch_ID == 'TBD') @else style="background-color: #9592d1;" @endif>
		      <td>
		        <div><h4><strong>{{ $classroom->course->Description }}</strong></h4></div>
		        <small>
		        	@if(!empty($classroom->Te_Mon_Room))

						<p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
						<p>Monday: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong> - <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>

						@endif

						@if(!empty($classroom->Te_Tue_Room))

						<p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
						<p>Tuesday: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong> - <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>

						@endif

						@if(!empty($classroom->Te_Wed_Room))

						<p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
						<p>Wednesday: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong> - <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>

						@endif

						@if(!empty($classroom->Te_Thu_Room))

						<p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
						<p>Thursday: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong> - <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>

						@endif

						@if(!empty($classroom->Te_Fri_Room))

						<p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
						<p>Friday: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong> - <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>

						@endif
		        </small>
		      </td>
		      <td>
		      	{{ $classroom->terms->Comments }} {{ date('Y', strtotime($classroom->terms->Term_Begin)) }}
		      </td>
		      <td>
				@if(empty($classroom->teachers)) No Teacher: Waitlist/Class Cancelled
				@elseif($classroom->Tch_ID == 'TBD')  No Teacher: Waitlist/Class Cancelled
				@else <span><strong>{{ $classroom->teachers->Tch_Firstname }} {{ $classroom->teachers->Tch_Lastname }}
				</strong></span>
				@endif
		      </td>

		      <td>
					<button id="showStudentsBtn" value="{{ $classroom->Code}}" class="btn btn-default btn-space">Show Students</button>
					<a href="{{ route('teacher-select-week', [$classroom->Code]) }}" class="btn btn-default btn-space">Manage Attendance</a>
					<button id="enterResultsBtn" value="{{ $classroom->Code}}" class="btn btn-default btn-space">Enter Results</button>
		      </td>
		    </tr>
		    @endforeach
		    @endforeach
		  {{-- @endforeach --}}
		  </tbody>
		</table>

	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="students-here">
			
		</div>
	</div>
</div>

@stop

@section('java_script')
<script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>

<script type="text/javascript">
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
      success: function(data, status) {
        // console.log(data)
        $(".students-here").html(data);
        $(".students-here").html(data.options);
        // scroll down to bottom of page
        $('html, body').animate({scrollTop:$(document).height()}, 'slow');
      }
  });
}); 

$("button[id='enterResultsBtn']").click(function(){
  var Code = $(this).val();
  var token = $("input[name='_token']").val();

  $("button[id='enterResultsBtn'][value='"+Code+"']").addClass('btn-success');
  $("button[id='enterResultsBtn'][value='"+Code+"']").removeClass('btn-default');
  $("button").not("button[id='enterResultsBtn'][value='"+Code+"']").addClass('btn-default');
  $("button").not("button[id='enterResultsBtn'][value='"+Code+"']").removeClass('btn-success');


  $.ajax({
      url: "{{ route('teacher-enter-results') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
      success: function(data, status) {
        // console.log(data)
        $(".students-here").html(data);
        $(".students-here").html(data.options);
        // scroll down to bottom of page
        $('html, body').animate({scrollTop:$(document).height()}, 'slow');
      }
  });
}); 
</script>
@stop