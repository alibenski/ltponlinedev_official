
{{-- @extends('teachers.teacher_template')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content') --}}
{{-- <div id="loader2"></div> --}}
<div class="preloader2">
  <p class="text-center">
    <strong>Loading data from the database... Please wait...</strong>
  </p>
</div>
<div class="row">
  <div class="col-md-12">
    <h3><strong>Log Student Attendance for @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $day }}: {{ $time }}</strong></h3>
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
        {{-- <p>L = Late</p> --}}
        <p>E = Excused</p>
        <p>A = Absent</p>
      </div>
      <!-- /.box-body -->
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    {{-- <a href="{{ route('teacher-select-week', [$classroom->Code]) }}" class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Back</a> --}}

    <button class="btn btn-success btn-save">Save Attendance</button>
    <input type="hidden" name="_token" value="{{ Session::token() }}">
    <input type="hidden" name="wkManageAttendance" value="{{ $week }}">
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
                {{-- <th>L</th>    --}}
                <th>E</th>   
                <th>A</th>   
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th class="pull-right">Set status for all students <i class="fa fa-arrow-circle-right"></i></th>
                <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterP"></th>
                {{-- <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterL"></th> --}}
                <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterE"></th>
                <th><input name="allStatus" type="checkbox" class="masterBtn" id="masterA"></th>
                
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
          @foreach($form_info as $key => $form)
          <tr id="{{$form->id}}" class="table-row">
            <td>
              <div class="counter-std"></div>
            </td>
            <td>
              @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif </td>
            <td>
              @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
            <td>
              @if(empty($form->attendances->$week)) <span class="label label-danger">None</span> @else <strong> @if($form->attendances->$week == 'P') <span class="label label-success"> Present </span> @endif @if($form->attendances->$week == 'E') <span class="label label-warning"> Excused </span> @endif @if($form->attendances->$week == 'A') <span class="label label-danger"> Absent </span> @endif </strong> @endif 
            </td>  
            <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_p sub_chk" data-id="{{ $form->id }}" value="P"></td>
            {{-- <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_l sub_chk" data-id="{{ $form->id }}" value="L"></td> --}}
            <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_e sub_chk" data-id="{{ $form->id }}" value="E"></td>
            <td><input name="indivStatus{{ $form->id }}" type="radio" class="sub_chk_a sub_chk" data-id="{{ $form->id }}" value="A"></td>
            <td>
              {{-- <textarea name="remarks{{ $form->id }}" class="remarks" cols="30" rows="1" placeholder="@if(empty($form->attendances->attendanceRemarks)) @else @foreach ($form->attendances->attendanceRemarks as $element) @if($loop->last) {{ $element->remarks }} @endif @endforeach @endif"></textarea> --}}
              <textarea name="remarks{{ $form->id }}" class="remarks" cols="30" rows="1" value=""></textarea>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
  

{{-- @stop --}}

{{-- @section('java_script') --}}
<script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>

<script>
  $(document).ready(function () {
    var counterStd = 0;
    $('.counter-std').each(function() {
        counterStd++;
        $(this).attr('id', 'std-'+counterStd);
        $('#std-'+counterStd).html(counterStd);
        // console.log(counterStd)
    });

    var promises = [];
    var allIDs = [];
    var wk = $("input[name='wkManageAttendance']").val();
    var token = $("input[name='_token']").val();

    console.log(wk)

    $('tr.table-row').each(function(){      
      allIDs.push($(this).attr('id'));
    }); //end of $.each
    
    var id = allIDs.join(",");

    promises.push($.ajax({
          url: '{{ route('ajax-get-remark') }}',
          type: 'GET',
          data: {id:id, wk:wk, _token:token},
        })
        .then(function(data) {
          // console.log("success");
          console.log(data);
          $.each(data, function(index, val) {
            $.each(val.attendance_remarks, function(index1, val1) {
              if (val1.wk_id == wk) {
                $("tr#"+val.pash_id).find("textarea.remarks").attr('placeholder', val1.remarks);
              }
            });
          });
        })
        .fail(function() {
          console.log("error");
          alert("An error occured. Click OK to reload.");
          window.location.reload();
        })
        .always(function() {
          // console.log("complete");
        })); 
    
    $.when.apply($('tr.table-row'), promises).then(function() {
        $(".preloader2").fadeOut(600);
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
{{-- @stop --}}