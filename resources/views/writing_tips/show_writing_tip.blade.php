@extends('writing_tips.template_writing_tip')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<div class='col-md-12'>
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>
                         ID # {{$writingTip->id}}
                      </h3>

                      <p>{{$writingTip->languages->name}} Writing Tip Entry</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-edit"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <a href="{{ route('writing-tips.index') }}" class="btn btn-default btn-space"><i class="fa fa-arrow-left"></i> Back to Writing Tip Entries</a>
                <a href="{{ route('writing-tips.edit', $writingTip->id) }}" class="btn btn-warning btn-space"><i class="fa fa-pencil"></i> Edit</a>
                <a class="btn btn-primary" data-toggle="modal" href='#modal-id'><i class="fa fa-list"></i> View Mailing List</a>
                <a class="btn btn-info open-modal-selective-send" data-toggle="modal" href='#modal-id-selective'><i class="fa fa-check-square"></i> Select and Send</a>
                <a href="{{ route('send-writing-tip-email', $writingTip->id) }}" class="btn btn-success btn-space"><i class="fa fa-envelope"></i> Send to Mailing List</a>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </div>
            
        </div>
    </div>
    

    <h3 class="alert text-center" style="font-weight: 800;">SUBJECT: Writing Tip - {{ $writingTip->subject }}</h3>


    <div class="row">
        @include('emails.writingTip')
    </div>
</div>

<div class="modal fade" id="modal-id">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Sending to {{count($drupalEmailRecords)}} email addresses</h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-sm-12">
                    <ul>
                        @foreach ($drupalEmailRecords as $element)
                            <div class="col-sm-4">
                                <li>{{ $element->data }}</li>
                            </div>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-id-selective">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="preloader2"><h4 class="text-center"><strong>Sending Email...</strong></h4></div>
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Select email addresses and Send</h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-sm-12">
                    @foreach ($drupalEmailRecords as $key => $element)
                        <div class="col-sm-4">
                            <label for="box_value_{{ $key }}">
                                <input id="box_value_{{ $key }}" type="checkbox" name="email_add" class="sub_chk" multiple="multiple" value="{{ $element->data }}" /> {{ $element->data }} 
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success selective-send"><i class="fa fa-envelope"></i> Send</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('java_script')
<script>
    $(document).ready(function() {

        $('a.open-modal-selective-send').click(function() {
            $(".sub_chk").prop('checked',false); 
            $('div.preloader2').addClass('hidden');
            $('button.selective-send').removeAttr('disabled');
        });

        $('#modal-id-selective').on('click', '.selective-send', function() {
          
          var allVals = [];  
          $(".sub_chk:checked").each(function() {  
              allVals.push($(this).val());
          });  

          var join_selected_values = allVals.join(",");
          var token = $("input[name='_token']").val();
          
          if(allVals.length <=0)  
          {  
            alert("Please select at least 1 student.");  

          }  else {  
            $('button.selective-send').attr('disabled', 'true');
            $('div.preloader2').removeClass('hidden');

            $.ajax({
                url: '{{ route('selective-send-writing-tip-email', $writingTip->id) }}',
                type: 'POST',
                data: {join_selected_values: join_selected_values, _token:token},
            })
            .done(function(data) {
                console.log(data);
                $('#modal-id-selective').modal('hide');
            })
            .fail(function() {
                console.log("error");
                alert('An error occured. Please contact the system administrator. ');
                window.location.reload();
            })
            .always(function() {
                console.log("complete");
            });
            
          }
        });

    });
</script>
@stop