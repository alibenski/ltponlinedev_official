@extends('main')
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

      </div>
    </div>
  </div>
</div>
@stop 