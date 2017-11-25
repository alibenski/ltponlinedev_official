@extends('main')
@section('tabtitle', '| Approval Page')
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

        <div class="panel-heading">Approval Page Enrolment Form for Semester: <strong>{{ $next_term }}</strong></div>
          <div class="panel-body">
            <form method="PUT" action="" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Staff Member Name:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="" class="form-control"  type="text" value="{{$input_staff->users->name}}" readonly>                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-md-3 control-label">Staff Member's Email Address:</label>
                    
                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="email" placeholder="" class="form-control"  type="text" value="{{$input_staff->users->email}}"readonly="">                                    
                        </div>
                    </div>
                </div>
             
                <div class="row">
                  <div class="col-md-12">
                    <table class="table">
                      <thead>
                        <th>Title A</th>
                        <th>Title B</th>
                        <th>Title C</th>
                        <th>Title D</th>
                        <th>Approve?</th>
                      </thead>

                      <tbody>
                        @foreach($input_course as $course)                         
                          <tr>
                            <th>{{ $course->CodeIndexID }}</th>
                            <td>{{ $course->courses->Description }}</td>
                            <td>{{ $course->languages->name }}</td>
                            <td>
                              @if(empty($course->schedule))
                              null
                              @else
                              {{ $course->schedule->name }}
                              @endif
                            </td>
                            <td><a href="" class="btn btn-primary btn-sm">Yes</a></td>
                          </tr>
                        @endforeach

                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- MAKE A DECISION SECTION -->

                <div class="form-group">
                    <label class="col-md-4 control-label">Approve the above course schedule(s)?</label>

                      <div class="col-md-2">
                                <input id="decision1" name="decision" class="with-font dyes" type="radio" value="yes" >
                                <label for="decision1" class="form-control-static">YES</label>
                      </div>

                      <div class="col-md-2">
                                <input id="decision2" name="decision" class="with-font dno" type="radio" value="no">
                                <label for="decision2" class="form-control-static">NO</label>
                      </div>
                </div>


                <div class="col-sm-offset-5">
                  <button type="submit" class="btn btn-success button-prevent-multi-submit">Submit Decision</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop 