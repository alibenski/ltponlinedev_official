@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('indexno') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('nameLast') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('nameFirst') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('email') ? 'is-invalid' : '' }}">
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

                        <div class="form-group{{ $errors->has('password') ? 'is-invalid' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

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
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
