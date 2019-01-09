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
          <td>{{ $wk }}</td>
          <td>Monday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Mon_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Mon_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="submit" class="btn btn-info btn-sm">Log Attendance</button>
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
          <td>{{ $wk }}</td>
          <td>Tuesday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Tue_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Tue_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="submit" class="btn btn-info btn-sm">Log Attendance</button>
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
          <td>{{ $wk }}</td>
          <td>Wednesday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Wed_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Wed_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="submit" class="btn btn-info btn-sm">Log Attendance</button>
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
          <td>{{ $wk }}</td>
          <td>Thursday</td>
            <td>{{ date('h:i a', strtotime($day_time->Te_Thu_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Thu_ETime)) }}</td>
            <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="submit" class="btn btn-info btn-sm">Log Attendance</button>
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
          <td>{{ $wk }} </td>
          <td>Friday</td>
          <td>{{ date('h:i a', strtotime($day_time->Te_Fri_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Fri_ETime)) }}</td>
          <td>
            <form method="GET" action="{{ route('teacher-manage-attendances') }}">
                <button type="submit" class="btn btn-info btn-sm">Log Attendance</button>
                <input type="hidden" for="{{ $wk }}" name="wk" class="wkCounter" value="{{ $wk }}">
                <input type="hidden" name="day" value="Friday">
                <input type="hidden" name="time" value="{{ date('h:i a', strtotime($day_time->Te_Fri_BTime)) }} - {{ date('h:i a', strtotime($day_time->Te_Fri_ETime)) }}">
                <input type="hidden" name="Code" value="{{ $day_time->Code }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
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
    }); 
});
</script>