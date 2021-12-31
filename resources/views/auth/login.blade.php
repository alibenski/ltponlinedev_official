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
@if (Session::has('ohchr')) 
    <div class="alert alert-danger alert-block text-center" role="alert">
        <strong>Note: </strong> {{ Session::get('ohchr') }}
    </div>
@endif
<div class="container d-flex align-items-center" style="min-height: 75vh;">
    <div class="row">
        <div class="col-sm-6">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/Logo2016_transparent.png') }}" style="height: 120px; width:auto;">
            </a>
            <h1 class="text-white">UNOG-CLM LTP Online Enrolment Platform</h1>
            <p class="text-white text-justify">The Language Training Programme at the United Nations Office at Geneva believes in multilingualism and multiculturalism as key elements of mutual understanding in a global context. Toward this aim, we offer language courses in the six official languages of the United Nations (Arabic, Chinese, English, French, Russian and Spanish).</p>
        </div>
        <div class="col-sm-5 offset-sm-1">
            <div class="card">
                {{-- <div class="card-header">UNOG-CLM LTP Online Enrolment Platform</div> --}}
                <div class="card-body">
                    <form class="form-prevent-multi-submit" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                            <div class="form-floating mt-3">
                                <label for="email"><strong>Email Address</strong></label>
                                <input id="email" type="email" class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" aria-describedby="emailHelpBlock" required autofocus>
                            </div>
                            
                            <div class="form-floating mt-3">
                                <label for="password"><strong>Password</strong></label>
                                <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" aria-describedby="pwdHelpBlock" required>
                                
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger mt-2" role="alert">
                                        @foreach ($errors->all() as $error)
                                            <small>{{ $error }}</small>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 justify-end justify-content-md-end d-grid gap-2 d-md-flex">
                                <a class="" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 offset-md-1">
                                <button type="submit" class="btn btn-block btn-outline-primary button-prevent-multi-submit">
                                    Login
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
