<div class="table-responsive filtered-table">
  <div class="preloader2"><p>Please wait... Fetching data from the database...</p></div>
  <h4><strong>Students of @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $course->schedules->name }}</strong></h4>
  <table class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Contact No.</th>
              <th>Enrolled Next Term?</th>
              {{-- <th>Action</th> --}}
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
            <input type="hidden" name="L" value="{{$form->L}}">
          </td>
          <td>
            @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
          <td>
            @if(empty($form->users->sddextr->PHONE)) None @else {{ $form->users->sddextr->PHONE }} @endif 
          </td>
          <td id="{{$form->INDEXID}}" class="enrolled-next-term">
            
          </td>
          {{-- <td>
            <button class="btn btn-default"> Result</button>
          </td> --}}
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