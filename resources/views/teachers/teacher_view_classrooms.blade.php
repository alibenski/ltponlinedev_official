@extends('teachers.teacher_template')

@section('content')

<div class="row">
	<div class="col-md-12">
@foreach($assigned_classes as $classroom)
		<div class="col-md-3">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3>{{ $classroom->course->Description }}</h3>
				</div>
				<div class="box-body no-padding">
					<div class="col-md-12">

						@if(!empty($classroom->Te_Mon_Room))

						{{-- <p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p> --}}
						<p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
						<p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>

						@endif

						@if(!empty($classroom->Te_Tue_Room))

						{{-- <p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p> --}}
						<p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
						<p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>

						@endif

						@if(!empty($classroom->Te_Wed_Room))

						{{-- <p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p> --}}
						<p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
						<p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>

						@endif

						@if(!empty($classroom->Te_Thu_Room))

						{{-- <p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p> --}}
						<p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
						<p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>

						@endif

						@if(!empty($classroom->Te_Fri_Room))

						{{-- <p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p> --}}
						<p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
						<p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>

						@endif		
						
					</div>
				</div>
				<div class="box-footer">
						<button id="showStudentsBtn" value="{{ $classroom->Code}}" class="btn btn-default">Show Students</button>
				</div>
			</div>
		</div>
	
@endforeach
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
<script type="text/javascript">


$("button[id='showStudentsBtn']").click(function(){
  var Code = $(this).val();
  var token = $("input[name='_token']").val();

	

  $("button[value='"+Code+"']").addClass('btn-success');
  $("button[value='"+Code+"']").removeClass('btn-default');
  $("button").not("button[value='"+Code+"']").addClass('btn-default');
  	$("button").not("button[value='"+Code+"']").removeClass('btn-success');


  $.ajax({
      url: "{{ route('teacher-show-students') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
      success: function(data, status) {
        // console.log(data)
        $(".students-here").html(data);
        $(".students-here").html(data.options);
      }
  });
}); 
</script>
@stop