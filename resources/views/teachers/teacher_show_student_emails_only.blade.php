<div class="table-responsive filtered-table">
  <div class="preloader2"><p>Please wait... Fetching data from the database...</p></div>
  <h4><strong>Student Emailss of @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $course->schedules->name }}</strong></h4>
  
  <h5 style="margin-left: 1.5rem">{{$course->Term}}-{{substr($course->courses->Description, 0, 2)}} {{substr($course->courses->Description, strpos($course->courses->Description, ": ") + 1)}}-{{substr($course->classrooms->teachers->Tch_Firstname, 0, 1)}}. {{$course->classrooms->teachers->Tch_Lastname}}-{{substr($course->schedules->name, 0, 3)}}@if (($pos = strrpos($course->schedules->name, "&")) !== FALSE)&{{str_replace(' ','',substr($course->schedules->name, $pos + 1, 4))}} @endif
									@if(\Carbon\Carbon::parse($course->schedules->begin_time) < \Carbon\Carbon::parse('1899-12-30 12:00:00'))Morning @else Lunch @endif // {{$course->CodeClass}}</h5>
  <table class="table table-bordered table-striped">
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
<div id="modalshow" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body-schedule">
            </div> 
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".preloader2").fadeOut(600);
    
});
</script>
<script> 
$(document).ajaxError(function(e, xhr, opt, exc){
    console.log("Error requesting " + opt.url + ": " + xhr.status + " " + xhr.statusText + " " + exc);
});
</script>