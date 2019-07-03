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
                <a href="" class="btn btn-success btn-space"><i class="fa fa-envelope"></i> Send to Mailing List</a>
            </div>
            
        </div>
    </div>


    

    <h3 class="alert text-center">SUBJECT: {{ $writingTip->subject }}</h3>


    <div class="row">
        @include('emails.writingTip')
    </div>
</div>

@stop

@section('java_script')
<script>

</script>
@stop