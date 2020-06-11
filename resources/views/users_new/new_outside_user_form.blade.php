@extends('layouts.app')
@section('customcss')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop
@section('content')
@if (Session::has('warning')) 
    <div class="alert alert-warning alert-block text-center" role="alert">
        <strong>Note: </strong> {{ Session::get('warning') }}
    </div>
@endif
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">External Registeration</div>

                <div class="panel-body">
                    <form class="form-horizontal form-prevent-multi-submit" enctype="multipart/form-data" method="POST" action="{{ route('post-new-outside-user-form') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('indexno') ? ' has-error' : '' }}">
                            <label for="indexno" class="col-md-4 control-label">Index # <span class="small text-danger"></span></label>

                            <div class="col-md-6">
                                <input id="indexno" type="text" class="form-control" name="indexno" value="{{ old('indexno') }}" autofocus>

                                @if ($errors->has('indexno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('indexno') }}</strong>
                                    </span>
                                @endif
                                <p class="small text-danger"><strong>Please delete trailing zeroes if you have an index number which is less than 8 digits e.g. 00012345 -> 12345</strong></p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="gender" class="col-md-4 control-label"><span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            <div class="col-md-6 ">
                                <p class="form-control-static">required field</p>
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('contractfile') ? ' has-error' : '' }}">
                            <label for="contractfile" class="col-md-4 control-label">Copy of badge ID / Carte de légitimation <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            <div class="col-md-6">
                            <input name="contractfile" type="file" class="col-md-12 form-control-static" required="required">
                                @if ($errors->has('contractfile'))
                                    <span class="alert alert-danger help-block">
                                        <strong>{{ $errors->first('contractfile') }}</strong>
                                    </span>
                                @endif
                                <p class="small text-danger"><strong>File size must be less than 8MB</strong></p>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label for="gender" class="col-md-4 control-label">Gender <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            <div class="col-md-6">
                            <div class="dropdown">
                                <select class="col-md-8 form-control select2-basic-single" style="width: 100%;" name="gender" autocomplete="off" required="">
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
                        
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Title <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            <div class="col-md-6">
                            <div class="dropdown">
                                <select class="col-md-8 form-control select2-basic-single" style="width: 100%;" name="title" autocomplete="off" >
                                    <option value="">--- Please Select ---</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mr.">Mr.</option>
                                </select>
                            </div>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('profile') ? ' has-error' : '' }}">
                            <label for="profile" class="col-md-4 control-label">Profile <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            <div class="col-md-6">
                            <div class="dropdown">
                                <select class="col-md-8 form-control select2-basic-single" style="width: 100%;" name="profile" autocomplete="off" required="">
                                    <option value="">--- Please Select ---</option>
                                    <option value="STF">Staff Member</option>
                                    <option value="INT">Intern</option>
                                    <option value="CON">Consultant</option>
                                    <option value="WAE">When Actually Employed</option>
                                    <option value="JPO">JPO</option>
                                    <option value="MSU">Staff of Permanent Mission</option>
                                    <option value="SPOUSE">Spouse of Staff from UN or Mission</option>
                                    <option value="RET">Retired UN Staff Member</option>
                                    <option value="SERV">Staff of Service Organizations in the Palais</option>
                                    <option value="PRESS">Staff of UN-accredited NGO's and Press Corps</option>
                                </select>
                            </div>

                                @if ($errors->has('profile'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('profile') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('nameLast') ? ' has-error' : '' }}">
                            <label for="nameLast" class="col-md-4 control-label">Last name <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

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
                            <label for="nameFirst" class="col-md-4 control-label">First name <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

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
                            <label for="email" class="col-md-4 control-label">Professional email address <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

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
                            <label for="org" class="col-md-4 control-label">Organization <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                {{-- <input id="org" type="text" class="form-control" name="org" value="{{ old('org') }}" required autofocus> --}}

                            <div class="dropdown">
                              <select class="form-control select2-basic-single" style="width: 100%;" name="org" autocomplete="off" required>
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
                            <label for="contact_num" class="col-md-4 control-label">Contact number <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                <input id="contact_num" type="text" class="form-control" name="contact_num" value="{{ old('contact_num') }}" required autofocus>

                                @if ($errors->has('contact_num'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_num') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                            <label for="dob" class="col-md-4 control-label">Date of birth <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dob">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                <input type="hidden" name="dob" id="dob" value="" required=""/>

                                @if ($errors->has('dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="form-group{{ $errors->has('cat') ? ' has-error' : '' }}">
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
                                </div> --}}

                                {{-- <input id="cat" type="text" class="form-control" name="cat" value="{{ old('cat') }}" required autofocus> --}}

                                {{-- @if ($errors->has('cat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cat') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        {{-- <div class="form-group{{ $errors->has('student_cat') ? ' has-error' : '' }}">
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
                                </div> --}}

                                {{-- <input id="student_cat" type="text" class="form-control" name="student_cat" value="{{ old('student_cat') }}" required autofocus> --}}

                                {{-- @if ($errors->has('student_cat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('student_cat') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

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
@section('java_script')
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script>
  $(document).ready(function() {
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 4,
        forceParse: 0,
        showMeridian: 1,
        minView: 2
    });
  });
</script>
@stop