@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Reset Password</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            <small>{{ session('status') }}</small>
                        </div>
                    @endif

                    <form class="form-prevent-multi-submit" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label">E-Mail Address</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control  {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <small class="form-text text-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary button-prevent-multi-submit">
                                    Send Password Reset Link
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
