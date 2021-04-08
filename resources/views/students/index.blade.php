@extends('main')
@section('tabtitle', 'Profile')
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
        <div class="col-md-9" style="margin-bottom: 1rem">
            <div class="card">
                <div class="card-header bg-primary"><strong class="text-white">Student Profile</strong></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal">
                        
                        <div class="form-group row">
                            <label for="fullName" class="col-md-4 col-form-label">Profile:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p> 
                                    @if(empty( Auth::user()->profile )) Update Needed 
                                    @else
                                        @if( Auth::user()->profile == "STF") Staff Member @endif
                                        @if( Auth::user()->profile == "INT") Intern @endif
                                        @if( Auth::user()->profile == "CON") Consultant @endif
                                        @if( Auth::user()->profile == "WAE") When Actually Employed @endif
                                        @if( Auth::user()->profile == "JPO") JPO @endif
                                        @if( Auth::user()->profile == "MSU") Staff of Permanent Mission @endif
                                        @if( Auth::user()->profile == "SPOUSE") Spouse of Staff from UN or Mission @endif
                                        @if( Auth::user()->profile == "RET") Retired UN Staff Member @endif
                                        @if( Auth::user()->profile == "SERV") Staff of Service Organizations in the Palais @endif
                                        @if( Auth::user()->profile == "NGO") Staff of UN-accredited NGO's @endif
                                        @if( Auth::user()->profile == "PRESS") Staff of UN Press Corps @endif 
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label">Title:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty ( Auth::user()->sddextr )) Update Needed @else {{ Auth::user()->sddextr->TITLE }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label">Gender:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty ( Auth::user()->sddextr )) Update Needed 
                                    @else 
                                        @if (Auth::user()->sddextr->SEX == "M") Male @endif
                                        @if (Auth::user()->sddextr->SEX == "F") Female @endif
                                        @if (Auth::user()->sddextr->SEX == "O") Other @endif
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fullName" class="col-md-4 col-form-label">Full Name:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty( Auth::user()->sddextr )) Update Needed @else {{ Auth::user()->sddextr->LASTNAME }}, {{ Auth::user()->sddextr->FIRSTNAME }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label">Email Address:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="org" class="col-md-4 col-form-label">Organization:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->torgan['Org name'] }} - {{ Auth::user()->sddextr->torgan['Org Full Name'] }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contactNo" class="col-md-4 col-form-label">Contact Number:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->PHONE }} @endif</p>
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label for="contactNo" class="col-md-4 col-form-label">Date of Birth:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->BIRTH }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jobAppointment" class="col-md-4 col-form-label">Type of Appointment:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->CATEGORY }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gradeLevel" class="col-md-4 col-form-label">Grade Level:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->LEVEL }}@endif</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="course" class="col-md-4 col-form-label">Last UN Language Course:</label>

                            <div class="col-md-8 font-weight-bold">
                                <p>
                                    @if(empty ($repos_lang))
                                    None
                                    @else
                                        @if(empty($repos_lang->Te_Code)) {{ $repos_lang->coursesOld->Description }} 
                                        @else {{ $repos_lang->courses->Description}}
                                        @endif 
                                    - {{ $repos_lang->terms->Term_Name }} 
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-4"><a href="{{ route('students.edit', Auth::user()->id) }}" class="btn btn-block btn-outline-info">Edit Profile</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-info text-center"><strong>UN Language Courses</strong></div>
                <div class="card-body">
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