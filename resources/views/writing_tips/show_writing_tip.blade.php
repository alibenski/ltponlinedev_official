@extends('shared_template')

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
                <a href="{{ route('send-writing-tip-email', $writingTip->id) }}" class="btn btn-success btn-space"><i class="fa fa-envelope"></i> Send to Mailing List</a>
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
            <div class="modal-header">
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

@stop

@section('java_script')
<script>

</script>
@stop