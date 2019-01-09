  <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Week</th>
          <th>Day</th>
          <th>Time</th>  
        </tr>
      </thead>
      <tbody>
        @if(!empty($day_time->Te_Mon_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td>
            {{ $wk }} <input type="hidden" name="wk" value="{{ $wk }}">
          </td>
          <td>Monday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Mon_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Mon_ETime)) }}</td>
          <td>
            <a href="{{ route('teacher-manage-attendances', ['Code' => $day_time->Code]) }}" class="btn btn-info btn-sm">Log Attendance</a>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Tue_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td>{{ $wk }}</td>
          <td>Tuesday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Tue_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Tue_ETime)) }}</td>
          <td>
            <a href="{{ route('teacher-manage-attendances', ['Code' => $day_time->Code]) }}" class="btn btn-info btn-sm">Log Attendance</a>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Wed_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td>{{ $wk }}</td>
          <td>Wednesday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Wed_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Wed_ETime)) }}</td>
          <td>
            <a href="{{ route('teacher-manage-attendances', ['Code' => $day_time->Code]) }}" class="btn btn-info btn-sm">Log Attendance</a>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Thu_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td>{{ $wk }}</td>
          <td>Thursday</td>
            <td>{{ date('h:i a', strtotime($day_time->Te_Thu_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Thu_ETime)) }}</td>
            <td>
            <a href="{{ route('teacher-manage-attendances', ['Code' => $day_time->Code]) }}" class="btn btn-info btn-sm">Log Attendance</a>
          </td>
        </tr>
        @endif

        @if(!empty($day_time->Te_Fri_Room))
        <tr>
          <td>
            <div class="counter"></div>
          </td>
          <td>{{ $wk }}</td>
          <td>Friday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Fri_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Fri_ETime)) }}</td>
          <td>
            <a href="{{ route('teacher-manage-attendances', ['Code' => $day_time->Code]) }}" class="btn btn-info btn-sm">Log Attendance</a>
          </td>
        </tr>
        @endif

      </tbody>
  </table>


<script>
$(document).ready(function () {
    var counter = 0;
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        $('#'+counter).html(counter);

        var wk = $(this).closest("tr").find("input[name='wk']").val();
        console.log(wk)
        // $(this).closest("tr").find("input[name='wk']").;
        // console.log(counter)
    });    

});
</script>