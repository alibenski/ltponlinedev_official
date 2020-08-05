@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Validate & Register with Umoja Information</div>

                <div class="card-body">
                    <form class="form-horizontal form-prevent-multi-submit" method="POST" action="{{ route('newuser.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group row {{ $errors->has('indexno') ? 'is-invalid' : '' }}">
                            <label for="indexno" class="col-md-12 control-label">Index # <span class="small text-danger"></span></label>

                            <div class="col-md-12">
                                <input id="indexno" type="text" class="form-control" name="indexno" value="{{ old('indexno') }}" autofocus>

                                @if ($errors->has('indexno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('indexno') }}</strong>
                                    </span>
                                @endif
                            <small class="form-text text-danger"><strong>Please delete trailing zeroes if you have an index number which is less than 8 digits e.g. 00012345 -> 12345</strong></small>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <label for="email" class="col-md-12 control-label">UN email address</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            <p class="small text-danger"><strong>Please enter the email address you are using to receive convocation emails from CLM Language.</strong></p>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('g-recaptcha-response') ? 'is-invalid' : '' }}">
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
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary button-prevent-multi-submit">
                                    Validate
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
