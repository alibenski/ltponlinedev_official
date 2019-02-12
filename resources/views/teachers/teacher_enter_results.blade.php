<div class="reload">
<div class="table-responsive filtered-table">
  <div class="preloader2"><p>Please wait...</p></div>
  <h4><strong>Enter Results for Students of @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $course->schedules->name }}</strong></h4>
  <table class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>#</th>
              <th>Name</th>
              <th>Enrolment Next Term</th>
              <th>Written</th>
              <th>Oral</th>
              <th>Overall Result</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
      {{-- @foreach($form_info as $form_in) --}}
        @foreach($form_info as $form)
        <tr class="table-row">
          <td>
            <div class="counter"></div>
          </td>
          <td>
            @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif 
            <input type="hidden" name="indexid" value="{{$form->INDEXID}}">
            <input type="hidden" name="id" value="{{$form->id}}">
            <input type="hidden" name="L" value="{{$form->L}}">
          </td>
          <td id="{{$form->INDEXID}}" class="enrolled-next-term">
            
          </td>
          <td class="input-Written">
            @if(empty($form->Written)) 
              <input type="number" name="Written" class="input-written" value="" placeholder="no grade"> 
            @else <span class="written">{{ $form->Written }}</span> @endif 

          </td>
          <td class="input-Oral">
            @if(empty($form->Oral)) 
              <input type="number" name="Oral" class="input-oral" value="" placeholder="no grade"> 
            @else <span class="oral">{{ $form->Oral }}</span> @endif 

          </td>
          <td class="input-Result">
            @if(empty($form->Result)) 
              <select name="Result" class="input-result" id="Result">
                <option value="" disabled selected>Select Here</option>
                <option value="P">Pass</option>
                <option value="F">Fail</option>
                <option value="I">Incomplete</option>
              </select>

            @else 
              <span class="result">
              @if($form->Result == "P")
                Pass
              @elseif($form->Result == "F")
                Fail
              @elseif($form->Result == "I")
                Incomplete
              @endif

              </span> 
            @endif 
            
          </td>
          <td>
            <button type="button" class="btn btn-warning btn-sm quick-edit">Edit</button>
            <button type="button" class="btn btn-success btn-sm quick-save" >Save</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
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
</div>


<script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>

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
<script> 
$(document).ready(function() {
  var promises = [];
    $('tr.table-row').each(function(){
      var indexid = $(this).closest("tr").find("input[name='indexid']").val();
      var L = $(this).closest("tr").find("input[name='L']").val();
      var token = $("input[name='_token']").val();

      promises.push($.ajax({
            url: '{{ route('ajax-show-if-enrolled-next-term') }}',
            type: 'GET',
            data: {indexid:indexid, L:L, _token:token},
          })
          .then(function(data) {
            // console.log("success");
            if (data != 'not enrolled') {

              if (data[0].length > 0) {
                console.log(data[0]);
                $.each(data[0], function(index, val) {
                  $("td#"+indexid+".enrolled-next-term").append("<p>"+val+"</p>");
                });
                
              }

              if (data[1].length > 0) {
                console.log(data[1]);
                $.each(data[1], function(i, v) {
                  $("td#"+indexid+".enrolled-next-term").append("<p>Placement: "+v+"</p>");
                });
                
              }

            }

          })
          .fail(function() {
            console.log("error");
            alert("An error occured. Click OK to reload.");
            window.location.reload();
          })
          .always(function() {
            // console.log("complete");
          })); 
    }); //end of $.each

    $.when.apply($('tr.table-row'), promises).then(function() {
        $(".preloader2").fadeOut(600);
    }); 
});

</script>
<script>
$(document).ready(function() {
  $(".quick-edit").click(function(){
    $(this).attr('disabled', 'true');
    var Written = $(this).closest("tr").find("span.written").text();
    var Oral = $(this).closest("tr").find("span.oral").text();
    var Result = $(this).closest("tr").find("span.result").text();
    var trimmedResult = $.trim(Result); // trim away whitespaces

    $(this).closest("tr").find("span.written").html('<input type="number" name="Written" value="" placeholder="'+Written+'">');
    $(this).closest("tr").find("span.oral").html('<input type="number" name="Oral" value="" placeholder="'+Oral+'">');
    if (trimmedResult == "Pass") {
      $(this).closest("tr").find("span.result").html('<select name="Result" class="input-result" id="Result"><option value="" disabled>Select Here</option><option value="P" selected>Pass</option><option value="F">Fail</option><option value="I">Incomplete</option></select>');
    }
    if (trimmedResult == "Fail") {
      $(this).closest("tr").find("span.result").html('<select name="Result" class="input-result" id="Result"><option value="" disabled>Select Here</option><option value="P">Pass</option><option value="F" selected>Fail</option><option value="I">Incomplete</option></select>');
    }
    if (trimmedResult == "Incomplete") {
      $(this).closest("tr").find("span.result").html('<select name="Result" class="input-result" id="Result"><option value="" disabled>Select Here</option><option value="P">Pass</option><option value="F">Fail</option><option value="I" selected>Incomplete</option></select>');
    }

  }); 

  $('.quick-save').on('click', function() {
    var id = $(this).closest("tr").find("input[name='id']").val();
    var Written = $(this).closest("tr").find("input[name='Written']").val();
    var Oral = $(this).closest("tr").find("input[name='Oral']").val();
    var Result = $(this).closest("tr").find("select[name='Result']").val();
    var token = $("input[name='_token']").val();
    console.log(id)

    $.ajax({
      url: '{{ route('ajax-save-results') }}',
      type: 'PUT',
      data: {id:id, Written:Written, Oral:Oral, Result:Result, _token:token},
    })
    .done(function(data) {
      var Code = data.CodeClass;
      console.log(Code);
      $.ajax({
      url: "{{ route('teacher-enter-results') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
      success: function(data, status) {
        // console.log(data)
        $(".students-here").html(data);
        $(".students-here").html(data.options);
      }
  });
    })
    .fail(function(data) {
      console.log("error");
      alert("An error occured. Click OK to reload.");
      window.location.reload();
    })
    .always(function(data) {
      console.log("complete");
    });
    
  });
});
</script>