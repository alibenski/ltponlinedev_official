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
<div class="container">
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
                              {{-- show if course is 1st or 2nd choice --}}
                                {{-- <div>
                                @if( $form->continue_bool == 1 )
                                  <span class="label label-primary margin-label">First Choice
                                @else 
                                  <span class="label label-default margin-label">Second Choice
                                @endif  
                                </div> --}}
                              {{-- show decision labels --}}
                              {{-- <div>
                                  @if($form->is_self_pay_form == 1)
                                    <span id="status" class="label label-success margin-label">
                                  Self Payment --}}
                                  {{--  @elseif(isset($form->deleted_at))
                                    <span id="status" class="label label-danger margin-label">
                                  Cancelled --}}
                                  {{-- @elseif(is_null($form->approval) && is_null($form->approval_hr))
                                    <span id="status" class="label label-warning margin-label">
                                  Pending Approval
                                  @elseif(in_array(Auth::user()->sddextr->DEPT, ["UNOG", "JIU"]) && $form->approval == 1 && is_null($form->approval_hr))
                                    <span id="status" class="label label-success margin-label">
                                  Approved
                                  @elseif($form->approval == 1 && is_null($form->approval_hr))
                                    <span id="status" class="label label-warning margin-label">
                                  Pending Approval
                                  @elseif($form->approval == 1 && $form->approval_hr == 1)
                                    <span id="status" class="label label-success margin-label">
                                  Approved
                                  @elseif($form->approval == 0 && is_null($form->approval_hr))
                                    <span id="status" class="label label-danger margin-label">
                                  Disapproved
                                  @elseif($form->approval == 1 && $form->approval_hr == 0)
                                    <span id="status" class="label label-danger margin-label">
                                  Disapproved
                                  @endif 
                                </div> --}}
                                @if($form->cancelled_by_student == 1)
                                  <span class="label label-danger margin-label">Enrolment Form Cancelled By Student</span>
                                @endif 
                                <h4><strong>Enrolment Form # {{ $form->eform_submit_count }}</strong></h4>
                                <h4><strong>{{ $form->courses->EDescription }}</strong></h4>
                                
                                    <div class="col-sm-6">
                                        <a id="modbtn" class="btn btn-sm btn-info btn-block btn-space" data-toggle="modal" href="#modalshow" data-tecode="{{ $form->Te_Code }}" data-approval="{{ $form->approval }}" data-formx="{{ $form->form_counter }}" data-mtitle="{{ $form->courses->EDescription }}">View Info</a>
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
                                          <form method="POST" action="{{ route('submitted.destroy', [$form->INDEXID, $form->Te_Code, $form->form_counter]) }}">
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
      var dapproval = link.data('approval');
      var dFormCounter = link.data('formx');
      var token = $("input[name='_token']").val();
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('submitted.show') }}', {'tecode':dtecode, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
          console.log(data);
          $('.modal-body-schedule').html(data)
      });
    });
  });
</script>
@stop