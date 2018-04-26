@extends('main')
@section('tabtitle', '| Home')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Dashboard</strong></div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="col-sm-12">
                    <h2 class="text-center">Enrolment Instructions and LTP Announcements</h2>
                        <ul class="col-sm-10 col-sm-offset-1">
                            <li>2 approved enrolment forms means you agree to attend 2 courses in the same semester</li>
                            <li>The system will automatically identify if you have not attended any courses with CLM for more than 2 terms prior to this semester.</li>
                            <li>You can submit a maximum of 2 enrolment forms</li>
                            <li>You can submit a maximum of 2 placement test forms</li>
                            <li>If you have any questions, please contact the Language Secretariat via e-mail: <strong> clm_language@un.org</strong></li>
                        </ul>                    
                    </div>
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