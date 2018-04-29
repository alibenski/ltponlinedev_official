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
                    <h2 class="text-center">Enrolment Instructions and Language Training Programme Announcements</h2>
                        <div class="col-sm-12 text-justify">
                            <br>
                            <h4><strong>Dear LTP participants,</strong></h4>
                            <h4>Welcome to the online registration platform.</h4>
                            <p><strong>This new platform is being piloted for registration in the summer language courses in French, Spanish and English. </strong><strong>For the autumn term (from 10 September to 30 November 2018), we will still use the paper-based registration system. The registration period for the autumn term will be from 21 May to 12 June.</strong></p>
                            <h4><strong>How can I enrol?</strong></h4>
                            <p>Go to the tab &ldquo;Register/Enrol Here&rdquo;. Select if you are a self-paying student or not. Then follow the instructions. You can do all the registration steps online, which replaces the previous paperwork and physical signatures. Your supervisor and the learning focal point will receive an email to approve your registration.</p>
                            <p>Once you have submitted your enrolment form, you can go to the tab &ldquo;current submitted forms&rdquo; to check the status of your enrolment. When your supervisor (and learning focal point when required) have approved your enrolment, you will receive an email.</p>
                            <p>Only once your supervisor has approved your enrolment and, when required, your focal point too, will your enrolment be confirmed, indicating the date and time of your enrolment.&nbsp;</p>
                            <p><strong>If you want to enrol in two courses for the same term, you need to submit two forms. You may submit a maximum of two enrolment forms per term.</strong></p>
                            <h4><strong>Should I take a placement test?</strong></h4>
                            <p>If you have previously studied the language you request outside the UN, you will need to take a placement test to be placed in the correct level. The placement test is mandatory for all new language students, unless they are complete beginners. &nbsp;Complete beginners are not required to take the placement test.</p>
                            <p>The system will identify if you are a new student in the language requested, or if you have had &nbsp;a break in study of more than two terms.&nbsp; &nbsp;In these cases, a placement test will be automatically required, unless you are a complete beginner.</p>
                            <h4><strong>What should I do if I want to cancel my language course?</strong></h4>
                            <p>Go to the tab &ldquo;current submitted forms&rdquo;, select the cancellation form &nbsp;&nbsp;and click on the button: &ldquo;Cancel Enrolment&rdquo;</p>
                            <p>The deadline for cancelling your course is two weeks before the start date of the summer courses.&nbsp;</p>
                            <h4><strong>Contact:</strong></h4>
                            <p>If you have any questions, please contact the Language Secretariat by email: <strong>clm_language@un.org.</strong></p>
                        </div>                   
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