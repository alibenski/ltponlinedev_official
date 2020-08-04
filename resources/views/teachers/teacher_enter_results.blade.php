<div class="reload">
<div class="table-responsive filtered-table">
  <div class="preloader2"><p>Please wait... Loading results interface...</p></div>
  <div class="alert alert-warning alert-dismissible">
    <h5 class="text-center"><i class="icon fa fa-bullhorn fa-2x"></i> <strong>Overall result is mandatory. Save button will remain disabled until a result has been chosen. </strong></h5>
  </div>
  <h4><strong>Enter Results for Students of @if(empty($course->courses->Description)) {{ $course->Te_Code }} @else {{ $course->courses->Description}} @endif - {{ $course->schedules->name }}</strong></h4>
  <table class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>#</th>
              <th>Name</th>
              <th>Enrolment Next Term</th>
              <th>Written</th>
              <th>Oral</th>
              <th>Overall Grade</th>
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
            <ol></ol>
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
          <td class="input-Overall-Grade">
            @if(empty($form->Overall_Grade)) 
              <input type="number" name="Overall_Grade" class="input-overall-grade" value="" placeholder="no grade"> 
            @else <span class="overall-grade">{{ $form->Overall_Grade }}</span> @endif 

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
            <button type="button" class="btn btn-warning btn-sm btn-space quick-edit">Edit</button>
            <button type="button" class="btn btn-success btn-sm btn-space quick-save" disabled>Save</button>
            <button type="button" class="btn btn-primary btn-sm btn-space assign-course" data-toggle="modal"><i class="fa fa-upload"></i> Assign Course</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </td>
        </tr>
        @endforeach
      {{-- @endforeach --}}
      </tbody>
  </table>
  
  <input type="hidden" name="_token" value="{{ Session::token() }}">

</div>
<div id="modalshow" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-purple">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
                <h4 class="modal-title">Assign Course to Student</h4>
            </div>
            <div class="modal-body-content modal-background">
            </div>

        </div>
    </div>
</div>  
</div>

<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script>
$(document).ready(function () {
    var counter = 0;
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        $('#'+counter).html(counter);
        // console.log(counter)
    });    
    $('.dropdown-toggle').dropdown();
    
});
</script>
<script>
$(document).ready(function () {
    $('.assign-course').click( function() {
      var indexid = $(this).closest("tr").find("input[name='indexid']").val();
      var L = $(this).closest("tr").find("input[name='L']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
        url: "{{ route('teacher-assign-course-view') }}",
        type: 'GET',
        data: {indexid:indexid, L:L,_token: token},
      })
      .done(function(data, status) {
        console.log("show assign view status: "+status);
        if (data == 'missingSelectedTerm') {
            alert('Something went wrong. Missing selected term.')
            window.location.href = "{{ route('teacher-dashboard') }}";
          } else {
            $('.modal-body-content').html(data)
            $('#modalshow').modal('show');
          }
      })
      .fail(function(e, data) {
        if (e.status == 403) {
              alert('Session Expired!')
              // window.location.reload();
              window.location.href = "{{ route('login') }}";
          } else {
            alert('Something went wrong. Session may have expired.')
            window.location.href = "{{ route('login') }}";
          }
      })
      .always(function() {
        console.log("complete show assign view");
      });

    });    
});
</script>
<script> 
$(document).ready(function() {
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
  
  showIfEnrolledNextTerm();
    
  function showIfEnrolledNextTerm() {
      // var promises = [];    
      // promises.push(
            $.ajax({
              url: '{{ route('ajax-show-if-enrolled-next-term') }}',
              type: 'GET',
              dateType:"json",
              data: {indexid:indexid, L:L, _token:token},
            })
            .then(function(data) {
              $.each(data, function (indexInArray, valueOfElement) { 
                  $("td#"+valueOfElement.INDEXID+".enrolled-next-term").find('ol').append("<li class='appended-value-1' data-name='"+valueOfElement['Te_Code']+"'></li>");
                  $("td#"+valueOfElement.INDEXID+".enrolled-next-term").find("li.appended-value-1[data-name='"+valueOfElement['Te_Code']+"']").append(valueOfElement['courses']['Description']);
              });
              
              showIfEnrolledNextTermPlacement();
            })
            .fail(function(data) {
              console.log("error on showing enrol next term: " + data);
              alert("An error occured while checking if student enrolled to the next term. Click OK to reload.");
              window.location.reload();
            })
            .always(function() {
              console.log("complete append next term courses");
            })
      // ); 

      // $.when.apply($('tr.table-row'), promises).then(function() {
      //     $('[data-toggle="tooltip"]').tooltip(); 
      //     $(".preloader2").fadeOut(600);
      // }); 
      return this;
  }

  function showIfEnrolledNextTermPlacement() {
      // var promises = [];    
      // promises.push(
            $.ajax({
              url: '{{ route('ajax-show-if-enrolled-next-term-placement') }}',
              type: 'GET',
              dateType:"json",
              data: {indexid:indexid, _token:token},
            })
            .then(function(data) {
              $.each(data, function (indexInArray, valueOfElement) { 
                $("td#"+valueOfElement.INDEXID+".enrolled-next-term").find('ol').append("<li class='appended-value-2'>Submitted Placement: "+valueOfElement['languages']['name']+"</li>");
              });

              checkIfAssigned();
            })
            .fail(function(data) {
              console.log("error on showing enrol next term placement: " + data);
              alert("An error occured while checking placement forms. Click OK to reload.");
              window.location.reload();
            })
            .always(function() {
              console.log("complete append next term placement");
            })
      // ); 

      // $.when.apply($('tr.table-row'), promises).then(function() {
      //     $(".preloader2").fadeOut(600);
      // }); 
      return this;
  }

  function checkIfAssigned() {
    var promises = [];    
    promises.push(
          $.ajax({
            url: '{{ route('ajax-check-if-assigned') }}',
            type: 'GET',
            dateType:"json",
            data: {indexid:indexid, L:L, _token:token},
          })
          .then(function(data) {
            $.each(data, function (indexInArray, valueOfElement) {
                $("td#"+valueOfElement.INDEXID+".enrolled-next-term").find("li.appended-value-1[data-name='"+valueOfElement['Te_Code']+"']").append("<span><i class='fa fa-star text-success' data-toggle='tooltip' title='This form has been assigned to a course by "+valueOfElement['modify_user']['name']+"'></i> </span>");
            });
          })
          .fail(function(data) {
            console.log("error on showing enrol next term: " + data);
            alert("An error occured while fetching data. Click OK to reload.");
            window.location.reload();
          })
          .always(function() {
            console.log("complete check if assigned");
          })
    ); 

    $.when.apply($('tr.table-row'), promises).then(function() {
        $('[data-toggle="tooltip"]').tooltip(); 
        $(".preloader2").fadeOut(600);
    }); 
    return this;
  }
});
</script>

<script>
$(document).ready(function() {
  $(".quick-edit").on('click', function(){
    $(this).attr('disabled', 'true');
    var Written = $(this).closest("tr").find("span.written").text();
    var Oral = $(this).closest("tr").find("span.oral").text();
    var Overall_Grade = $(this).closest("tr").find("span.overall-grade").text();
    var Result = $(this).closest("tr").find("span.result").text();
    var trimmedResult = $.trim(Result); // trim away whitespaces

    var spanResultValue = $(this).closest("tr").find("span.result").text();
    if (spanResultValue) {
      $(this).closest("tr").find(".quick-save").attr('disabled', false);
    } else {
      console.log('no action')
    }

    $(this).closest("tr").find("span.written").html('<input type="number" name="Written" value="" placeholder="'+Written+'">');
    $(this).closest("tr").find("span.oral").html('<input type="number" name="Oral" value="" placeholder="'+Oral+'">');
    $(this).closest("tr").find("span.overall-grade").html('<input type="number" name="Overall_Grade" value="" placeholder="'+Overall_Grade+'">');

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

  $('select.input-result').on('change', function() {
    $(this).closest("tr").find(".quick-save").attr('disabled', false);
  });

  $('.quick-save :disabled').on('click', function() {
    alert('y');
  });

  $('.quick-save').on('click', function() {
    var id = $(this).closest("tr").find("input[name='id']").val();
    var Written = $(this).closest("tr").find("input[name='Written']").val();
    var Oral = $(this).closest("tr").find("input[name='Oral']").val();
    var Overall_Grade = $(this).closest("tr").find("input[name='Overall_Grade']").val();
    var Result = $(this).closest("tr").find("select[name='Result']").val();
    var token = $("input[name='_token']").val();
    console.log(id)

    $.ajax({
      url: "{{ route('ajax-save-results') }}",
      type: 'PUT',
      data: {id:id, Written:Written, Oral:Oral, Overall_Grade:Overall_Grade, Result:Result, _token:token},
    })
    .done(function(data) {
      var Code = data.CodeClass;
      console.log(Code);
      $.ajax({
          url: "{{ route('teacher-enter-results') }}", 
          method: 'POST',
          data: {Code:Code, _token:token},
      })
      .done(function(data) {
          // console.log(data)
            $(".students-here").html(data);
            $(".students-here").html(data.options);
      })
      .fail(function(data) {
          console.log("error");
          alert("An error occured. Click OK to reload.");
          window.location.reload();
      })
      .always(function(data) {
          console.log("complete");
      });
    })
    .fail(function(data) {
      console.log("error");
      alert("An error occured. Click OK to reload.");
      window.location.reload();
    })
    .always(function(data) {
      console.log("complete quick save");
    });
    
  });
});
</script>

<script>  
$('#modalshow').on('click', '.modal-accept-btn',function() {
  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var teacher_comments = $("textarea#textarea-"+eform_submit_count+"[name='teacher_comments'].course-no-change").val();


  $.ajax({
    url: "{{ route('teacher-nothing-to-modify') }}",
    type: 'PUT',
    data: {teacher_comments:teacher_comments, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
  })
  .done(function(data) {
    console.log(data);
    if (data == 0) {
      alert('Hmm... Nothing to change, nothing to update...');
    }

    var L = $("input[name='L']").val();

      $.ajax({
          url: "{{ route('teacher-assign-course-view') }}",
          type: 'GET',
          data: {indexid:qry_indexid, L:L,_token: token},
        })
        .done(function(data) {
          console.log("no change assign view : success");
          $('.modal-body-content').html(data);
        })
  })
  .fail(function() {
    alert("An error occured. Click OK to reload.");
    window.location.reload();
  })
  .always(function() {
    console.log("complete");
    
  });
    
});

$('#modalshow').on('click', '.modal-save-btn',function() {
  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var Te_Code = $("select#"+eform_submit_count+"[name='Te_Code'].course_select_no").val();
  var schedule_id = $("select#schedule-"+eform_submit_count+"[name='schedule_id']").val();
  var teacher_comments = $("textarea#textarea-"+eform_submit_count+"[name='teacher_comments'].course-changed").val();

  $(".overlay").fadeIn('fast'); 

  $.ajax({
    url: "{{ route('teacher-save-assigned-course') }}",
    type: 'PUT',
    data: {Te_Code:Te_Code, schedule_id:schedule_id, teacher_comments:teacher_comments, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
  })
  .done(function(data) {
    console.log(data);
    if (data == 0) {
      alert('Hmm... Nothing to change, nothing to update. Your selected course and schedule have already been assigned to this student.');
    }
    var L = $("input[name='L']").val();

    $.ajax({
      url: "{{ route('teacher-assign-course-view') }}",
      type: 'GET',
      data: {indexid:qry_indexid, L:L,_token: token},
    })
    .done(function(data) {
      console.log("refreshing the assign view : success"); 
      $('.modal-body-content').html(data);    
    })
    .always(function() {
      console.log("complete refresh modal view");
    });

  })
  .fail(function() {
    alert("An error occured. Click OK to reload.");
    window.location.reload();
  })
  .always(function() {
    console.log("complete save assigned course");
  });
  
});

$('#modalshow').on('click', 'button.course-delete',function() {

  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var method = $("input[name='_method']").val();
  var teacher_comments = $("textarea#textarea-"+eform_submit_count+"[name='teacher_comments'].course-changed").val();

  var r = confirm("You are about to delete a form. Are you sure?");
  if (r == true) {

    $(".overlay").fadeIn('fast'); 

    $.ajax({
      url: "{{ route('teacher-delete-form') }}",
      type: 'POST',
      data: {teacher_comments:teacher_comments, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token, _method:method},
    })
    .done(function(data) {
      console.log(data);
      var L = $("input[name='L']").val();

      $.ajax({
        url: "{{ route('teacher-assign-course-view') }}",
        type: 'GET',
        data: {indexid:qry_indexid, L:L,_token: token},
      })
      .done(function(data) {
        console.log("refreshing the assign view : success"); 
        if (data == 'missingSelectedTerm') {
            alert('Something went wrong. Missing selected term.')
            window.location.href = "{{ route('teacher-dashboard') }}";
          } else {
            $('.modal-body-content').html(data);    
          }
      })
      .fail(function() {
        alert("An error occured. Click OK to reload.");
        window.location.reload();
      })
      .always(function() {
        console.log("complete refresh modal view");
      });

    })
    .fail(function() {
      alert("An error occured. Click OK to reload.");
      window.location.reload();
    })
    .always(function() {
      console.log("complete delete form");
    });
  }

});

$('#modalshow').on('click', 'button.show-modal-history', function() {
    $('.modal-title-history').text('Past Language Course Enrolment');
    $('#showModalHistory').modal('show');
});    
</script>



<script>
$('#modalshow').on('hidden.bs.modal', function (event) {

  console.log(event.target)
  // alert( "This will be displayed only once." );
  //    $( this ).off( event );
  if (event.target.id == 'modalshow') {
    $(".preloader2").fadeIn('fast');
    var Code = $("button[id='enterResultsBtn'].btn-success").val();
    var token = $("input[name='_token']").val();

    $.ajax({
      url: "{{ route('teacher-enter-results') }}", 
      method: 'POST',
      data: {Code:Code, _token:token},
    })
    .done(function(data) {

      $(".students-here").html(data);
      $(".students-here").html(data.options);
      console.log("inserted student table");
    })
    .fail(function(data) {
      console.log("error");
      alert("An error occured. Click OK to reload.");
      window.location.reload();
    })
    .always(function(data) {
      console.log("complete close modal");
    });
  }
});
</script>