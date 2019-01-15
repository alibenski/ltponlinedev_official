
@extends('teachers.teacher_template')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content')
<div id="loader2"></div>
<div class="row">
  <div class="col-md-12">
    <h3><strong>Log Student Attedance for {{ $course->courses->Description}} - {{ $day }}: {{ $time }} ({{ $week }})</strong></h3>
  </div>
</div>

<div class="row">
  <div class="col-md-3">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">LEGEND</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <p>P = Present</p>
        <p>L = Late</p>
        <p>E = Excused</p>
        <p>A = Absent</p>
      </div>
      <!-- /.box-body -->
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <a href="{{ route('teacher-select-week', ['Code'=> $classroom->Code]) }}" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</a>

    <button class="btn btn-success btn-save">Save Attendance</button>
    <input type="hidden" name="_token" value="{{ Session::token() }}">
    <input type="hidden" name="wk" value="{{ $week }}">
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="table-responsive filtered-table">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Existing Status</th>
                <th>P</th>   
                <th>L</th>   
                <th>E</th>   
                <th>A</th>   
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th class="pull-right">Set status for all students <i class="fa fa-arrow-circle-right"></i></th>
                <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterP"></th>
                <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterL"></th>
                <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterE"></th>
                <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterA"></th>
                
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
          @foreach($form_info as $key => $form)
          <tr id="{{$form->id}}">
            <td>
              <div class="counter"></div>
            </td>
            <td>
              @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif </td>
            <td>
              @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
            <td>
              @if(empty($form->attendances->$week)) <span class="label label-danger">None</span> @else <strong>{{ $form->attendances->$week }}</strong> @endif 
            </td>  
            <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_p sub_chk" data-id="{{ $form->id }}" value="P"></td>
            <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_l sub_chk" data-id="{{ $form->id }}" value="L"></td>
            <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_e sub_chk" data-id="{{ $form->id }}" value="E"></td>
            <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_a sub_chk" data-id="{{ $form->id }}" value="A"></td>
            <td>
              <textarea name="remarks{{ $form->id }}" class="remarks" cols="30" rows="1" placeholder="@if(empty($form->attendances->attendanceRemarks)) @else @foreach ($form->attendances->attendanceRemarks as $element) @if($loop->last) {{ $element->remarks }} @endif @endforeach @endif"></textarea>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
  

@stop

@section('java_script')
<script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
<script>
 $(window).load(function(){
    $("#loader2").fadeOut(600);
 });
</script>

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

<script type="text/javascript">
  $(document).ready(function () {

      $('#masterP').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk_p").prop('checked', true);
          $(".masterBtn").not($(this)).prop('checked',false);
       } else {  
          $(".sub_chk_p").prop('checked',false);  
       }  
      });

      $('#masterL').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk_l").prop('checked', true);
          $(".masterBtn").not($(this)).prop('checked',false);  
       } else {  
          $(".sub_chk_l").prop('checked',false);  
       }  
      });

      $('#masterE').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk_e").prop('checked', true);  
          $(".masterBtn").not($(this)).prop('checked',false);
       } else {  
          $(".sub_chk_e").prop('checked',false);  
       }  
      });

      $('#masterA').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk_a").prop('checked', true); 
          $(".masterBtn").not($(this)).prop('checked',false);
       } else {  
          $(".sub_chk_a").prop('checked',false);  
       }  
      });

  });
</script>

<script>
  $(document).ready(function () {
    $(".btn-save").click(function(){

      var token = $("input[name='_token']").val();
      var wk = $("input[name='wk']").val();
      var allVals = [];  
      var allStatus = [];
      var allRemarks = [];
          $(".sub_chk:checked").each(function() {  
              allVals.push($(this).attr('data-id'));
              allStatus.push($(this).val());
              allRemarks.push($(this).closest("tr").find("textarea.remarks").val());
          });
      
      var join_selected_values = allVals.join(",");
      var join_status_values = allStatus.join(",");
      var join_remarks_values = allRemarks.join(",");

      if(allVals.length <=0)  
          {  
              alert("Please enter attendance for at least 1 student.");  

          }  else {
            $(this).attr('disabled', 'true');

            $.ajax({
                url: "{{ route('ajax-teacher-attendance-update') }}", 
                method: 'PUT',
                data: { ids:join_selected_values, attendanceStatus:join_status_values, remarks:join_remarks_values, _token:token, wk:wk },
                success: function(data, status) {
                  console.log(data)
                  if (data == 'success') {
                    location.reload();
                  } else {
                    alert('Something went wrong!');
                    location.reload();
                  }
                  // $(".preview-here").html(data);
                  // $(".preview-here").html(data.options);
                }
            });
          }
    }); 
  });
</script>

@stop