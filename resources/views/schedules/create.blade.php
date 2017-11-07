@extends('main')
@section('tabtitle', "| Create New Schedule")
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
    <form class="well form-horizontal" method="POST" action="{{ route('schedules.store') }}">
              <div class="form-group">
                <label for="sched_name" class="control-label col-md-4">Schedule Description:</label>
                <textarea type="text" class="col-md-6 form-control-static" id="sched_name" name="sched_name" rows="1" style="resize:none; border-color: red;" readonly>Description will automatically be generated based on the choices below.</textarea>
              </div>
                <!-- array checkboxes -->
                <div class="form-group">
                    <label class="col-md-4 control-label">Choose Days:</label>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label>
                              @foreach ($days as $id => $name)
                                <input type="checkbox" name="begin_day[]" value="{{ $id }}" /> {{ $name }}
                                <br>
                              @endforeach
                            </label>
                        </div>
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

            <div class="container">
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
    </form>﻿
</div>
</div>
</div>
@endsection