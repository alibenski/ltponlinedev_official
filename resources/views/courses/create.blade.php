@extends('main')
@section('tabtitle', '| Create New Course')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <h1>Create New Course</h1>
    <hr>

    <form method="POST" action="{{ route('courses.store') }}">
      <div class="form-group">
        <label name="name">Course name:</label>
        <input id="name" name="name" class="form-control">
      </div>
      
      <div class="form-group">
          <label name="language_id" class="col-md-4 control-label">Select the schedules for this course:</label>
          <select class="form-control select2-multi" name="schedules[]" multiple="multiple">
              @foreach ($schedules as $id => $name)
                <option value="{{ $id }}"> {{ $name }}</option>
              @endforeach
          </select>
      </div>
      
        <input type="submit" value="Create Course" class="btn btn-success btn-lg btn-block">
        <input type="hidden" name="_token" value="{{ Session::token() }}">   
    </form>
  </div>
</div>﻿
@endsection

@section('scripts_code')
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <script type="text/javascript">$(".select2-multi").select2(); </script>
@stop