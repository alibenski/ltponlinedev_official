@extends('main')
@section('tabtitle', 'Submitted Forms')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet">
@stop
@section('content')
<div id="loader"></div>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-info">
        <h5 class="text-center">Please select the desired <b>Term</b> from the dropdown and click <b>Submit</b> to see the forms submitted during that semester.</h5>
      </div>
      <div class="card card-body">
      <form method="GET" action="{{ route('previous-submitted') }}">
        <div class="form-group">
          <label for="termValue" class="col-md-12 control-label">Term Select:</label>
          <div class="form-group col-sm-12">
            <div class="dropdown">
              <select id="termValue" name="termValue" class="col-md-8 form-control select2-term-single" style="width: 100%;" required="required">
                @foreach($term_select as $value)
                    <option></option>
                    <option value="{{$value->Term_Code}}">{{$value->Comments}} - {{$value->Term_Name}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="form-group col-md-8">
          <button type="submit" class="btn btn-success" value="UNOG">View Forms</button>        
        </div>

      </form>
      </div>
    </div>
  </div>
  @if ($next_term->Term_Code == '001')
 
  @else
  
  @if (count($student_convoked) > 0)
      
  <div class="row">
      <div class="col-sm-12">
          <div class="card">
              <div class="card-header bg-success"><strong>Your Language Training Course for {{ $next_term->Term_Name }}</strong></div>

              <div class="card-body">
                <p>
                  @foreach ($student_convoked as $element)
                  <h3><strong>@if(!empty($element->courses->Description)){{ $element->courses->Description }}@endif</strong></h3>
                  
                  <p>Schedule: <strong>@if(!empty($element->schedules->name)){{$element->schedules->name}}@endif</strong></p>  

                    @if(!empty($element->classrooms->Te_Mon_Room))
                    <p>Monday Room: <strong>{{ $element->classrooms->roomsMon->Rl_Room }}</strong></p>
                    @endif
                    @if(!empty($element->classrooms->Te_Tue_Room))
                    <p>Tuesday Room: <strong>{{ $element->classrooms->roomsTue->Rl_Room }}</strong></p>
                    @endif
                    @if(!empty($element->classrooms->Te_Wed_Room))
                    <p>Wednesday Room: <strong>{{ $element->classrooms->roomsWed->Rl_Room }}</strong></p>
                    @endif
                    @if(!empty($element->classrooms->Te_Thu_Room))
                    <p>Thursday Room: <strong>{{ $element->classrooms->roomsThu->Rl_Room }}</strong></p>
                    @endif
                    @if(!empty($element->classrooms->Te_Fri_Room))
                    <p>Friday Room: <strong>{{ $element->classrooms->roomsFri->Rl_Room }}</strong></p>
                    @endif

                  <p>
                    @if($element->classrooms->Tch_ID == 'TBD')
                    <h4><span class="badge badge-danger"> Waitlisted</span></h4> 
                    @elseif(empty($element->classrooms->Tch_ID))
                    <h4><span class="badge badge-danger"> Waitlisted</span></h4> 
                    @else 
                    Teacher: <strong>{{ $element->classrooms->teachers->Tch_Name }} </strong>
                    @endif
                  </p>
                  <br> 
                    @if($element->classrooms->Tch_ID == 'TBD')
                    @elseif(empty($element->classrooms->Tch_ID))
                    @else
                    <form method="POST" action="{{ route('cancel-convocation', [$element->CodeIndexIDClass]) }}" class="form-prevent-multi-submit">
                        <input type="submit" value="@if($element->deleted_at) Cancelled @else Cancel Enrolment @endif" class="btn btn-danger btn-space button-prevent-multi-submit" @if($element->deleted_at) disabled="" @else @endif>
                        {{-- name="deleteTerm" attribute for LimitCancelPeriod middleware --}}
                        <input type="hidden" name="deleteTerm" value="{{ $element->Term }}">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                       {{ method_field('DELETE') }}
                    </form>
                    @endif
                  @endforeach
                </p>
              </div>
      </div>
  </div>
  @else
  <div class="row">
        <div class="col-md-6">
            <div class="card">
                    <div class="card-header bg-info text-center"><strong>Submitted Enrolment Forms for the 
                        @if(empty($next_term->Term_Name))
                        DB NO ENTRY
                        @else
                        {{ $next_term->Term_Name }} {{ $next_term->Comments }}
                        @endif
                        Term
                      </strong>
                      <input id="termIdSubmitted" type="hidden" value="@if(is_null($next_term->Term_Code)) @else {{ $next_term->Term_Code }} @endif">
                    </div>
                        <div class="card-body">
                        @if(count($forms_submitted) > 0)
                          @foreach($forms_submitted as $form)
                            <div class="row">
                            <div class="col-sm-12">
                                @if($form->cancelled_by_student == 1)
                                  <span class="badge badge-danger margin-label">Enrolment Form Cancelled By Student</span>
                                @endif 
                                <h4><strong>Enrolment Form # {{ $form->eform_submit_count }}</strong> @if($form->is_self_pay_form == 1)<span class="badge badge-secondary margin-label">Self-Payment-based Form</span> @endif </h4>
                                <h4><strong>{{ $form->courses->EDescription }}</strong></h4>
                                    @if (\Carbon\Carbon::now() < $next_term->Enrol_Date_End)
                                      @if(is_null($form->deleted_at))
                                      <div class="col-sm-6">
                                        <a href={{ route('student-edit-enrolment-form-view', [ $form->Term, $form->INDEXID, $form->Te_Code ]) }} class="btn btn-sm btn-outline-success btn-block btn-space"> Modify</a>
                                      </div>
                                      @endif    
                                    @endif
                                    
                                    @if(is_null($form->deleted_at))
                                    <div class="col-sm-6">
                                        <a id="modbtn" class="btn btn-sm btn-outline-info btn-block btn-space" data-toggle="modal" href="#modalshow" data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-approval="{{ $form->approval }}" data-formx="{{ $form->eform_submit_count }}" data-mtitle="{{ $form->courses->EDescription }}"><i class="fa fa-eye"></i> View Status</a>
                                    </div> 
                                    @endif
                                    
                                    <div class="col-sm-6">
                                      @component('form.modaldelete')
                                        @slot('course')
                                          {{ $form->courses->Te_Code_New }}
                                        @endslot
                                        @slot('formCount')
                                          {{ $form->eform_submit_count }}
                                        @endslot
                                        @slot('buttonclass')
                                          btn-sm btn-danger btn-block btn-space
                                        @endslot
                                        @slot('buttonlabel')
                                          Cancel Enrolment
                                        @endslot
                                        @slot('classApproval')
                                          stat-{{ $form->approval }}
                                        @endslot
                                        @slot('deleteSet')
                                          @if(isset($form->deleted_at))
                                          delete-is-set
                                          @else
                                          delete-is-not-set
                                          @endif
                                        @endslot
                                        @slot('title')
                                          <span><i class="fa fa-lg fa-warning btn-space"></i>Cancellation Warning</span>
                                        @endslot
                                        @slot('body')
                                          <p>Cancel enrolment for <strong>{{ $form->courses->EDescription }}</strong></p>
                                          <p class="text-danger"><strong>IMPORTANT:</strong> You can be reimbursed or your organization will not be billed <strong><u>only if you cancel your enrolment 4 working days before the start of the term or 2 weeks before the start of Summer courses.</u></strong>  If you cancel your enrolment at any time after the deadline, the fees of the course will not be reimbursed.</p>
                                          <p>If you are a UN staff member, your HR Learning Department will be notified of this cancellation via e-mail.</p>
                                          <p>Please double check your decision. Thank you for your kind attention.</p>
                                        @endslot
                                        @slot('buttonoperation')
                                          <button type="button" class="btn btn-default btn-space" data-dismiss="modal">Back</button>
                                          <form method="POST" action="{{ route('submitted.destroy', [$form->INDEXID, $form->Te_Code, $form->Term, $form->eform_submit_count]) }}" class="form-prevent-multi-submit">
                                              <input type="submit" value="Cancel Enrolment" class="btn btn-danger btn-space button-prevent-multi-submit">
                                              <input type="hidden" name="deleteTerm" value="{{ $form->Term }}">
                                              <input type="hidden" name="_token" value="{{ Session::token() }}">
                                             {{ method_field('DELETE') }}
                                          </form>
                                        @endslot
                                      @endcomponent
                                    </div>
                              </div>
                              </div>
                              <hr>
                          @endforeach 
                        @else
                          <h5>No Forms Submitted</h5>
                        @endif 
                        </div>
            </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-warning text-center"><strong>Placement Test Request Forms for the 
            @if(empty($next_term->Term_Name))
                DB NO ENTRY
              @else
                {{ $next_term->Term_Name }} {{ $next_term->Comments }}
            @endif
                Term</strong>
          </div>
            <div class="card-body">
            @if(count($plforms_submitted) > 0)
              @foreach($plforms_submitted as $plform)
                <div class="row">
                <div class="col-sm-12">

                    @if($plform->cancelled_by_student == 1)
                      <span class="badge badge-danger margin-label">Placement Test Request Cancelled By Student</span> 
                    @endif

                    <h4><strong>Placement Test Request Form # {{ $plform->eform_submit_count }}</strong></h4>
                    <h5>@if($plform->is_self_pay_form == 1)<span class="badge badge-secondary margin-label">Self-Payment-based Form</span> @endif</h5> 
                    <h5>Language: <strong>{{ $plform->languages->name }}</strong></h5>
                    <h5>@if($plform->placementSchedule->is_online == 1)Test Date: Online from <strong>{{ date('d M Y', strtotime($plform->placementSchedule->date_of_plexam)) }}</strong> to <strong>{{ date('d M Y', strtotime($plform->placementSchedule->date_of_plexam_end)) }}</strong> @else Test Date: <strong>{{ date('d M Y', strtotime($plform->placementSchedule->date_of_plexam)) }}</strong> @endif</h5>
                    
                    <p>Organization: @if(is_null($plform->DEPT)) - @elseif($plform->DEPT == 999) SPOUSE @else {{ $plform->DEPT }} @endif
                    </p>

                    <p>
                      HR Staff and Development Section Approval:
                    @if(is_null($plform->is_self_pay_form))
                      @if(in_array($plform->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
                        <span id="status" class="badge badge-info margin-label">
                        N/A - Non-paying organization</span>
                      @else
                        @if(is_null($plform->approval) && is_null($plform->approval_hr))
                        <span id="status" class="badge badge-warning margin-label">
                        Pending Approval</span>
                        @elseif($plform->approval == 0 && (is_null($plform->approval_hr) || isset($plform->approval_hr)))
                        <span id="status" class="badge badge-danger margin-label">
                        N/A - Disapproved by Manager</span>
                        @elseif($plform->approval == 1 && is_null($plform->approval_hr))
                        <span id="status" class="badge badge-warning margin-label">
                        Pending Approval</span>
                        @elseif($plform->approval == 1 && $plform->approval_hr == 1)
                        <span id="status" class="badge badge-success margin-label">
                        Approved</span>
                        @elseif($plform->approval == 1 && $plform->approval_hr == 0)
                        <span id="status" class="badge badge-danger margin-label">
                        Disapproved</span>
                        @endif
                      @endif
                    @else
                    <span id="status" class="badge badge-info margin-label">
                    N/A - Self-Payment</span>
                    @endif
                    </p>

                    <p>
                      Language Secretariat Payment Validation: 
                    @if(is_null($plform->is_self_pay_form))
                    <span id="status" class="badge badge-info margin-label">N/A</span>
                    @else
                      @if($plform->selfpay_approval === 1)
                      <span id="status" class="badge badge-success margin-label">Approved</span>
                      @elseif($plform->selfpay_approval === 2)
                      <span id="status" class="badge badge-warning margin-label">Pending Valid Document</span>
                      @elseif($plform->selfpay_approval === 0)
                      <span id="status" class="badge badge-danger margin-label">Disapproved</span>
                      @else 
                      <span id="status" class="badge badge-info margin-label">Waiting</span>
                      @endif
                    @endif
                    </p>
                    @if (\Carbon\Carbon::now() < $next_term->Enrol_Date_End)
                      @if (is_null($plform->deleted_at))
                      <div class="col-sm-12">
                        <a href={{ route('student-edit-placement-form-view', [ $plform->id ]) }} class="btn btn-sm btn-outline-success btn-block btn-space"> Modify</a>
                      </div>
                      @endif
                    @endif
                    <div class="col-sm-12">
                    <form method="POST" action="{{ route('submittedPlacement.destroy', [$plform->INDEXID, $plform->L, $plform->Term, $plform->eform_submit_count]) }}" class="delete-form prevent-submit-form">
                        <input type="submit" @if (is_null($plform->deleted_at))
                          value="Cancel Placement Test"
                        @else
                          value="Cancelled"
                        @endif  class="btn btn-danger btn-space prevent-submit-button" @if (is_null($plform->deleted_at))
                          
                        @else
                          disabled="" 
                        @endif>
                        <input type="hidden" name="deleteTerm" value="{{ $plform->Term }}">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                       {{ method_field('DELETE') }}
                    </form>
                    </div>
                </div>
                </div>
                <hr>
              @endforeach  
            @else
              <h5>No Forms Submitted</h5>
            @endif                
            </div>
          </div>
        </div>

      </div>
      @endif  
    @endif
    </div>
  </div>
</div>
{{ csrf_field() }}
<div class="modal fade" id="modalshow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body-schedule">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>

<div id="dialog" title="Over Cancellation Deadline" style="display: none">
  <p>Cancellation of enrolment forms has been disabled. Please contact the Language Secretariat if you really want to cancel. NOTE: If you cancel your enrolment at any time after the deadline, the fees of the course will not be reimbursed.</p>
</div>  

@stop 

@section('scripts_code')

<script src="{{ asset('js/submit.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>

<script>
  $(document).ready(function($) {
      $("#loader").fadeOut(800);
      $('.select2-term-single').select2({
        placeholder: "Select Term",
      });
      $( "#dialog" ).dialog({
        autoOpen: false,
        show: {
          effect: "blind",
          duration: 1000
        },
        hide: {
          effect: "explode",
          duration: 1000
        }
      }); 

      $('form.delete-form').on('submit', function() {
        var c = confirm("You are about to cancel a form. Are you sure?");
        if (c == true) {
          $('.prevent-submit-button').attr('disabled', 'true');
        }
        return c; //you can just return c because it will be true or false
      });  
  });
</script>

<script>
  $(document).ready(function () {
    $('a.delete-is-set').removeAttr("href").css("cursor","not-allowed");
    $('a.stat-0').removeAttr("href").css("cursor","not-allowed");
    $('a.stat-0').delay(800).fadeOut('slow', function() {
                      $(this).remove(); 
                      }); 
      var dterm = $('#termIdSubmitted').val(); console.log(dterm)
      $.get("/get-date-ajax", {'term':dterm}, function(data) {
            console.log(data);
            // temporary disable for demo
            if (data === 'disabled') {
              $('a.cancel-btn').removeAttr("href").css("cursor","not-allowed");  
              $('a.cancel-btn').on('click', function () {
                $( "#dialog" ).dialog( "open" );
              });
            }
          });
      $.get("/is-cancelled-ajax", {'term':dterm}, function(data) {
            $.each(data, function (index, value) {               
                $('a.delete-is-set').text('Cancelled').animate({
                  backgroundColor: "#fff", color: "#000"}, 800, function() {
                  /* stuff to do after animation is complete */
                });
            });
          }); 
  });  
</script>

<script>  
  $(document).ready(function () {
    $('#modalshow').on('show.bs.modal', function (event) {
      var link = $(event.relatedTarget); // Link that triggered the modal
      var dtitle = link.data('mtitle');
      var dtecode = link.data('tecode');
      var dterm = link.data('term');
      var dapproval = link.data('approval');
      var dFormCounter = link.data('formx');
      var token = $("input[name='_token']").val();
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('submitted.show') }}', {'tecode':dtecode, 'term':dterm, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
          console.log(data);
          $('.modal-body-schedule').html(data)
      });
    });
  });
</script>
@stop