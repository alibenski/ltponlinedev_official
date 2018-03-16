@extends('main')
@section('tabtitle', '| Submitted Forms')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
                                @if($form->is_self_pay_form == 1)
                                <p><span id="status" class="label label-success" style="margin-right: 10px;">
                                Self Payment
                                @elseif(is_null($form->approval) && is_null($form->approval_hr))
                                <p><span id="status" class="label label-warning" style="margin-right: 10px;">
                                Pending Approval
                                @elseif(in_array(Auth::user()->sddextr->DEPT, ["UNOG", "JIU"]) && $form->approval == 1 && is_null($form->approval_hr))
                                <p><span id="status" class="label label-success" style="margin-right: 10px;">
                                Approved
                                @elseif($form->approval == 1 && is_null($form->approval_hr))
                                <p><span id="status" class="label label-warning" style="margin-right: 10px;">
                                Pending Approval
                                @elseif($form->approval == 1 && $form->approval_hr == 1)
                                <p><span id="status" class="label label-success" style="margin-right: 10px;">
                                Approved
                                @elseif($form->approval == 0 && is_null($form->approval_hr))
                                <p><span id="status" class="label label-danger" style="margin-right: 10px;">
                                Disapproved
                                @elseif($form->approval == 1 && $form->approval_hr == 0)
                                <p><span id="status" class="label label-danger" style="margin-right: 10px;">
                                Disapproved
                                @endif 
                                </span><strong>{{ $form->courses->EDescription}}</strong></p>
                                    
                                    <div class="col-sm-6">
                                        <a id="modbtn" class="btn btn-sm btn-info btn-block btn-space" data-toggle="modal" href="#modalshow" data-tecode="{{ $form->Te_Code }}" data-mtitle="{{ $form->courses->EDescription }}">View Info</a>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                      @component('form.modaldelete')
                                        @slot('course')
                                          {{ $form->courses->Te_Code_New }}
                                        @endslot
                                        @slot('buttonclass')
                                          btn-sm btn-danger btn-block btn-space
                                        @endslot
                                        @slot('buttonlabel')
                                          Cancel Enrolment
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
                                          <form method="POST" action="{{ route('submitted.destroy', [$form->INDEXID, $form->Te_Code]) }}">
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

<div id="dialog" title="Over Cancellation Deadline">
  <p>Cancellation of enrolment forms has been disabled. Please contact the Language Secretariat if you really want to cancel. NOTE: If you cancel your enrolment at any time after the deadline, the fees of the course will not be reimbursed.</p>
</div>  
@stop 

@section('scripts_code')

<script src="{{ asset('js/submit.js') }}"></script>


<script>
  $(document).ready(function () {
      $.get("/get-date-ajax", function(data) {
            console.log(data);
            if (data === 'disabled') {
              $('a.cancel-btn').removeAttr("href").css("cursor","not-allowed");  
              $('a.cancel-btn').on('click', function () {
                $( "#dialog" ).dialog( "open" );
              });
            }
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
  });  
</script>

<script>  
  $(document).ready(function () {
    $('#modalshow').on('show.bs.modal', function (event) {
      var link = $(event.relatedTarget); // Link that triggered the modal
      var dtitle = link.data('mtitle');
      var dtecode = link.data('tecode');
      var token = $("input[name='_token']").val();
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('submitted.show') }}', {'tecode':dtecode, '_token':token}, function(data) {
          console.log(data);
          $('.modal-body-schedule').html(data)
      });
    });
  });
</script>

@stop