@extends('main')
@section('tabtitle', "| Edit Course $course->name")
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
	<div class="row">
<form method="POST" action="{{ route('courses.update', $course->id) }}">
      <div class="form-group">
        <label for="name">Course Name:</label>
        <textarea type="text" class="form-control input-lg" id="name" name="name" rows="1" style="resize:none;">{{ $course->name }}</textarea>
      </div>
      
      <div class="form-group">
        <label for="name">Course Schedule:</label>
        <div class="tags">
            @if(empty($exists))
            <span class="label label-danger">none</span>
            @else
            <!-- Variable course refers to schedule function defined as variable schedule -->
            @foreach($course->schedule as $schedule)
                <span class="label label-default">{{ $schedule->name }}</span>
            @endforeach
            @endif
            
        </div>
      </div>
    <div class="col-md-4">
      <div class="well">
        <dl class="dl-horizontal">
          <dt>Created at:</dt>
          <dd>{{ date('M j, Y h:i:sa', strtotime($course->created_at)) }}</dd>
        </dl>

        <dl class="dl-horizontal">
          <dt>Last updated:</dt>
          <dd>{{ date('M j, Y h:i:sa', strtotime($course->updated_at)) }}</dd>
        </dl>
        <hr>
        <div class="row">
          <div class="col-sm-6">
            <a href="{{ route('courses.index') }}" class="btn btn-danger btn-block">Back</a>
          </div>
          <div class="col-sm-6">
              <button type="submit" class="btn btn-success btn-block">Save</button>
              <input type="hidden" name="_token" value="{{ Session::token() }}">
              {{ method_field('PUT') }}
            </form>﻿
	</div>
</div>
@endsection