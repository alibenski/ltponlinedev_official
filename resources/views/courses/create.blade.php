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
                  <label class="col-md-10 control-label">Language: </label>
                  <div class="row">
                    <div class="col-md-10">
                      <div class="input-group">
                        @foreach ($languages as $id => $name)
                          <span class="input-group-addon">       
                            <input type="radio" name="L" value="{{ $id }}" >                 
                          </span>
                            <input type="text" class="form-control" value="{{ $name }}" readonly="">
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                    <label name="Description" class="col-md-10 control-label">New Course Name: </label>
                    <input id="Description" name="Description" class="form-control">
                </div>

                <div class="form-group">
                    <label name="Te_Code" class="col-md-10 control-label">New Course Code: </label>
                    <input id="Te_Code" name="Te_Code" class="form-control">
                </div>

                <div class="row">
                    <div class="col-sm-4 col-md-offset-2">
                      <a href="{{ route('courses.index') }}" class="btn btn-danger btn-block">Back</a>
                    </div>
                  <div class="col-sm-4">
                     <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save Course</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                  </div>
                </div>
    </form>
  </div>
</div>﻿
@endsection

@section('scripts_code')

  <script src="{{ asset('js/submit.js') }}"></script>      

@stop
