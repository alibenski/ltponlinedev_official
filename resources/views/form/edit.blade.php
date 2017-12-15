@extends('public')
@section('tabtitle', '| Edit')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        {{ $form->Code }} for {{ $form->INDEXID }}
                            @foreach($schedbycourse as $courses => $collection)
                            <div class="row">
                            <div class="col-sm-12">
                                <p><span for="" class="label label-warning" style="margin-right: 10px;">For Approval</span><strong>{{ $courses }}</strong></p>
                                <ul>
                                    <p><strong>Possible schedule(s) chosen:</strong></p>
                                    @foreach($collection as $schedule)
                                        {{ $schedule->schedule->name }} <br>
                                    @endforeach
                                </ul>
                                <div class="col-sm-8 col-sm-offset-2">
                                    <a href="" class="btn btn-sm btn-danger btn-block">Cancel Enrolment</a>
                                </div>
                            </div>
                            </div>
                            <hr>
                            @endforeach

      </div>
    </div>
  </div>
</div>
@stop 