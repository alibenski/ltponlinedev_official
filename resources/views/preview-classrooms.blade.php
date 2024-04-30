@extends('admin.no_sidebar_admin')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')

@include('admin.partials._termSessionMsg')
<p class="hidden">view-classrooms-per-section</p>

<div class="row">
  <div class="col-sm-12">
    <div id="accordion">
        @foreach($classrooms as $classroom)
        <h3><strong>{{ $classroom_3->course->Description}}</strong></h3>
        <div class="btn-group">
          <a class="btn btn-success btn-space" href="{{ route('pdfview',['download'=>'pdf', 'code'=> $classroom->Code]) }}" target="_blank"><i class="fa fa-print"></i> Print/Download</a>
          <a class="btn btn-info btn-space view-attendance" href="{{ route('admin-view-classrooms', ['Code'=> $classroom->Code]) }}" target="_blank"><i class="fa fa-eye"></i> View Attendance/Grades</a>
        </div>
        
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><strong>Section # {{ $classroom->sectionNo }}</strong></h3>
            </div>
            <div class="panel-body">
              <p>Teacher: 
                <h4 class="teacher-name">@if($classroom->Tch_ID) <strong>{{ $classroom->teachers->Tch_Name }}</strong> @else <span class="label label-danger">none assigned / waitlisted</span> @endif</h4>

                <div class="col-sm-4 insert-select-teacher-here hidden">
                  <select class="form-control"name="select-teacher" autocomplete="off">
                    <option value=""></option>
                  </select>
                </div>
              </p>
            </div>
            <div class="panel-footer">
              <button id="changeTeacherBtn" class="btn btn-warning">Change Teacher</button>
              <button id="saveTeacherBtn" class="btn btn-success hidden" data-id="{{ $classroom->id }}">Save</button>
            </div>
          </div>
          
          <div class="panel panel-default">
            <div class="panel-body">
              @if(!empty($classroom->Te_Mon_Room))
              <div class="col-sm-6">
                <p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
                <p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
                <p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>
                <hr>
              </div>
              @endif
              @if(!empty($classroom->Te_Tue_Room))
              <div class="col-sm-6">
                <p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
                <p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
                <p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>
                <hr>
              </div>
              @endif
              @if(!empty($classroom->Te_Wed_Room))
              <div class="col-sm-6">
                <p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
                <p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
                <p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>
                <hr>
              </div>
              @endif
              @if(!empty($classroom->Te_Thu_Room))
              <div class="col-sm-6">
                <p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
                <p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
                <p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>
                <hr>
              </div>
              @endif
              @if(!empty($classroom->Te_Fri_Room))
              <div class="col-sm-6">
                <p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
                <p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
                <p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>
                <hr>
              </div>
              @endif
              @if(!empty($classroom->Te_Sat_Room))
              <div class="col-sm-6">
                <p>Saturday Room: <strong>{{ $classroom->roomsSat->Rl_Room }}</strong></p>
                <p>Saturday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Sat_BTime ))}}</strong></p>
                <p>Saturday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Sat_ETime)) }}</strong></p>
                <hr>
              </div>
              @endif
            </div>
            
            <div class="panel-footer">
              <a href="{{ route('classrooms.edit', $classroom->id) }}" class="btn btn-warning">Edit Classroom Parameters</a>
            </div>            
          </div>

        </div>
    </div>    
  </div>
</div>  

<div class="row">
  <div class="col-sm-12">
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
                  <th>Availability Day(s)</th>
                  <th>Availability Time(s)</th>
                  <th>Availability Delivery Mode(s)</th>
                  <th>Flexible Day?</th>
                  <th>Flexible Time?</th>
                  <th>Flexible Format?</th>
                  <th>Schedules</th>
                  <th>Comments</th>
                  <th>Remark</th>
                  <th>Submission Date</th>
                  <th>Cancelled & Billed?</th>
                  <th>Cancel Date/Time Stamp</th>
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
                  <h4>@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif <small>[{{$form->INDEXID}}] </small></h4> 
                        @foreach ($studentWithMoreClasses as $item)
                            @foreach ($item as $key => $value)
                                @if ($key==$form->INDEXID)
                                    <i class="fa fa-exclamation-triangle text-danger"></i> <span class="text-danger">more than 1 class</span><br />
                                @endif
                            @endforeach
                        @endforeach
                  @if(empty($form->users->profile)) No Profile 
                  @else  
                  <span class="label label-default">
                      @if($form->users->profile == "STF") Staff Member @endif
                      @if($form->users->profile == "INT") Intern @endif
                      @if($form->users->profile == "CON") Consultant @endif
                      @if($form->users->profile == "WAE") When Actually Employed @endif
                      @if($form->users->profile == "JPO") JPO @endif
                      @if($form->users->profile == "MSU") Staff of Permanent Mission @endif
                      @if($form->users->profile == "SPOUSE") Spouse of Staff from UN or Mission @endif
                      @if($form->users->profile == "RET") Retired UN Staff Member @endif
                      @if($form->users->profile == "SERV") Staff of Service Organizations in the Palais @endif
                      @if($form->users->profile == "NGO") Staff of UN-accredited NGO's @endif
                      @if($form->users->profile == "PRESS") Staff of UN Press Corps @endif
                  </span>
                  @endif
                        
                  <p>@if($form->deleted_at) <span class="label label-danger">Cancelled</span> @else @endif</p>

                  @if ($form->enrolments)
                  <p>
                    @foreach ($form->enrolments as $element)
                      @if ($element->is_self_pay_form)
                        <i class="fa fa-euro" title="self-paying student"></i> self-paying
                      @endif
                    @endforeach
                  </p>
                  @endif
                  
                  @if ($form->placements)
                  <p>
                    @foreach ($form->placements as $element)
                      @if ($element->is_self_pay_form)
                        <i class="fa fa-euro" title="self-paying student"></i> self-paying
                      @endif
                    @endforeach
                  </p>
                  @endif
                  
                  @if ($form->no_show)
                      <p><span class="label label-warning">No Show</span></p>
                  @endif
                </td>
                <td>
                  @if(empty($form->users->email)) None @else {{ $form->users->email }} @endif </td>
                <td>
                  @if(empty($form->users->sddextr->PHONE)) None @else {{ $form->users->sddextr->PHONE }} @endif </td>
                <td>
                  <strong>
                    @if ($form->PS == 1)
                      Re-enrolment
                    @endif
                    @if ($form->PS == 2)
                      In Waitlist
                    @endif
                    @if ($form->PS == 3)
                      Within 2 Terms/Not Re-enrolment
                    @endif
                    @if ($form->PS == 4)
                      Placement Forms/Others
                    @endif
                    @if ($form->PS == 5)
                      Late Enrolment
                    @endif
                  </strong>
                   [ {{$form->PS}} ] 
                  <input name="INDEXID" type="hidden" value="{{ $form->INDEXID }}">
                  <input name="Term" type="hidden" value="{{ $form->Term }}">
                  <input name="L" type="hidden" value="{{ $form->L }}">
                  <input name="CodeIndexID" type="hidden" value="{{ $form->CodeIndexID }}">
                  <input name="Te_Code" type="hidden" value="{{ $form->Te_Code }}">
                  {{-- <strong>
                   <div><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div>
                   <div id="{{ $form->CodeIndexID }}" class="priority-status"></div> 
                  </strong> --}}
                </td>
                <td>
                  @if ($form->placements->first())
                    {{ $form->placements->first()->dayInput }}  
                  @endif
                </td>
                <td>
                  @if ($form->placements->first())
                    {{ $form->placements->first()->timeInput }}  
                  @endif
                </td>
                <td>
                  @if ($form->placements->first()) 
                    @if($form->placements->first()->deliveryMode === 0)<span class="glyphicon glyphicon-ok text-success"></span> in-person @elseif($form->placements->first()->deliveryMode === 1)<span class="glyphicon glyphicon-ok text-success"></span> online @elseif($form->placements->first()->deliveryMode === 2)<span class="glyphicon glyphicon-ok text-success"></span> both in-person and online @else <span class="glyphicon glyphicon-remove text-danger"></span> No response @endif 
                  @endif
                </td>
                <td>
                  @if(is_null($form->flexibleDay))
                    -
                  @elseif($form->flexibleDay === 1)
                    <span class="badge label-success">Yes</span>
                              @else
                    <span class="badge label-danger">NOT FLEXIBLE</span>
                              @endif
                </td>
                <td>
                  @if(is_null($form->flexibleTime))
                    -
                  @elseif($form->flexibleTime === 1)
                    <span class="badge label-success">Yes</span>
                              @else
                    <span class="badge label-danger">NOT FLEXIBLE</span>
                              @endif
                </td>
                <td>
                  @if(is_null($form->flexibleFormat))
                    -
                  @elseif($form->flexibleFormat === 1)
                    <span class="badge label-success">Yes</span>
                              @else
                    <span class="badge label-danger">NOT FLEXIBLE</span>
                              @endif
                </td>

                <td>
                  <a id="modbtn" class="btn btn-info btn-space" data-toggle="modal" href="#modalshow" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-formx="{{ $form->eform_submit_count }}" data-mtitle=""><span><i class="fa fa-eye"></i></span> Wishlist </a>
                </td>
                <td>
                  <button type="button" class="btn btn-default btn-space view-all-comments" data-toggle="modal" title="View HR student admin etc. comments here"><i class="fa fa-comment"></i> View All Comments</button>

                  <div id="viewAllComments-{{ $form->INDEXID }}-{{ $form->Te_Code }}-{{ $form->Term }}" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                          <div class="modal-content">

                              <div class="modal-header bg-default">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
                                  <h4 class="modal-title"><i class="fa fa-comment"></i> View All Comments</h4>
                              </div>
                              <div class="modal-body-view-all-comments">
                                <div class="col-sm-12"> 
                                  <p><strong>HR Comment:</strong> {{ $form->hr_comments }}</p>
                                  <p><strong>Student Comment:</strong> {{ $form->std_comments }}</p>
                                  <p><strong>Course Preference:</strong> {{ $form->course_preference_comment }}</p>
                                  <p><strong>Teacher Comment:</strong> {{ $form->teacher_comments }}</p>
                                  @if ($form->admin_eform_comment)
                                    <p><strong>Admin Regular Form Comment When Assigned to Course:</strong> {{ $form->admin_eform_comment }}</p>
                                  @endif
                                  @if ($form->admin_plform_comment)
                                    <p><strong>Admin Placement Form Comment When Assigned to Course:</strong> {{ $form->admin_plform_comment }}</p>
                                  @endif
                                  @if ($form->Comments)
                                    <p><strong>Admin Comment on Manually Created Form:</strong> {{ $form->Comments }}</p>
                                  @endif
                                </div> 
                              </div>
                              <div class="modal-footer modal-background">
                                
                              </div>
                          
                          </div>
                      </div>
                  </div>
                </td>
                <td>
                  <textarea id="{{ $form->id }}" name="pash-remark" class="remark" cols="30" rows="1" value="" placeholder="Saving overwrites the existing comment."></textarea>
                  <div class="row">
                    <button id="{{ $form->id }}" class="btn btn-success btn-space save-remark" disabled=""><i class="fa fa-save"></i></button>
                    <small>@if ($form->lastRemarkBy) Last remark by  {{ $form->lastRemarkBy->name }}  @endif</small>
                  </div>
                </td>
                <td>
                  {{$form->created_at}}
                </td>
                <td>
                  @if ($form->deleted_at)
                    @if ($form->deleted_at > $form->terms->Cancel_Date_Limit)
                      @if ($form->enrolments)
                        @foreach ($form->enrolments as $element)
                          @if ($element->is_self_pay_form)
                          @else
                            @if ($form->cancelled_but_not_billed || in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
                            @else
                              @if ($loop->first)
                              <strong>YES</strong>
                              @endif
                            @endif
                          @endif
                        @endforeach
                      @endif

                      @if ($form->placements)
                        @foreach ($form->placements as $element)
                          @if ($element->is_self_pay_form)
                          @else
                            @if ($form->cancelled_but_not_billed || in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
                            @else
                              @if ($loop->first)
                              <strong>YES</strong>
                              @endif
                            @endif
                          @endif
                        @endforeach
                      @endif
                    @endif
                  @endif
                </td>
                <td>
                  @if ($form->deleted_at)
                    {{$form->deleted_at}} <br />by {{$form->cancelledBy->name}}                
                  @endif
                </td>
                <td>
                  @if(is_null($form->convocation_email_sent))
                    @if(!is_null($form->classrooms->Tch_ID) && $form->classrooms->Tch_ID != 'TBD')
                      <button type="button" value="{{ $form->CodeIndexIDClass }}" id="sendEmailConvocation" class="btn btn-success btn-space"><i class="fa fa-send"></i> Send Email Convocation</button>
                    @endif
                  @else 
                  @endif

                  <button type="button" class="btn btn-danger btn-space pash-delete" data-toggle="modal" @if($form->deleted_at) disabled="" @endif> @if($form->deleted_at)<i class="fa fa-remove"></i> Cancelled @else <i class="fa fa-trash"></i> Delete @endif</button>

                  @if ($form->deleted_at)
                    <form method="POST" action="{{ route('undelete-pash', $form->id) }}" class="undelete-form form-prevent-multi-submit">
                        <input id="unDeleteInput" type="submit" value="Undo Delete" class="undelete-form btn btn-success btn-space button-prevent-multi-submit">

                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                       {{ method_field('PUT') }}
                    </form>
                  @endif

                  <div id="modalDeletePash-{{ $form->id }}" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                          <div class="modal-content">

                              <div class="modal-header bg-danger">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
                                  <h3 class="modal-title">Class Cancellation</h3>
                              </div>
                              <div class="modal-body-pash-delete">
                                <div class="col-sm-12">

                                  <form method="POST" action="{{ route('cancel-convocation', $form->CodeIndexIDClass) }}" class="delete-form form-prevent-multi-submit">

                                      <h4>Index # {{ $form->INDEXID }} : <strong> {{ $form->users->name }}</strong></h4>
                                      <h4>Cancelling participation from <strong> {{ $form->courses->Description }}</strong></h4>
                                      
                                      <div class="form-group">
                                        <h4><input type="checkbox" name="cancelled_but_not_billed" value=1> Student will <strong class="text-danger"><u>NOT</u></strong> be billed</h4>
                                      </div>

                                      <input type="submit" value="@if($form->deleted_at) Cancelled @else Delete @endif" class="delete-form btn btn-danger btn-space button-prevent-multi-submit" @if($form->deleted_at) disabled="" @else @endif>

                                      <input type="hidden" name="deleteTerm" value="{{ $form->Term }}">
                                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                                     {{ method_field('DELETE') }}
                                  </form>

                                </div>
                              </div>
                              <div class="modal-footer modal-background">
                                
                              </div>
                          
                          </div>
                      </div>
                  </div>

                </td>
              </tr>  
              @endif
            @endforeach
          {{-- @endforeach --}}
          </tbody>
      </table>
    </div>
  </div>
</div>
@endforeach



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
$(document).ready(function() {
  var arr = [];
  var eform_submit_count = [];
  var token = $("input[name='_token']").val();
  var term = $("input[name='term']").val();
  var L = $("input[name='L']").val();

  // retrieve each remark
  $("textarea.remark").each(function() {
    var id = $(this).attr('id');
    
    arr.push(id); //insert values to array per iteration
  });
  console.log(arr)

  if (arr.length > 0) {
    $.ajax({
      url: '{{ route('ajax-preview-get-remarks') }}',
      type: 'GET',
      data: {arr:arr, term:term, _token:token},
    })
    .done(function(data) {
      // console.log(data);
      $.each(data, function(index, val) {
        $('textarea#'+index).val(val);
      });
    })
    .fail(function() {
      alert("An error occured. Click OK to reload.");
      window.location.reload();
    })
    .always(function() {
      console.log("complete");
    });
  }
  

  // enable save button only if textarea has characters
  $('textarea.remark').on("keyup", function() {
      var remark_id = $(this).attr('id');

      if( $('textarea#'+remark_id).val().length > 0) {
          $('button#'+remark_id+'.save-remark').prop("disabled", false);
      } else {
          $('button#'+remark_id+'.save-remark').prop("disabled", true);
      }
  });

  $('button.save-remark').click(function() {
    var id = $(this).attr('id');
    var remark = $('textarea#'+id).val();
    var token = $("input[name='_token']").val();

    $.ajax({
      url: '{{ route('ajax-preview-post-remarks') }}',
      type: 'PUT',
      data: {id:id, remark:remark, _token:token},
    })
    .done(function(data) {
      if (data == 'success') {
        window.location.reload();
      }
    })
    .fail(function() {
      alert("An error occured. Click OK to reload.");
      window.location.reload();
    })
    .always(function() {
      console.log("complete");
    });
    
  });
});
</script>

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
          $('.modal-body-schedule').html('');
          $('.modal-body-schedule').html(data);
      });
    });

    $('form.delete-form').submit(function() {
        var c = confirm("You are about to delete a form. Are you sure?");
        return c; //you can just return c because it will be true or false
    });
});

$(document).on('click', '.view-all-comments', function() {
  var INDEXID = $(this).closest("tr").find("input[name='INDEXID']").val();
  var Te_Code = $(this).closest("tr").find("input[name='Te_Code']").val();
  var Term = $(this).closest("tr").find("input[name='Term']").val();
    $('#viewAllComments-'+INDEXID+'-'+Te_Code+'-'+Term).modal('show'); 
});

$(document).on('click', '.pash-delete', function() {
  var pash_id = $(this).closest("tr").find("input[type='checkbox'].sub_chk").attr('data-id');
  console.log(pash_id) 
    $('#modalDeletePash-'+pash_id).modal('show'); 
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
        // $.get('{{ route('ajax-get-priority') }}', {'INDEXID':INDEXID, 'L':L, 'Term':Term, 'CodeIndexID':CodeIndexID, '_token':token }, function(data) {
        //   // console.log(data)
        //   $('.fa-spin').addClass('hidden');
        //   $('#'+CodeIndexID).html(data);
        // });
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
    $('button#sendEmailConvocation').on('click', function() {
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
              $('#modalshowform').modal({backdrop: 'static', keyboard: false});
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

<script>
  $(document).ready(function() {
    var token = $("input[name='_token']").val();

    $("#changeTeacherBtn").on('click', function() {

      $("h4.teacher-name").addClass('hidden');
      $.ajax({
        url: '{{ route('ajax-select-teacher') }}',
        type: 'GET',
        data: {_token: token},
      })
      .done(function(data) {
        console.log("success");
        $("select[name='select-teacher']").html(data.options);
        $(".insert-select-teacher-here").removeClass('hidden');
        $("#changeTeacherBtn").attr('disabled', 'disabled');
        $("#saveTeacherBtn").removeClass('hidden');
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
    });

    $(document).on('click', '#saveTeacherBtn', function() {

      $(this).attr('disabled', 'disabled');
      var id = $(this).attr('data-id');
      var teacherID = $("select[name='select-teacher']").val();

      $.ajax({
        url: '{{ route('ajax-update-teacher') }}',
        type: 'PUT',
        data: {id: id, _token: token, Tch_ID: teacherID},
      })
      .done(function(data) {
        console.log(data['Tch_ID'])
        window.location.reload();
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      

    });

  });
</script>
@stop