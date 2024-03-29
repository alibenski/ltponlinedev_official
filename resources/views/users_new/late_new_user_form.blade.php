@extends('layouts.app')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@stop
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Late Account Registration Form</div>

                <div class="card-body">
                    <form class="form-horizontal form-prevent-multi-submit" enctype="multipart/form-data" method="POST" action="{{ route('late-register') }}">
                        {{ csrf_field() }}
                                                
                        <div class="form-group {{ $errors->has('profile') ? 'is-invalid' : '' }}">
                            <label for="profile" class="col-md-12 control-label">Profile <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            
                            @include('ajax-profile-select')
                            
                        </div>

                        <div id="attachSection"></div>

                        <div id="orgSection" class="form-group{{ $errors->has('org') ? 'is-invalid' : '' }} d-none">
                            <label for="org" class="col-md-12 control-label">Organization <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-12">

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

                        <div id="countrySection"></div>
                        <div id="ngoSection"></div>
                       
                        <div class="form-group {{ $errors->has('title') ? 'is-invalid' : '' }}">
                            <label for="title" class="col-md-12 control-label">Title <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            <div class="col-md-12">
                            <div class="dropdown">
                                <select class="col-md-12 form-control select2-basic-single" style="width: 100%;" name="title" autocomplete="off" >
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

                        <div class="form-group {{ $errors->has('nameLast') ? 'is-invalid' : '' }}">
                            <label for="nameLast" class="col-md-12 control-label">Last name <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-12">
                                <input id="nameLast" type="text" class="form-control" name="nameLast" value="{{ old('nameLast') }}" required >

                                @if ($errors->has('nameLast'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nameLast') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('nameFirst') ? 'is-invalid' : '' }}">
                            <label for="nameFirst" class="col-md-12 control-label">First name <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-12">
                                <input id="nameFirst" type="text" class="form-control" name="nameFirst" value="{{ old('nameFirst') }}" required >

                                @if ($errors->has('nameFirst'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nameFirst') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <label for="email" class="col-md-12 control-label">Email address <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control email-input" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                            <label for="gender" class="col-md-12 control-label">Gender <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
                            <div class="col-md-12">
                            <div class="dropdown">
                                <select class="col-md-12 form-control select2-basic-single" style="width: 100%;" name="gender" autocomplete="off" required="">
                                    <option value="">--- Please Select ---</option>
                                    <option value="F">Female</option>
                                    <option value="M">Male</option>
                                    <option value="O">Other</option>
                                </select>
                            </div>

                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('contact_num') ? 'is-invalid' : '' }}">
                            <label for="contact_num" class="col-md-12 control-label">Contact number <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-12">
                                <input id="contact_num" type="text" class="form-control" name="contact_num" value="{{ old('contact_num') }}" required >

                                @if ($errors->has('contact_num'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_num') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('dob') ? 'is-invalid' : '' }}">
                            <label for="dob" class="col-md-12 control-label">Date of birth (YYYY-MM-DD)<span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                        <input type="text" id="dob" name="dob" class="form-control datetimepicker-input" data-target="#datetimepicker4" placeholder="">

                                        <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                @if ($errors->has('dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'is-invalid' : '' }}">
                            <label class="col-md-12 control-label">Captcha</label>
                            <div class="col-md-12">
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
                            <div class="col-md-8 offset-md-5">
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/profileOrgAssoc.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#datetimepicker4').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $("select[name='profile']").on("change", function () {
        let profileChoice = $("select[name='profile']").val();
        const profileArray1 = ["STF", "INT", "CON", "WAE", "JPO"];
        console.log(profileChoice);
        if ($.inArray(profileChoice, profileArray1) >= 0) {
            showFileAttach();
        }
        else if (profileChoice == "MSU") {
            showFileAttachMSU();
        } 
        else if (profileChoice == "SPOUSE") {
            showFileAttachSpouse();
        } 
        else if (profileChoice == "RET") {
            showFileAttachRetired();
        } 
        else if (profileChoice == "SERV") {
            showFileAttachServ();
        } 
        else if (profileChoice == "NGO") {
            showFileAttachNgo();
        } 
        else if (profileChoice == "PRESS") {
            showFileAttachPress();
        } 
        else {
            $("#attachSection").html("");
        }
    });

    function showFileAttach() {
        $.ajax({
            url: "{{ route('ajax-file-attach-badge-cdl') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachMSU() {
        $.ajax({
            url: "{{ route('ajax-file-attach-msu') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachSpouse() {
        $.ajax({
            url: "{{ route('ajax-file-attach-spouse') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachRetired() {
        $.ajax({
            url: "{{ route('ajax-file-attach-retired') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachServ() {
        $.ajax({
            url: "{{ route('ajax-file-attach-serv') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachNgo() {
        $.ajax({
            url: "{{ route('ajax-file-attach-ngo') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachPress() {
        $.ajax({
            url: "{{ route('ajax-file-attach-press') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    $("select[name='org']").on("change", function () {
        let choice = $("select[name='org']").val();
        if (choice == "MSU") {
            getCountry();
        } else {
            $("#countrySection").html("");
        }
    });

    function getCountry() {
        $.ajax({
            url: "{{ route('ajax-select-country') }}", 
            method: 'GET',
            success: function(data, status) {
                console.log(data)
            $("#countrySection").html("");
            $("#countrySection").html(data.options);
            }
        });  
    }

    $("select[name='org']").on("change", function () {
        let choice = $("select[name='org']").val();
        if (choice == "NGO") {
            $("#ngoSection").html("<div class='col-md-12'><div class='form-group row'><label for='ngoName' class='col-md-12 control-label text-danger'>NGO Name: <span style='color: red'><i class='fa fa-asterisk' aria-hidden='true'></i> required field</span> </label><div class='col-md-12'><input id='ngoName' type='text' class='form-control' name='ngoName' placeholder='Enter NGO agency name' required></div></div></div>");
        } else {
            $("#ngoSection").html("");
        }
    });

    $('#showModal').on('hidden.bs.modal', function (e) {
        $('input.email-input').focus();
    })
  });    
</script>
@stop