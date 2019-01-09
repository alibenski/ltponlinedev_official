
@extends('teachers.teacher_template')

@section('content')

<div class="row">
  <div class="col-md-12">
    <h4><strong>Log Student Attedance for {{ $course->courses->Description}} - </strong></h4>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>P</th>   
              <th>L</th>   
              <th>E</th>   
              <th>A</th>   
          </tr>
          <tr>
              <th></th>
              <th></th>
              <th class="pull-right">Set status for all students <i class="fa fa-long-arrow-right "></i></th>
              <td><input type="radio" ></td>
              <td><input type="radio" ></td>
              <td><input type="radio" ></td>
              <td><input type="radio" ></td>
              
              {{-- <th>Remarks</th> --}}
          </tr>
      </thead>
      <tbody>
        @foreach($form_info as $student)
        <tr id="{{$student->id}}">
          <td>
            <div class="counter"></div>
          </td>
          <td>
            @if(empty($student->users->name)) None @else {{ $student->users->name }} @endif </td>
          <td>
            @if(empty($student->users->email)) None @else {{ $student->users->email }} @endif </td>
          <td><input type="radio" ></td>
          <td><input type="radio" ></td>
          <td><input type="radio" ></td>
          <td><input type="radio" ></td>
          {{-- <td>
            <textarea name="" id="" cols="30" rows="1"></textarea>
          </td> --}}
        </tr>
        @endforeach
      </tbody>
  </table>
  </div>
</div>
  

@stop

@section('java_script')
<script>
$(document).ready(function () {
    var counter = 0;
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        $('#'+counter).html(counter);
        // console.log(counter)
    });    

});
</script>
@stop