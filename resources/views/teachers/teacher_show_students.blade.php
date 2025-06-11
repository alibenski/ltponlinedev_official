<div class="table-responsive filtered-table">
  <div class="preloader2"><p>Please wait... Fetching data from the database...</p></div>
  <h4><strong>Students of @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $course->schedules->name }}</strong></h4>
  <h5 class="text-info">Note: From Winter 2024 (241) term, the total attendance does not add values of Orientation Week (Wk1). </h5>
  <table class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Contact No.</th>
              <th>Enrolled Next Term?</th>
              <th>Days Present</th>
              <th>Days Excused</th>
              <th>Days Absent</th>
              <th>No Show</th>
              <th>Operation</th>
          </tr>
      </thead>
      <tbody>
      {{-- @foreach($form_info as $form_in) --}}
        @foreach($form_info as $form)
        <tr class="table-row" @if ($form->deleted_at) style="background-color: #eed5d2;" @endif>
          <td>
            <div class="counter"></div>
          </td>
          <td>
            @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif 
            @if ($form->deleted_at) <label for="cancelled" class="label label-danger">cancelled</label> @endif
            <input type="hidden" name="indexid" value="{{$form->INDEXID}}">
            <input type="hidden" name="L" value="{{$form->L}}">
            <input type="hidden" name="id" value="{{$form->id}}">
          </td>
          <td>
            @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
          <td>
            @if(empty($form->users->sddextr->PHONE)) None @else {{ $form->users->sddextr->PHONE }} @endif 
          </td>
          <td id="{{$form->INDEXID}}" class="enrolled-next-term">
            
          </td>
          <td id="{{$form->id}}" class="days-present">
            
          </td>
          <td id="{{$form->id}}" class="days-excused">
            
          </td>
          <td id="{{$form->id}}" class="days-absent">
            
          </td>
          <td id="{{$form->id}}" class="no-show-status">
            @if ($form->no_show == 1)
                <span class="badge">Yes</span>
            @endif
          </td>
          <td id="{{$form->id}}" class="no-show-button">
            @if ($form->no_show == 0)
              <button id="{{$form->id}}" class="btn btn-danger no-show-btn operation-btn" data-student-name="{{ $form->users->name }}" data-code-class="{{$form->CodeClass}}">Mark No Show</button>   
            @else
              <button id="{{$form->id}}" class="btn btn-default undo-btn operation-btn" data-student-name="{{ $form->users->name }}" data-code-class="{{$form->CodeClass}}">Undo No Show</button> 
            @endif
          </td>
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
    var counter = 0;
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        $('#'+counter).html(counter);
    });    
    
    var id = [];
    var indexid = [];
    var L = $("tr.table-row").find("input[name='L']").val();
    var token = $("input[name='_token']").val();

    $('tr.table-row').each(function(){
      var each_id = $(this).closest("tr.table-row").find("input[name='id']").val();
      var each_indexid = $(this).closest("tr.table-row").find("input[name='indexid']").val();

      id.push(each_id);
      indexid.push(each_indexid);
    });

    $.ajax({
      url: '{{ route('ajax-show-overall-attendance') }}',
      type: 'GET',
      dateType:"json",
      data: {indexid:indexid, L:L,id:id, _token:token},
    })
    .done(function(data) {
        if (data == 0) {
            $("td.days-present").append("<p>0</p>");
            $("td.days-excused").append("<p>0</p>");
            $("td.days-absent").append("<p>0</p>");
        } else {
          $.each(data, function(index, val) {
              $("td#"+val.pash_id+".days-present").append("<p>"+val.P+"</p>");
              $("td#"+val.pash_id+".days-excused").append("<p>"+val.E+"</p>");
              $("td#"+val.pash_id+".days-absent").append("<p>"+val.A+"</p>");
          });
        }

        showIfEnrolledNextTerm();
        showIfEnrolledNextTermPlacement();
    })
    .fail(function(data) {
        console.log("error on loading attendance summary: " + data);
        alert("An error occured. Empty array: " + data + " The term might not be set. Click OK to be redirected. ");
        window.location.href = "/admin/teacher-dashboard";
    })
    .always(function() {
        console.log("load complete attendance summary");
    });
          
    function showIfEnrolledNextTerm() {
        var promises = [];    
        promises.push(
              $.ajax({
                url: '{{ route('ajax-show-if-enrolled-next-term') }}',
                type: 'GET',
                dateType:"json",
                data: {indexid:indexid, L:L, _token:token},
              })
              .then(function(data) {
                console.log(data);
                $.each(data, function (indexInArray, valueOfElement) { 
                   console.log(valueOfElement.INDEXID)
                   $("td#"+valueOfElement.INDEXID+".enrolled-next-term").append("<p>"+valueOfElement['courses']['Description']+"</p>");
                });
              })
              .fail(function(data) {
                console.log("error on showing enrol next term: " + data);
                alert("An error occured. Click OK to reload.");
                window.location.reload();
              })
              .always(function() {
                console.log("complete show if enrolled");
              })
        ); 

        $.when.apply($('tr.table-row'), promises).then(function() {
            $(".preloader2").fadeOut(600);
        }); 
    }

    function showIfEnrolledNextTermPlacement() {
        var promises = [];    
        promises.push(
              $.ajax({
                url: '{{ route('ajax-show-if-enrolled-next-term-placement') }}',
                type: 'GET',
                dateType:"json",
                data: {indexid:indexid, _token:token},
              })
              .then(function(data) {
                console.log(data);
                $.each(data, function (indexInArray, valueOfElement) { 
                  $("td#"+valueOfElement.INDEXID+".enrolled-next-term").append("<p>Submitted Placement: "+valueOfElement['languages']['name']+"</p>");
                   
                });
              })
              .fail(function(data) {
                console.log("error on showing enrol next term: " + data);
                alert("An error occured. Click OK to reload.");
                window.location.reload();
              })
              .always(function() {
                console.log("complete show if placement");
              })
        ); 

        $.when.apply($('tr.table-row'), promises).then(function() {
            $(".preloader2").fadeOut(600);
        }); 
    }
});

$("button.no-show-btn").on("click", function () {
  const token = $("input[name='_token']").val();
  let pash_id = $(this).attr("id");
  let name = $(this).attr("data-student-name");
  let Code = $(this).attr("data-code-class");
  // put confirm pop-up
  let c = confirm("You are about to mark this student (" +name+ ") as NO-SHOW. Are you sure?");
  if (c == true) {
    $("button.operation-btn").attr('disabled', true);
    $(".preloader2").fadeIn(600);
    // ajax to update pash record
    $.ajax({
        url: "{{ route('mark-no-show') }}", 
        method: 'POST',
        data: {pash_id:pash_id, _token:token},
    })
    .done(function(data) {
        loadStudents(Code, token);
    })
    .fail(function(data) {
        console.log("error");
    })
    .always(function(data) {
        console.log("always");
    });    
  }
})

$("button.undo-btn").on("click", function () {
  const token = $("input[name='_token']").val();
  let pash_id = $(this).attr("id");
  let name = $(this).attr("data-student-name");
  let Code = $(this).attr("data-code-class");
  // put confirm pop-up
  let c = confirm("You are about to undo the NO-SHOW status of this student (" +name+ "). Are you sure?");
  if (c == true) {
    $("button.operation-btn").attr('disabled', true);
    // ajax to update pash record
    $.ajax({
        url: "{{ route('undo-no-show') }}", 
        method: 'POST',
        data: {pash_id:pash_id, _token:token},
    })
    .done(function(data) {
        loadStudents(Code, token);
    })
    .fail(function(data) {
        console.log("error");
    })
    .always(function(data) {
        console.log("always");
    });    
  }
})

function loadStudents(Code, token) {
  $.ajax({
      url: "{{ route('teacher-show-students') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
  })
  .done(function(data) {
        $(".students-here").html(data);
        $(".students-here").html(data.options);
        
        if (!$.isArray(data)) {
            alert("An error occured while loading Show Students Page. Click OK to reload.");
            window.location.reload();
        }
  })
  .fail(function(data) {
      console.log("error");
      alert("An error occured while loading Show Students Page. Click OK to reload.");
      window.location.reload();
  })
  .always(function(data) {
      console.log("complete show students");
  });
}
</script>
<script> 
$(document).ajaxError(function(e, xhr, opt, exc){
    console.log("Error requesting " + opt.url + ": " + xhr.status + " " + xhr.statusText + " " + exc);
});
</script>