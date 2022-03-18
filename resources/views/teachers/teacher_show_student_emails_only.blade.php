<div class="table-responsive filtered-table">
  <div class="preloader2"><p>Please wait... Fetching data from the database...</p></div>
  <h4><strong>Student Emailss of @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $course->schedules->name }}</strong></h4>
  
  <h4 style="margin-left: 1.5rem"><b>Class Code:</b> {{$course->Term}}-{{substr($course->courses->Description, 0, 2)}} {{substr($course->courses->Description, strpos($course->courses->Description, ": ") + 1)}}-{{substr($course->classrooms->teachers->Tch_Firstname, 0, 1)}}. {{$course->classrooms->teachers->Tch_Lastname}}-{{substr($course->schedules->name, 0, 3)}}@if (($pos = strrpos($course->schedules->name, "&")) !== FALSE)&{{str_replace(' ','',substr($course->schedules->name, $pos + 1, 4))}} @endif
									@if(\Carbon\Carbon::parse($course->schedules->begin_time) < \Carbon\Carbon::parse('1899-12-30 12:00:00'))Morning @else Lunch @endif // {{$course->CodeClass}}</h4>
  <br />                  
  <table id="sampol" class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>Email addresses</th>
          </tr>
      </thead>
      <tbody>
      {{-- @foreach($form_info as $form_in) --}}
        @foreach($form_info as $form)
        <tr class="table-row" @if ($form->deleted_at) style="background-color: #eed5d2;" @endif>
          <td>
            @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
        </tr>
        @endforeach
      {{-- @endforeach --}}
      </tbody>
  </table>
  
  <input type="hidden" name="_token" value="{{ Session::token() }}">

</div>
<script>
$(document).ready(function () {
    $('#sampol').DataTable({
        "fixedHeader": true,
        "deferRender": true,
        "dom": 'B<"clear">lfrtip',
        "pageLength": 50,
        "buttons": [
                'copy', 'csv', 'excel', 'pdf'
            ],
    });
    $(".preloader2").fadeOut(600);
});
</script>
<script> 
$(document).ajaxError(function(e, xhr, opt, exc){
    console.log("Error requesting " + opt.url + ": " + xhr.status + " " + xhr.statusText + " " + exc);
});
</script>