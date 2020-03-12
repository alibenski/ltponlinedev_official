@extends('layouts.app')

@section('content')
@if (Session::has('expired')) 
    <div class="alert alert-danger alert-block text-center" role="alert">
        <strong>Session Expired: </strong> {{ Session::get('expired') }}
    </div>
@endif
@if (Session::has('success')) 
    <div class="alert alert-success alert-block text-center" role="alert">
        <strong>Note: </strong> {{ Session::get('success') }}
    </div>
@endif
@if (Session::has('warning')) 
    <div class="alert alert-warning alert-block text-center" role="alert">
        <strong>Note: </strong> {{ Session::get('warning') }}
    </div>
@endif
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-md-2">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form class="form-prevent-multi-submit" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label"><strong>Email Address</strong></label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" aria-describedby="emailHelpBlock" required autofocus>

                                {{-- @if ($errors->has('email'))
                                <small id="emailHelpBlock" class="form-text text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </small>
                                @endif --}}

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label"><strong>Password</strong></label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" aria-describedby="pwdHelpBlock" required>

                                {{-- @if ($errors->has('password'))
                                    <small id="pwdHelpBlock" class="form-text text-danger">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </small>
                                @endif --}}
                                
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger mt-2" role="alert">
                                        @foreach ($errors->all() as $error)
                                            <small>{{ $error }}</small>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-outline-primary button-prevent-multi-submit">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
