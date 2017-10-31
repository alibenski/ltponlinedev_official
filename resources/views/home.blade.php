@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Student Profile</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal">
                        

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Full Name:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email Address:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Type of Appointment:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->job_appointment }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_category" class="col-md-4 control-label">Job Category:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->job_category }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="course" class="col-md-4 control-label">Last UN Language Course</label>

                            <div class="col-md-4 form-control-static">
                                <p>
                                    @if(empty ($repos_lang->courses->Description))
                                    none
                                    @else
                                    {{ $repos_lang->courses->Description }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12"><a href="{{ route('myform.create')}}" class="btn btn-block btn-info btn-md">Click here to access the enrolment form</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts_link')
<script src="{{ asset('js/app.js') }}"></script>
@stop