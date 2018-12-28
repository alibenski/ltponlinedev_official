@extends('admin.no_sidebar_admin')

@section('content')

<div class="row">
  <div class="col-sm-12">
    <div id="accordion">
        @foreach($classrooms as $classroom)
        <h3><strong>{{ $classroom_3->course->Description}}</strong></h3>
        <div class="col-sm-12">
        <h3>Section # {{ $classroom->sectionNo }}</h3>
          <p>Teacher: <h4>@if($classroom->Tch_ID) <strong>{{ $classroom->teachers->Tch_Name }}</strong> @else <span class="label label-danger">none assigned / waitlisted</span> @endif</h4></p>
          @if(!empty($classroom->Te_Mon_Room))
          <p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
          <p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
          <p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>
          @endif
          @if(!empty($classroom->Te_Tue_Room))
          <p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
          <p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
          <p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>
          @endif
          @if(!empty($classroom->Te_Wed_Room))
          <p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
          <p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
          <p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>
          @endif
          @if(!empty($classroom->Te_Thu_Room))
          <p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
          <p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
          <p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>
          @endif
          @if(!empty($classroom->Te_Fri_Room))
          <p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
          <p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
          <p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>
          @endif

          <div class="table-responsive filtered-table">
            <h4><strong>{{ $classroom_3->course->Description}} Students</strong></h4>

            <button style="margin-bottom: 10px" class="btn btn-primary delete_all">Move Selected</button>
            
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><input type="checkbox" id="master"></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact No.</th>
                        <th>Priority</th>
                        <th>Flexible?</th>
                        <th>Schedules</th>
                        <th>Submission Date</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody>
                {{-- @foreach($form_info as $form_in) --}}
                  @foreach($form_info as $form)
                    @if ($form->CodeClass === $classroom->Code)
                    <tr id="tr_{{$form->id}}" @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
                      <td>
                        <div class="counter"></div>
                      </td>
                      <td>
                        @if($form->deleted_at) 
                        @else 
                        <input type="checkbox" class="sub_chk" data-id="{{ $form->id }}">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                        @endif
                      </td>
                      <td>
                        @if(empty($form->users->name)) None @else {{ $form->users->name }} @endif 
                        @if($form->deleted_at) <span class="label label-danger">Cancelled</span> @else @endif
                      </td>
                      <td>
                        @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
                      <td>
                        @if(empty($form->users->sddextr->PHONE)) None @else {{ $form->users->sddextr->PHONE }} @endif </td>
                      <td>
                        <input name="INDEXID" type="hidden" value="{{ $form->INDEXID }}">
                        <input name="Term" type="hidden" value="{{ $form->Term }}">
                        <input name="L" type="hidden" value="{{ $form->L }}">
                        <input name="CodeIndexID" type="hidden" value="{{ $form->CodeIndexID }}">
                        <strong>
                         <div><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div>
                         <div id="{{ $form->CodeIndexID }}" class="priority-status"></div> 
                        </strong>
                      </td>
                      <td>
                        @if($form->flexibleBtn == 1)
                                    <span class="label label-success margin-label">Yes</span>
                                  @else
                          -
                                  @endif
                      </td>
                      <td>
                        <a id="modbtn" class="btn btn-info btn-space" data-toggle="modal" href="#modalshow" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-formx="{{ $form->form_counter }}" data-mtitle=""><span><i class="fa fa-eye"></i></span> Wishlist Schedule</a>
                      </td>
                      <td>
                        {{$form->created_at}}
                      </td>
                      <td>
                        @if(is_null($form->convocation_email_sent))
                          @if(!is_null($form->classrooms->Tch_ID) && $form->classrooms->Tch_ID != 'TBD')
                            <button type="button" value="{{ $form->CodeIndexIDClass }}" id="sendEmailConvocation" class="btn btn-success"><i class="fa fa-send"></i> Send Email Convocation</button>
                          @endif
                        @else --
                        @endif
                      </td>
                    </tr>  
                    @endif
                  @endforeach
                {{-- @endforeach --}}
                </tbody>
            </table>
          </div>

              {{-- <ol>
              @foreach ($arr as $record)
                @if ($record->CodeClass === $classroom->Code)
                  <li>ID:{{$record->id}} - {{ucwords($record->users->name)}} - Priority:{{$record->PS}}
                    <a id="modbtn" class="btn btn-sm btn-info btn-space" data-toggle="modal" href="#modalshowform" data-id="{{ $record->id }} data-mtitle="Moving : {{ucwords($record->users->name)}}"><span><i class="fa fa-arrow-right"></i></span> Move Student</a>
                  </li>
                @endif
              @endforeach
              </ol> --}}
        </div>
        @endforeach
    </div>
  </div>
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
{{-- modal to edit --}}
<div id="modalshowform" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Move Students</h4>
            </div>
            <div class="modal-body-move-student">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('java_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js"></script>

<script>
$(document).ready(function () {
    $('#modalshow').on('show.bs.modal', function (event) {
      var link = $(event.relatedTarget); // Link that triggered the modal
      var dtitle = link.data('mtitle');
      var dindexno = link.data('indexno');
      var dtecode = link.data('tecode');
      var dterm = link.data('term');
      var dapproval = link.data('approval');
      var dFormCounter = link.data('formx');
      var token = $("input[name='_token']").val();
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('ajax-preview-modal') }}', {'indexno':dindexno, 'tecode':dtecode, 'term':dterm, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
          // console.log(data);
          $('.modal-body-schedule').html(data);
      });
    });
});
</script>

<script>
$(document).ready(function () {
    var counter = 0;
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        $('#'+counter).html(counter);
        // console.log(counter)
    });    

    var arr = [];
    $('input[name="CodeIndexID"]').each(function(){
        var CodeIndexID = $(this).val();
        var Term = $("input[name='Term']").val();
        var L = $("input[name='L']").val();
        var INDEXID = $("input[name='INDEXID']").val();
        var token = $("input[name='_token']").val();
        // console.log(CodeIndexID)
        $.get('{{ route('ajax-get-priority') }}', {'INDEXID':INDEXID, 'L':L, 'Term':Term, 'CodeIndexID':CodeIndexID, '_token':token }, function(data) {
          // console.log(data)
          $('.fa-spin').addClass('hidden');
          $('#'+CodeIndexID).html(data);
        });
        arr.push(CodeIndexID); //insert values to array per iteration
    });
    // console.log(arr)
});
</script>

<script type="text/javascript">
  $("input[name='schedule_id']").click(function(){
      var schedule_id = $(this).val();
      var Te_Code = $("input[name='Te_Code']").val();
      var Term = $("input[name='Term']").val();
      var L = $("input[name='L']").val();
      var token = $("input[name='_token']").val();
      
      $.ajax({
          url: "{{ route('ajax-preview') }}", 
          method: 'POST',
          data: {schedule_id:schedule_id, Te_Code:Te_Code, Term:Term, L:L, _token:token},
          success: function(data, status) {
            // console.log(data)
            $(".preview-here").html(data);
            $(".preview-here").html(data.options);
          }
      });
  }); 
</script>

<script>
$(document).ready(function() {
    $('#sendEmailConvocation').on('click', function() {
      $(this).attr('disabled', 'disabled');
      var CodeIndexIDClass = $(this).val();
      console.log(CodeIndexIDClass)
      var token = $("input[name='_token']").val();

      $.ajax({
        url: '{{ route('send-individual-convocation') }}',
        type: 'POST',
        data: {CodeIndexIDClass:CodeIndexIDClass, _token:token},
      })

      .done(function(data) {
        console.log(data);
        if (["success"]) {
          window.location.reload();
        }
      })

      .fail(function(data) {
         if (["fail"]) { 
          alert('Error sending. Please contact System Administrator.');
          window.location.reload();
        }
      })
      
    });
  });  

</script>

<script type="text/javascript">
  $(document).ready(function () {


      $('#master').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk").prop('checked', true);  
       } else {  
          $(".sub_chk").prop('checked',false);  
       }  
      });


      $('.delete_all').on('click', function(e) {

          var allVals = [];  
          $(".sub_chk:checked").each(function() {  
              allVals.push($(this).attr('data-id'));
          });  

          var join_selected_values = allVals.join(",");

          var token = $("input[name='_token']").val();
          

          if(allVals.length <=0)  
          {  
              alert("Please select at least 1 student.");  

          }  else {  
              $('#modalshowform').modal('show');
              $.get('{{ route('ajax-move-students-form') }}', {'ids':join_selected_values,  '_token':token}, function(data) {
                // console.log(data);
                $('.modal-body-move-student').html(data);
              });
          }
          //     // var check = confirm("Are you sure you want to delete this row?");  
          //     // if(check == true){  


          //     //     var join_selected_values = allVals.join(","); 


          //     //     $.ajax({
          //     //         url: $(this).data('url'),
          //     //         type: 'DELETE',
          //     //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          //     //         data: 'ids='+join_selected_values,
          //     //         success: function (data) {
          //     //             if (data['success']) {
          //     //                 $(".sub_chk:checked").each(function() {  
          //     //                     $(this).parents("tr").remove();
          //     //                 });
          //     //                 alert(data['success']);
          //     //             } else if (data['error']) {
          //     //                 alert(data['error']);
          //     //             } else {
          //     //                 alert('Whoops Something went wrong!!');
          //     //             }
          //     //         },
          //     //         error: function (data) {
          //     //             alert(data.responseText);
          //     //         }
          //     //     });


          //     //   $.each(allVals, function( index, value ) {
          //     //       $('table tr').filter("[data-row-id='" + value + "']").remove();
          //     //   });
          //     // }  
          // }  
      });


      // $('[data-toggle=confirmation]').confirmation({
      //     rootSelector: '[data-toggle=confirmation]',
      //     onConfirm: function (event, element) {
      //         element.trigger('confirm');
      //     }
      // });


      // $(document).on('confirm', function (e) {
      //     var ele = e.target;
      //     e.preventDefault();


      //     $.ajax({
      //         url: ele.href,
      //         type: 'DELETE',
      //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      //         success: function (data) {
      //             if (data['success']) {
      //                 $("#" + data['tr']).slideUp("slow");
      //                 alert(data['success']);
      //             } else if (data['error']) {
      //                 alert(data['error']);
      //             } else {
      //                 alert('Whoops Something went wrong!!');
      //             }
      //         },
      //         error: function (data) {
      //             alert(data.responseText);
      //         }
      //     });


      //     return false;
      // });
  });
</script>
@stop