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
                            <label for="title" class="col-md-4 control-label">Title:</label>

                            <div class="col-md-8 form-control-static">
                                <p>@if(empty ( Auth::user()->sddextr )) Update Needed @else {{ Auth::user()->sddextr->TITLE }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullName" class="col-md-4 control-label">Full Name:</label>

                            <div class="col-md-8 form-control-static">
                                <p>@if(empty( Auth::user()->sddextr )) Update Needed @else {{ Auth::user()->sddextr->LASTNAME }}, {{ Auth::user()->sddextr->FIRSTNAME }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email Address:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="org" class="col-md-4 control-label">Organization:</label>

                            <div class="col-md-8 form-control-static">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->torgan['Org name'] }} - {{ Auth::user()->sddextr->torgan['Org Full Name'] }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contactNo" class="col-md-4 control-label">Contact Number:</label>

                            <div class="col-md-8 form-control-static">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->PHONE }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobAppointment" class="col-md-4 control-label">Type of Appointment:</label>

                            <div class="col-md-8 form-control-static">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->CATEGORY }} @endif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gradeLevel" class="col-md-4 control-label">Grade Level:</label>

                            <div class="col-md-8 form-control-static">
                                <p>@if(empty(Auth::user()->sddextr)) Update Needed @else {{ Auth::user()->sddextr->LEVEL }}@endif</p>
                            </div>
                        </div>

                        {{-- <div class="form-group">
                            <label for="contractExp" class="col-md-4 control-label">Contract Expiration:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->sddextr->CONEXP }}</p>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label for="course" class="col-md-4 control-label">Last UN Language Course:</label>

                            <div class="col-md-8 form-control-static">
                                <p>
                                    @if(empty ($repos_lang))
                                    None
                                    @elseif(empty ($repos_lang->courses->EDescription))
                                    {{ $repos_lang->Te_Code }}
                                    @else
                                    {{ $repos_lang->courses->EDescription }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-offset-4"><a href="{{ route('students.edit', Auth::user()->id) }}" class="btn btn-block btn-info btn-md">Edit my CLM Online Profile</a>
                        </div>
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