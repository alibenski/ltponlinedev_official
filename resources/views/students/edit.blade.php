@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
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
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span><input  name="name" placeholder="{{ $student->name }}" class="form-control"  type="text" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email Address:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span><input  name="email" placeholder="{{ $student->email }}" class="form-control"  type="text">                                    
                                </div>
                                <p class="small text-danger"><strong>Warning:</strong> Once you change your e-mail address, this will become <strong>your login and your official e-mail address</strong> to which we will be sending notifications and other future correspondences.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Contact Number:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span><input  name="email" placeholder="{{ $student->sddextr->PHONE }}" class="form-control"  type="text" readonly>                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-2 control-label">Type of Appointment:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-folder-open"></i></span><input  name="job_appointment" placeholder="{{ $student->job_appointment }}" class="form-control"  type="text" readonly="">
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