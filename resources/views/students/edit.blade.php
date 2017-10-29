@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Student Profile</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.update', $student->id) }}" class="form-horizontal">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">Full Name:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="name" placeholder="{{ $student->name }}" class="form-control"  type="text" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email Address:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="email" placeholder="{{ $student->email }}" class="form-control"  type="text" readonly>                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-2 control-label">Type of Appointment:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-folder-open"></i></span><input  name="job_appointment" placeholder="{{ $student->job_appointment }}" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_category" class="col-md-2 control-label">Job Category:</label>

                            <div class="col-md-8">
                                <p class="form-control-static">{{ $student->job_category }}</p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- no further div -->
@endsection