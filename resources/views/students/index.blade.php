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

    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-success">
                <div class="panel-heading"><strong>Student Profile</strong></div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal">
                        

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Full Name:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email Address:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Organization:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->sddextr->DEPT }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Contact Number:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->sddextr->PHONE }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Gender:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ ucfirst(Auth::user()->sddextr->SEX) }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Type of Appointment:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->sddextr->CATEGORY }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_category" class="col-md-4 control-label">Job Category:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->job_category }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_appointment" class="col-md-4 control-label">Contract Expiration:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->sddextr->CONEXP }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="course" class="col-md-4 control-label">Last UN Language Course:</label>

                            <div class="col-md-8 form-control-static">
                                <p>
                                    @if(empty ($repos_lang->courses->EDescription))
                                    none
                                    @else
                                    {{ $repos_lang->courses->EDescription }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <!--<div class="col-md-12"><a href="{{ route('myform.create') }}" class="btn btn-block btn-info btn-md">click here to enrol</a>
                        </div>-->
                    </form>
                </div>
            </div>
        </div>  
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading text-center"><strong>UN Language Courses</strong></div>
                <div class="panel-body">
                    <ul  class="list-group">
                        <a href="https://learning.unog.ch/language-course-arabic" target="_blank" class=" text-center arab-txt">Arabic</a>
                        <a href="https://learning.unog.ch/language-course-chinese" target="_blank" class=" text-center chi-txt">Chinese</a>
                        <a href="https://learning.unog.ch/language-course-english" target="_blank" class=" text-center eng-txt">English</a>
                        <a href="https://learning.unog.ch/language-course-french" target="_blank" class=" text-center fr-txt">French</a>
                        <a href="https://learning.unog.ch/language-course-russian" target="_blank" class=" text-center ru-txt">Russian</a>
                        <a href="https://learning.unog.ch/language-course-spanish" target="_blank" class=" text-center sp-txt">Spanish</a>
                    </ul>
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
@section('scripts_link')

<script src="{{ asset('js/app.js') }}"></script>

@stop