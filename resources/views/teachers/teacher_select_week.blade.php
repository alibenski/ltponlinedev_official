
@extends('shared_template')

@section('customcss')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

<div class="row">
  <div class="row">
    <div class="col-md-2">
      <a href="{{ route('teacher-view-classrooms') }}" class="btn btn-danger btn-space"><i class="fa fa-arrow-circle-left"></i> View Your Classes</a>
      @hasrole('Teacher FP')
      <a href="{{ route('teacher-view-all-classrooms') }}" class="btn btn-info btn-space"><i class="fa fa-arrow-circle-left"></i> View All Classes</a>
      @endhasrole
    </div>
  
    <div class="col-md-10">
      <h3 class="text-center" style="margin-top: 3px;"><strong>Manage Attendance for @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $course->schedules->name }}</strong></h3>
      <input type="hidden" name="CodeClass" value="{{ $course->CodeClass }}">
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <button id="Wk1" class="btn btn-space btn-default btn-wk">Week 1: {{ date('d-M-Y', strtotime($term->Term_Begin)) }}</button>
      <button id="Wk2" class="btn btn-space btn-default btn-wk">Week 2: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(1))) }}</button>
      <button id="Wk3" class="btn btn-space btn-default btn-wk">Week 3: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(2)))}}</button>
      <button id="Wk4" class="btn btn-space btn-default btn-wk">Week 4: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(3)))}}</button>
      <button id="Wk5" class="btn btn-space btn-default btn-wk">Week 5: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(4)))}}</button>
      <button id="Wk6" class="btn btn-space btn-default btn-wk">Week 6: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(5)))}}</button>
      <button id="Wk7" class="btn btn-space btn-default btn-wk">Week 7: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(6)))}}</button>
      <button id="Wk8" class="btn btn-space btn-default btn-wk">Week 8: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(7)))}}</button>
      <button id="Wk9" class="btn btn-space btn-default btn-wk">Week 9: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(8)))}}</button>
      <button id="Wk10" class="btn btn-space btn-default btn-wk">Week 10: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(9)))}}</button>
      <button id="Wk11" class="btn btn-space btn-default btn-wk">Week 11: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(10)))}}</button>
      <button id="Wk12" class="btn btn-space btn-default btn-wk">Week 12: {{ date('d-M-Y', strtotime(\Carbon\Carbon::parse($term->Term_Begin)->addWeeks(11)))}}</button>
    </div>
  </div>
  
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="insert-table-here"></div>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <div class="insert-students-here"></div>
    </div>
  </div>

</div>

@stop

@section('java_script')
<script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>

<script>
  $("button.btn-wk").click(function(){
    var Wk = $(this).attr('id');
    var CodeClass = $("input[name='CodeClass']").val();
    var token = $("input[name='_token']").val();

    // $("button[id='manageAttendanceBtn'][value='"+Code+"']").addClass('btn-success');
    // $("button[id='manageAttendanceBtn'][value='"+Code+"']").removeClass('btn-default');
    // $("button").not("button[id='manageAttendanceBtn'][value='"+Code+"']").addClass('btn-default');
    // $("button").not("button[id='manageAttendanceBtn'][value='"+Code+"']").removeClass('btn-success');
    $(".insert-table-here").html('');
    $(".insert-students-here").html('');

    $.ajax({
        url: "{{ route('teacher-week-table') }}", 
        method: 'GET',
        data: {Wk:Wk,CodeClass:CodeClass,_token:token},
        success: function(data, status) {
          // console.log(data)
          $(".insert-table-here").html(data);
          $(".insert-table-here").html(data.options);
        }
    });
  }); 

</script>

@stop