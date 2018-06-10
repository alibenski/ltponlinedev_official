@extends('main')
@section('tabtitle', '| Submitted Forms')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet">
@stop
@section('content')
<div id="loader">
</div>
<div class="container">
  <div class="row">
        <div class="col-md-6">
            <div class="panel panel-info">
                    <div class="panel-heading"><strong>Submitted Enrolment Forms for the 
                        @if(empty($next_term->Term_Name))
                        DB NO ENTRY
                        @else
                        {{ $next_term->Term_Name }} 
                        @endif
                        Term
                      </strong>
                    </div>
                        <div class="panel-body">
                          @foreach($forms_submitted as $form)
                            <div class="row">
                            <div class="col-sm-12">
                                @if($form->cancelled_by_student == 1)
                                  <span class="label label-danger margin-label">Enrolment Form Cancelled By Student</span>
                                @endif 
                                <h4><strong>Enrolment Form # {{ $form->eform_submit_count }}</strong> @if($form->is_self_pay_form == 1)<span class="label label-default margin-label">Self Payment-based Form</span> @endif </h4>
                                <h4><strong>{{ $form->courses->EDescription }}</strong></h4>
                                
                                    <div class="col-sm-6">
                                        <a id="modbtn" class="btn btn-sm btn-info btn-block btn-space" data-toggle="modal" href="#modalshow" data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-approval="{{ $form->approval }}" data-formx="{{ $form->form_counter }}" data-mtitle="{{ $form->courses->EDescription }}">View Info</a>
                                    </div> 
                                    
                                    <div class="col-sm-6">
                                      @component('form.modaldelete')
                                        @slot('course')
                                          {{ $form->courses->Te_Code_New }}
                                        @endslot
                                        @slot('formCount')
                                          {{ $form->form_counter }}
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
                                          <p>If you are a UN staff member, your Manager and/or HR Learning Department will be notified of this cancellation via e-mail.</p>
                                          <p>Please double check your decision. Thank you for your kind attention.</p>
                                        @endslot
                                        @slot('buttonoperation')
                                          <button type="button" class="btn btn-default btn-space" data-dismiss="modal">Back</button>
                                          <form method="POST" action="{{ route('submitted.destroy', [$form->INDEXID, $form->Te_Code, $form->Term, $form->form_counter]) }}">
                                              <input type="submit" value="Cancel Enrolment" class="btn btn-danger btn-space">
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
                        </div>
            </div>
        </div>
        
        <div class="col-md-6">
          <div class="panel panel-warning">
            <div class="panel-heading"><strong>Placement Test Request Forms</strong></div>
            <div class="panel-body">
              @foreach($plforms_submitted as $plform)
                <div class="row">
                <div class="col-sm-12">
                    @if($plform->cancelled_by_student == 1)
                      <span class="label label-danger margin-label">Placement Test Request Cancelled By Student</span>
                    @endif 
                    <h4><strong>Placement Test Request Form # {{ $plform->eform_submit_count }}</strong></h4>
                    <h5>@if($plform->is_self_pay_form == 1)<span class="label label-default margin-label">Self Payment-based Form</span> @endif</h5> 
                    <h5>Language: <strong>{{ $plform->languages->name }}</strong></h5>
                    <h5>@if($plform->L == 'F')Test Date: Online from <strong>{{ date('d M Y', strtotime($plform->placementSchedule->date_of_plexam)) }}</strong> to <strong>{{ date('d M Y', strtotime($plform->placementSchedule->date_of_plexam_end)) }}</strong> @else Test Date: <strong>{{ date('d M Y', strtotime($plform->placementSchedule->date_of_plexam)) }}</strong> @endif</h5>
                    <form method="POST" action="{{ route('submittedPlacement.destroy', [$plform->INDEXID, $plform->L, $plform->Term, $plform->eform_submit_count]) }}">
                        <input type="submit" @if (is_null($plform->deleted_at))
                          value="Cancel Placement Test"
                        @else
                          value="Cancelled"
                        @endif  class="btn btn-danger btn-space" @if (is_null($plform->deleted_at))
                          
                        @else
                          disabled="" 
                        @endif>
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                       {{ method_field('DELETE') }}
                    </form>
                </div>
                </div>
                <hr>
              @endforeach                  
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
{{ csrf_field() }}
<div class="modal fade" id="modalshow">
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

<div id="dialog" title="Over Cancellation Deadline" style="display: none">
  <p>Cancellation of enrolment forms has been disabled. Please contact the Language Secretariat if you really want to cancel. NOTE: If you cancel your enrolment at any time after the deadline, the fees of the course will not be reimbursed.</p>
</div>  

@stop 

@section('scripts_code')

<script src="{{ asset('js/submit.js') }}"></script>

<script>
 $(window).load(function(){
 $("#loader").fadeOut(800);
 });
</script>

<script>
  $(document).ready(function($) {
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
  });
</script>

<script>
  $(document).ready(function () {
    $('a.delete-is-set').removeAttr("href").css("cursor","not-allowed");
    $('a.stat-0').removeAttr("href").css("cursor","not-allowed");
    $('a.stat-0').delay(800).fadeOut('slow', function() {
                      $(this).remove(); 
                      }); 
      $.get("/get-date-ajax", function(data) {
            console.log(data);
            // temporary disable for demo
            // if (data === 'disabled') {
            //   $('a.cancel-btn').removeAttr("href").css("cursor","not-allowed");  
            //   $('a.cancel-btn').on('click', function () {
            //     $( "#dialog" ).dialog( "open" );
            //   });
            // }
          });   
      $.get("/is-cancelled-ajax", function(data) {
            $.each(data, function (index, value) {                
                $('a.delete-is-set').text('Cancelled').animate({
                  backgroundColor: "#fff", color: "#000"}, 800, function() {
                  /* stuff to do after animation is complete */
                });;
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