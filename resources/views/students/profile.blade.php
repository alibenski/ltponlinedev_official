@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Student Profile</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action=" ">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Full Name:</label>

                            <div class="col-md-4 control-label">
                                <p>{{ $student->name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email Address:</label>

                            <div class="col-md-4 control-label">
                                <p>{{ $student->email }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Type of Appointment:</label>

                            <div class="col-md-4 control-label">
                                <p>{{ $student->job_appointment }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_category" class="col-md-4 control-label">Job Category:</label>

                            <div class="col-md-4 control-label">
                                <p>{{ $student->job_category }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="language" class="col-md-4 control-label">Enrolled to language:</label>

                            <div class="col-md-4">
                                <p class="form-control-static">
                                @if(empty ($student->languages->name))
                                none
                                @else
                                {{ $student->languages->name }}</p> 
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="course" class="col-md-4 control-label">Enrolled to course:</label>

                            <div class="col-md-4 control-label">
                                <p>
								@if(empty ($student->courses->name))
								<p>none</p>
								@else
								<p>{{ $student->courses->name }}</p> 
								@endif
			                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- no further div -->
@endsection

@section('scripts_link')
<script src="{{ asset('js/app.js') }}"></script>
@stop