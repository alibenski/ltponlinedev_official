  <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Week</th>
          <th>Day</th>
          <th>Time</th>  
          <th>Operation</th>  
        </tr>
      </thead>
      <tbody>
        @if(!empty($day_time->Te_Mon_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td class="td-week">{{ $wk }}</td>
          <td>Monday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Mon_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Mon_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="button" for="Monday" class="btn btn-info btn-sm btn-log hidden">Log Attendance</button>
                <input type="hidden" for="{{ $wk }}" name="wk" class="wkCounter" value="{{ $wk }}">
                <input type="hidden" name="day" value="Monday">
                <input type="hidden" name="time" value="{{ date('h:i a', strtotime($day_time->Te_Mon_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Mon_ETime)) }}">
                <input type="hidden" name="Code" value="{{ $day_time->Code }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Tue_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td class="td-week">{{ $wk }}</td>
          <td>Tuesday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Tue_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Tue_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="button" for="Tuesday" class="btn btn-info btn-sm btn-log hidden">Log Attendance</button>
                <input type="hidden" for="{{ $wk }}" name="wk" class="wkCounter" value="{{ $wk }}">
                <input type="hidden" name="day" value="Tuesday">
                <input type="hidden" name="time" value="{{ date('h:i a', strtotime($day_time->Te_Tue_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Tue_ETime)) }}">
                <input type="hidden" name="Code" value="{{ $day_time->Code }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Wed_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td class="td-week">{{ $wk }}</td>
          <td>Wednesday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Wed_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Wed_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="button" for="Wednesday" class="btn btn-info btn-sm btn-log hidden">Log Attendance</button>
                <input type="hidden" for="{{ $wk }}" name="wk" class="wkCounter" value="{{ $wk }}">
                <input type="hidden" name="day" value="Wednesday">
                <input type="hidden" name="time" value="{{ date('h:i a', strtotime($day_time->Te_Wed_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Wed_ETime)) }}">
                <input type="hidden" name="Code" value="{{ $day_time->Code }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Thu_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td class="td-week">{{ $wk }}</td>
          <td>Thursday</td>
            <td>{{ date('h:i a', strtotime($day_time->Te_Thu_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Thu_ETime)) }}</td>
            <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="button" for="Thursday" class="btn btn-info btn-sm btn-log hidden">Log Attendance</button>
                <input type="hidden" for="{{ $wk }}" name="wk" class="wkCounter" value="{{ $wk }}">
                <input type="hidden" name="day" value="Thursday">
                <input type="hidden" name="time" value="{{ date('h:i a', strtotime($day_time->Te_Thu_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Thu_ETime)) }}">
                <input type="hidden" name="Code" value="{{ $day_time->Code }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Fri_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td class="td-week">{{ $wk }}</td>
          <td>Friday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Fri_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Fri_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="button" for="Friday" class="btn btn-info btn-sm btn-log hidden">Log Attendance</button>
                <input type="hidden" for="{{ $wk }}" name="wk" class="wkCounter" value="{{ $wk }}">
                <input type="hidden" name="day" value="Friday">
                <input type="hidden" name="time" value="{{ date('h:i a', strtotime($day_time->Te_Fri_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Fri_ETime)) }}">
                <input type="hidden" name="Code" value="{{ $day_time->Code }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Sat_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td class="td-week">{{ $wk }}</td>
          <td>Saturday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Sat_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Sat_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="button" for="Saturday" class="btn btn-info btn-sm btn-log hidden">Log Attendance</button>
                <input type="hidden" for="{{ $wk }}" name="wk" class="wkCounter" value="{{ $wk }}">
                <input type="hidden" name="day" value="Saturday">
                <input type="hidden" name="time" value="{{ date('h:i a', strtotime($day_time->Te_Sat_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Sat_ETime)) }}">
                <input type="hidden" name="Code" value="{{ $day_time->Code }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
          </td>
        </tr>
        @endif

      </tbody>
  </table>

<script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
<script>
$(document).ready(function () {
    let weekText = $("button.btn-wk.btn-success").text();
    $("td.td-week").text(weekText);
    var counter = 0;
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        $('#'+counter).html(counter);
        // console.log(counter)
    });
    var wkcounter = 0;
    $('.wkCounter').each(function() {
        wkcounter++;
        $(this).attr('id', wkcounter);
        var wkVal = $(this).attr('id');
        var wkSubVal = $(this).attr('for');
        $(this).val(wkSubVal+'_'+wkVal);
        // console.log(wkcounter)
    $('button.btn-log').removeClass('hidden');
    }); 

    var teacherManageAttendances = function(){
      var wk = $(this).closest('tr').find('input[name="wk"]').val();
      var time = $(this).closest('tr').find('input[name="time"]').val();
      var day = $(this).closest('tr').find('input[name="day"]').val();
      var Code = $(this).closest('tr').find("input[name='Code']").val();
      var token = $("input[name='_token']").val();

      $(this).closest('tr').addClass('bg-green');
      $("button.btn-log").not('button[for="'+day+'"]').closest('tr').removeClass('bg-green');
      $(".insert-students-here").append('<div class="preloader2"><p class="text-center"><strong>Loading...</strong></p></div>');

      $.ajax({
          url: "{{ route('teacher-manage-attendances') }}", 
          method: 'GET',
          data: {wk:wk, time:time, day:day, Code:Code, _token:token},
          success: function(data, status) {
            $(".insert-students-here").html(data);
            $(".insert-students-here").html(data.options);
          }
      });
    }

  // jQuery ajax to load manage attendance view
  $("button.btn-log").click(teacherManageAttendances);

  // update resource(s) and ajax jQuery to reload manage attendance view 
  $( document ).on('click', '.btn-save', function() {
      var token = $("input[name='_token']").val();
      var wk = $("input[name='wkManageAttendance']").val();
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
                    $(".insert-students-here").append('<div class="saveloader"><p class="text-center"><strong>Saving...</strong></p></div>');
                    setTimeout(function() {
                      $("tr.bg-green").closest("tr").find("button.btn-log").each(teacherManageAttendances);
                    }, 2000); 

                  } else {
                    alert('Something went wrong!');
                    window.location.reload();
                  }
                }
            });
          }
  });
  
});
</script>