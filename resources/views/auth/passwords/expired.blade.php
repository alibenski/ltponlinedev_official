@extends('layouts.app')
@section('customcss')
<style>
    #register .short{
    font-weight:bold;
    color:#FF0000;
    }
    #register .weak{
    font-weight:bold;
    color:orange;
    }
    #register .good{
    font-weight:bold;
    color:#2D98F3;
    }
    #register .strong{
    font-weight:bold;
    color: limegreen;
    }
    ul {
    padding-left: 10px;
    }
</style>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Change Password</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @hasrole('Teacher')
                        <a href="{{ route('teacher-dashboard') }}" class="btn btn-primary">Continue to Teacher Dashboard</a>
                        @endhasrole
                        <a href="/" class="btn btn-success">Continue to the enrolment platform</a>
                    @else
                    <div class="alert alert-info">
                        For security reasons, you are required to change your password upon first login.
                    </div>
                    <form id="register" class="form-horizontal" method="POST" action="{{ route('password.post_expired') }}">
                        {{ csrf_field() }}
                        <input id="email" type="hidden" name="email" value="{{ Auth::user()->email }}">
                        <input id="firstName" type="hidden" name="firstName" value="{{ Auth::user()->nameFirst }}">
                        <input id="lastName" type="hidden" name="lastName" value="{{ Auth::user()->nameLast }}">

                        <div class="form-group{{ $errors->has('current_password') ? 'is-invalid' : '' }}">
                            <label for="current_password" class="col-md-4 control-label">Current Password</label>

                            <div class="col-md-6">
                                <input id="current_password" type="password" class="form-control" name="current_password" required>

                                @if ($errors->has('current_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <label for="password" class="col-md-4 control-label">New Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                                <span id="result" style="font-size: 80%;"></span>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm New Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                <span id="result" style="font-size: 80%;"></span>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary btn-submit button-prevent-multi-submit" disabled>
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><i class="fa fa-lock" aria-hidden="true"></i> Password Strength and Complexity</div>

                <div class="card-body">
                    <ul>
                        <li>At least twelve (12) characters long; more than fourteen (14) characters is better</li>
                        <li>Different from the default (initial) password</li>
                        <li>Should not be the same as the username</li>
                        <li>Composed of at least two (2) of the following character classes:</li>
                        <ul>
                            <li><strong>upper case letters:</strong> ABCDEFGHIJKLMNOPQRSTUVWXYZ</li>
                            <li><strong>lower case letters:</strong> abcdefghij klmnopqrstuvwxyz</li>
                            <li><strong>numbers:</strong> 0123456789</li>
                            <li><strong>special characters:</strong>   !@#$%^&*()+=\`{}[]:";'< >?,./)</li>
                        </ul>
                        <li>Not be based on any personal information that is easily available to potential adversaries, such as names of family members, pets, friends, co-workers, birthdays, addresses, phone numbers etc.</li>
                    </ul>
                    <p>Compliance to <a href="https://iseek.un.org/webpgdept1637_8" target="_blank">UN Secretariat Password Guideline</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('java_script')
<script src="{{ asset('js/passwordscheck.js') }}"></script>
@stop
