@extends('admin.admin')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

  <div class="col-md-12"><h1 class="alert text-center"><i class="icon fa fa-plus"></i>Create New Schedule</h1></div>
  
    <form class="form-horizontal col-md-12" method="POST" action="{{ route('store-non-standard-schedule') }}" autocomplete="off">
              {{ csrf_field() }}

            <div class="col-md-12 form-group text-center">
              <h4 for="">Choose Type of Schedule:</h4>
              <button type="button" class="btn btn-default btn-lg btn-space standard">Standard</button>
              <button type="button" class="btn btn-default btn-lg btn-space non-standard">Non-Standard</button>
            </div>
              
            <div class="form-sched non-standard-schedule hidden">
              <div class="form-group">
                <label for="sched_name" class="control-label col-md-4">Non-standard Schedule:</label>
                <input type="text" class="col-md-6 form-control-static" id="sched_name" name="sched_name" rows="1" style="resize:none;" placeholder="Enter the non-standard schedule format, e.g. Online, 1 & 15 Feb, 1, 15 & 29 March, etc."></input>
                <strong><small class="col-md-8 col-md-offset-4 text-danger">Use only for non-standard schedule format, e.g. "Online", "1 & 15 Feb, 1, 15 & 29 March", etc.</small></strong>
              </div>

              <div class="form-group">
                  <label name="begin_time" class="col-md-4 control-label">Begin Time: </label>
                  <select class="col-md-6 form-control-static" name="begin_time">
                      <option value="">Select Begin Time</option>
                      @foreach ($btimes as $id => $name)
                          <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group">
                  <label name="end_time" class="col-md-4 control-label">End Time: </label>
                  <select class="col-md-6 form-control-static" name="end_time">
                      <option value="">Select End Time</option>
                      @foreach ($etimes as $id => $name)
                          <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                      @endforeach
                  </select>
              </div>
              
              <div class="container create-btn-grp">
                <div class="col-md-4 col-md-offset-4">
                  <div class="row">
                    <div class="col-sm-6">
                      <a href="{{ route('schedules.index') }}" class="btn btn-danger btn-block">Back</a>
                    </div>
                    <div class="col-sm-6">
                      <input type="submit" value="Create" class="btn btn-success btn-block">
                      <input type="hidden" name="_token" value="{{ Session::token() }}"> 
                    </div>
                  </div>
                </div> 
              </div>
            </div>

    </form>

    <form class="form-horizontal col-md-12" method="POST" action="{{ route('schedules.store') }}" autocomplete="off">
              {{ csrf_field() }}
            <div class="form-sched standard-schedule hidden">
              <!-- array checkboxes -->
              <div class="form-group">
                <label for="sched_name" class="control-label col-md-4">Standard Schedule:</label>
                <span type="text" class="col-md-6 form-control-static">Choose the appropriate day(s) with the corresponding begin and end times.</span>
              </div>
              <div class="form-group">
                  <label class="col-md-4 control-label">Choose Days:</label>
                  <div class="col-md-4">
                    @foreach ($days as $id => $name)
                    <div class="input-group"> 
                      <span class="input-group-addon">       
                        <input type="checkbox" name="begin_day[]" class="add-filter" value="{{ $id }}" autocomplete="off">                 
                      </span>
                        <label type="text" class="form-control">{{ $name }}</label>
                    </div>
                    @endforeach
                  </div>
              </div>

              <div class="form-group">
                  <label name="begin_time" class="col-md-4 control-label">Begin Time: </label>
                  <select class="col-md-6 form-control-static" name="begin_time">
                      <option value="">Select Begin Time</option>
                      @foreach ($btimes as $id => $name)
                          <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group">
                  <label name="end_time" class="col-md-4 control-label">End Time: </label>
                  <select class="col-md-6 form-control-static" name="end_time">
                      <option value="">Select End Time</option>
                      @foreach ($etimes as $id => $name)
                          <option value="{{ $id }}"> {{ date('h:i:sa', strtotime($name)) }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="container create-btn-grp">
                <div class="col-md-4 col-md-offset-4">
                  <div class="row">
                    <div class="col-sm-6">
                      <a href="{{ route('schedules.index') }}" class="btn btn-danger btn-block">Back</a>
                    </div>
                    <div class="col-sm-6">
                      <input type="submit" value="Create" class="btn btn-success btn-block">
                      <input type="hidden" name="_token" value="{{ Session::token() }}"> 
                    </div>
                  </div>
                </div> 
              </div>
            </div>

    </form>﻿

@endsection

@section('java_script')
<script>
  $(document).ready(function() {
    $( "button.standard" ).click(function() {
      $(this).addClass('btn-success');
      $("button.non-standard").removeClass('btn-success');
      
      $( "div.form-sched.standard-schedule" ).removeClass('hidden');
      $( "div.form-sched.non-standard-schedule" ).addClass('hidden');

    });

    $( "button.non-standard" ).click(function() {
      $(this).addClass('btn-success');
      $("button.standard").removeClass('btn-success');
      
      $( "div.form-sched.non-standard-schedule" ).removeClass('hidden');
      $( "div.form-sched.standard-schedule" ).addClass('hidden');

    });

  });
</script>
@stop