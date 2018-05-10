@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('newuser.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('indexno') ? ' has-error' : '' }}">
                            <label for="indexno" class="col-md-4 control-label">Index # <span class="small text-danger">(Leave blank if you are not in the Umoja system)</span></label>

                            <div class="col-md-6">
                                <input id="indexno" type="text" class="form-control" name="indexno" value="{{ old('indexno') }}" autofocus>

                                @if ($errors->has('indexno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('indexno') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label for="gender" class="col-md-4 control-label">Gender</label>
                            <div class="col-md-6">
                            <div class="dropdown">
                                <select class="col-md-8 form-control select2-basic-single" style="width: 100%;" name="gender" autocomplete="off" >
                                    <option value="">--- Please Select ---</option>
                                    <option value="F">Female</option>
                                    <option value="M">Male</option>
                                </select>
                            </div>

                                {{-- <input id="gender" type="text" class="form-control" name="gender" value="{{ old('gender') }}" required autofocus> --}}

                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('nameLast') ? ' has-error' : '' }}">
                            <label for="nameLast" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="nameLast" type="text" class="form-control" name="nameLast" value="{{ old('nameLast') }}" required autofocus>

                                @if ($errors->has('nameLast'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nameLast') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('nameFirst') ? ' has-error' : '' }}">
                            <label for="nameFirst" class="col-md-4 control-label">First Name</label>

                            <div class="col-md-6">
                                <input id="nameFirst" type="text" class="form-control" name="nameFirst" value="{{ old('nameFirst') }}" required autofocus>

                                @if ($errors->has('nameFirst'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nameFirst') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('org') ? ' has-error' : '' }}">
                            <label for="org" class="col-md-4 control-label">Organization</label>

                            <div class="col-md-6">
                                {{-- <input id="org" type="text" class="form-control" name="org" value="{{ old('org') }}" required autofocus> --}}

                            <div class="dropdown">
                              <select class="form-control select2-basic-single" style="width: 100%;" name="org" autocomplete="off" >
                                  <option value="">--- Please Select Organization ---</option>
                                      @if(!empty($org))
                                        @foreach($org as $value)
                                          <option class="wx" value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{$value['Org Full Name']}}</option>
                                        @endforeach
                                      @endif
                              </select>
                            </div>

                                @if ($errors->has('org'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('org') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('contact_num') ? ' has-error' : '' }}">
                            <label for="contact_num" class="col-md-4 control-label">Contact Number</label>

                            <div class="col-md-6">
                                <input id="contact_num" type="text" class="form-control" name="contact_num" value="{{ old('contact_num') }}" required autofocus>

                                @if ($errors->has('contact_num'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_num') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cat') ? ' has-error' : '' }}">
                            <label for="cat" class="col-md-4 control-label">Category</label>
                            <div class="col-md-6">
                                <div class="dropdown">
                                    <select class="col-md-8 form-control select2-basic-single" style="width: 100%;" name="cat" autocomplete="off" >
                                        <option value="">--- Please Select Category ---</option>
                                            @if(!empty($cat))
                                              @foreach($cat as $key => $value)
                                                <option class="col-md-8 wx" value="{{ $key }}">{{ $value}}</option>
                                              @endforeach
                                            @endif
                                    </select>
                                </div>

                                {{-- <input id="cat" type="text" class="form-control" name="cat" value="{{ old('cat') }}" required autofocus> --}}

                                @if ($errors->has('cat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cat') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('student_cat') ? ' has-error' : '' }}">
                            <label for="student_cat" class="col-md-4 control-label">Student Status</label>
                            <div class="col-md-6">
                                <div class="dropdown">
                                    <select class="col-md-8 form-control select2-basic-single" style="width: 100%;" name="student_cat" autocomplete="off" >
                                        <option value="">--- Please Select Status ---</option>
                                            @if(!empty($student_status))
                                              @foreach($student_status as $key => $value)
                                                <option class="col-md-8 wx" value="{{ $key }}">{{ $value}}</option>
                                              @endforeach
                                            @endif
                                    </select>
                                </div>

                                {{-- <input id="student_cat" type="text" class="form-control" name="student_cat" value="{{ old('student_cat') }}" required autofocus> --}}

                                @if ($errors->has('student_cat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('student_cat') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Captcha</label>
                            <div class="col-md-6">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}

                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary button-prevent-multi-submit">
                                    Register
                                </button>
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
