@extends('main')
@section('tabtitle', "| Edit Schedule")
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
    <form class="well form-horizontal" method="POST" action="{{ route('schedules.update', $schedule->id) }}">
          <div class="form-group">
            <label for="sched_name" class="control-label col-md-4">Schedule Description:</label>
            <textarea type="text" class="col-md-6 form-control-static" id="sched_name" name="sched_name" rows="1" style="resize:none;" readonly>{{ $schedule->name }}</textarea>
          </div>

              <div class="form-group">
                  <label name="begin_day" class="col-md-4 control-label">Begin Day: </label>
                  <select class="col-md-6 form-control-static" name="begin_day">
                      <option value="">Select Begin Day</option>
                      @foreach ($days as $id => $name)
                          <option value="{{ $id }}"> {{ $name }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="form-group">
                  <label name="end_day" class="col-md-4 control-label">End Day: </label>
                  <select class="col-md-6 form-control-static" name="end_day">
                      <option value="">Select End Day</option>
                      @foreach ($days as $id => $name)
                          <option value="{{ $id }}"> {{ $name }}</option>
                      @endforeach
                  </select>
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
                <dl class="dl-horizontal">
                  <dt>Created at:</dt>
                  <dd>{{ date('M j, Y h:i:sa', strtotime($schedule->created_at)) }}</dd>
                </dl>

                <dl class="dl-horizontal">
                  <dt>Last updated:</dt>
                  <dd>{{ date('M j, Y h:i:sa', strtotime($schedule->updated_at)) }}</dd>
                </dl>
                <hr>
                <div class="row">
                  <div class="col-sm-6">
                    <a href="{{ route('schedules.index') }}" class="btn btn-danger btn-block">Back</a>
                  </div>
                  <div class="col-sm-6">
                      <button type="submit" class="btn btn-success btn-block">Save</button>
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                      {{ method_field('PUT') }}
                </div>
            </div>
    </form>﻿
</div>
</div>
</div>
@endsection