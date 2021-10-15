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
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal form-prevent-multi-submit" method="POST" action="{{ route('post-new-new-user') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('indexno') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('gender') ? 'is-invalid' : '' }}">
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
                        
                        <div class="form-group{{ $errors->has('title') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('profile') ? 'is-invalid' : '' }}">
                            <label for="profile" class="col-md-4 control-label">Profile <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            @include('ajax-profile-select')
                        </div>

                        <div class="form-group{{ $errors->has('nameLast') ? 'is-invalid' : '' }}">
                            <label for="nameLast" class="col-md-4 control-label">Last Name <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                <input id="nameLast" type="text" class="form-control" name="nameLast" value="{{ old('nameLast') }}" required autofocus>

                                @if ($errors->has('nameLast'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nameLast') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('nameFirst') ? 'is-invalid' : '' }}">
                            <label for="nameFirst" class="col-md-4 control-label">First Name <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                <input id="nameFirst" type="text" class="form-control" name="nameFirst" value="{{ old('nameFirst') }}" required autofocus>

                                @if ($errors->has('nameFirst'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nameFirst') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email address <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control email-input" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('org') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('contact_num') ? 'is-invalid' : '' }}">
                            <label for="contact_num" class="col-md-4 control-label">Contact Number <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                <input id="contact_num" type="text" class="form-control" name="contact_num" value="{{ old('contact_num') }}" required autofocus>

                                @if ($errors->has('contact_num'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_num') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('dob') ? 'is-invalid' : '' }}">
                            <label for="dob" class="col-md-4 control-label">Date of Birth <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

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
                        {{-- <div class="form-group{{ $errors->has('cat') ? 'is-invalid' : '' }}">
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

                        {{-- <div class="form-group{{ $errors->has('student_cat') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('g-recaptcha-response') ? 'is-invalid' : '' }}">
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
<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="showModalTitle"><strong> Stop! Before you continue... </strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>If you do not have a <em>@un.org</em> professional/work email address, please enter a personal email address i.e. yahoo, gmail, outlook, etc.</p>
        <p>Thank you for understanding and complying.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Yes, I understand</button>
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
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minView: 2
    });
    
    $('.email-input').one('click', function () {
        $('#showModal').modal('show');
    });

    $('#showModal').on('hidden.bs.modal', function (e) {
        $('input.email-input').focus();
    })
  });
</script>
@stop