@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
@if (Session::has('overlimit')) 
    <div class="alert alert-danger" role="alert">
        <strong>Sorry: </strong> {{ Session::get('overlimit') }}
    </div>
@endif
@if (Session::has('enrolment_closed')) 
    <div class="alert alert-danger" role="alert">
        <strong>Sorry: </strong> {{ Session::get('enrolment_closed') }}
    </div>
@endif
<div class="container">
    <div class="row">
        <div class="col-md-5">
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
                            <label for="name" class="col-md-5 control-label">Full Name:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-5 control-label">Email Address:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-5 control-label">Type of Appointment:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->job_appointment }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_category" class="col-md-5 control-label">Job Category:</label>

                            <div class="col-md-4 form-control-static">
                                <p>{{ Auth::user()->job_category }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="course" class="col-md-5 control-label">Last UN Language Course:</label>

                            <div class="col-md-4 form-control-static">
                                <p>
                                    @if(empty ($repos_lang->courses->EDescription))
                                    none
                                    @else
                                    {{ $repos_lang->courses->EDescription }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12"><a href="{{ route('myform.create') }}" class="btn btn-block btn-info btn-md">click here to enrol</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- show submitted forms -->
        <div class="col-md-7">
            <div class="well">
                <div class="form-group">
                    <label for="course" class="control-label">Submitted Enrolment Forms for the 
                        @if(empty($next_term->Term_Name))
                        DB NO ENTRY
                        @else
                        {{ $next_term->Term_Name }} 
                        @endif
                    Term:</label>
                    <div class="">
                        <ul>
                                @foreach($forms_submitted as $form)
                                <span class="label label-success"><a href="{{ route('myform.edit', Crypt::encrypt($form->id)) }}" style="color: white;">{{ $form->courses->Description.' - '.$form->schedule->name}}</a></span>
                                @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts_link')
<script src="{{ asset('js/app.js') }}"></script>
@stop