@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Validation of account</div>
                <div class="panel-body">
                    <div class="alert alert-info col-md-8 col-md-offset-2">
                        <p class="small text-center"><strong>If you are an existing UNOG-CLM Language Training Programme participant, please enter the email address you are using to receive convocation emails from CLM Language.</strong></p>
                    </div>
                    <form class="form-horizontal form-prevent-multi-submit" method="POST" action="{{ route('post-new-outside-user') }}">
                        {{ csrf_field() }}
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
