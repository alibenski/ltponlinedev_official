@extends('main')
@section('tabtitle', '| Submitted Forms')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
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
                                <p><span id="status" class="label label-warning" style="margin-right: 10px;">
                                @if(is_null($form->approval) && is_null($form->approval_hr))
                                Pending Approval
                                @elseif(in_array(Auth::user()->sddextr->DEPT, ["UNOG", "JIU"]) && $form->approval == 1 && is_null($form->approval_hr))
                                Approved
                                @elseif($form->approval == 1 && is_null($form->approval_hr))
                                Pending Approval
                                @elseif($form->approval == 1 && $form->approval_hr == 1)
                                Approved
                                @elseif($form->approval == 0 && is_null($form->approval_hr))
                                Disapproved
                                @elseif($form->approval == 0 && $form->approval_hr == 0)
                                Disapproved
                                @endif
                                </span><strong>{{ $form->courses->EDescription}}</strong></p>
                                    <div class="col-sm-6">
                                        <a id="modbtn" class="btn btn-sm btn-info btn-block btn-space" data-toggle="modal" href="#modalshow" data-tecode="{{ $form->Te_Code }}" data-mtitle="{{ $form->courses->EDescription }}">View Info</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <form method="POST" action="{{ route('submitted.destroy', [$form->INDEXID, $form->Te_Code]) }}">
                                            <input type="submit" value="Cancel Enrolment" class="btn btn-sm btn-danger btn-block btn-space">
                                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                                           {{ method_field('DELETE') }}
                                        </form>﻿
                                    </div>
                            </div>
                            </div>
                            <hr>
                            @endforeach
                            <div class="col-sm-2">
                            <!--<a href="{{ route('noform.edit', Crypt::encrypt($form->id)) }}" class="btn btn-default btn-sm">View</a>-->
                            </div>    
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
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
@stop 

@section('scripts_code')
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
              $('.modal-body').html(data)
          });
        });
  });
</script>

@stop