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
</style>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Change Password</div>

                <div class="panel-body">
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

                        <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
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

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
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

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
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
    </div>
</div>
@stop

@section('java_script')
<script src="{{ asset('js/passwordscheck.js') }}"></script>
@stop
