@extends('main')
@section('tabtitle', '| Home')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    .active-home>a {
        color: #fff;
        font-weight: bold;
        background-color: #636b6f;
        }
    .nav-pills>li.active-home>a:hover {
        color: #fff;
        background-color: #636b6f;
        }
    </style>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Dashboard</strong></div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="col-sm-12">
                        
                        @include('home_dashboard_nav')

                        <div class="col-sm-12 text-justify">
                        <h2 class="text-center">Enrolment Instructions and Language Training Programme Announcements</h2> 
                            <br>
                            
                            <p><strong>For enrolment on language courses in Arabic, Chinese, English, French, Spanish and Russian, please follow the instructions below.</strong>
                            
                            <h4><strong>How can I enrol?</strong></h4>
                            <p>Go to the button “Register/Enrol Here”. Then follow the instructions. <strong>You will need your supervisor’s approval prior to enrolment.</strong> If required, the learning focal point from your organization will need to approve your registration via an email that they will receive. <strong>Please note that fees might be involved for your organization. Please contact your learning focal point prior to enrolment.</strong></p>
                            <p>Once you have submitted your enrolment form, you can go to the tab “current submitted forms” to check the status of your enrolment. Please note that your enrolment will <strong>only</strong> be confirmed after approval from your learning focal point (if applicable). You will then receive an email with an indication of the date and time of your enrolment.</p>
                            <p><strong>If you want to enrol on two courses for the same term, you need to submit two forms. You may submit a maximum of two enrolment forms per term. </strong> If you want to take just one course, but do not know which one to select as you might be interested in several courses, fill in only one form registering for your favourite course and indicate in the comments box the other courses you may take instead. If you are currently taking a course with us, remember that you can also ask your teacher for advice. </p>

                            <h4><strong>Should I take a placement test?</strong></h4>
                            <p>Complete beginners are not required to take a placement test.  If you have previously studied the language you are requesting outside the UN, you will need to take a placement test to be placed in the correct level. It is also mandatory for those who have had a break in study of more than two terms.</p>

                            <h4><strong>What should I do if I want to cancel my language course?</strong></h4>
                            <p>Go to the tab “current submitted forms”, select the cancellation form and click on the button: “Cancel Enrolment”  or “cancel placement test”.</p>
                            <p>The deadline for cancelling your course is 29 December 2019 at 11:59 p.m.</p>
                            
                            <h4><strong>Contact:</strong></h4>
                            <p>If you have any questions, please contact the Language Secretariat by email: <strong>clm_language@un.org.</strong></p>
                        </div>                   
                        <hr>
                        <div class="col-sm-12 text-justify">
                            <h2 class="text-center">Instructions pour l’inscription et annonces du Programme de formation linguistique</h2>
                            <br>
                            
                            <p><strong>Pour l’inscription aux cours de langues arabe, chinois, anglais français, espagnol et russe ; veuillez suivre les indications ci-dessous.</strong></p>

                            <h4><strong>Comment puis-je m'inscrire ?</strong></h4>
                            <p>Vous devez vous inscrire pendant la période d’inscription en allant sur la page d’inscription. Cliquer sur "Register/Enrol Here”. Suivez alors les instructions. <strong>Vous devez obtenir avant de vous inscrire l’accord de votre superviseur.</strong> Si nécessaire, le service de la formation du personnel de votre organisation/institution recevra un courrier électronique afin d’approuver votre demande d’inscription. <strong>Veuillez noter que votre inscription peut générer des frais pour votre organisation. Prenez contact avec le service de la formation du personnel de votre institution avant de vous inscrire.</strong></p>
                            <p>Une fois que vous avez soumis votre formulaire d'inscription, vous pouvez aller à l’onglet "Submitted forms" pour vérifier le statut de votre inscription. Quand le service de la formation du personnel de votre organisation/institution si nécessaire a approuvé votre inscription, vous recevrez un courrier électronique. Ce n’est qu’alors que votre inscription sera validée et la date et heure de votre inscription consignées.</p>
                            
                            <p><strong>Si vous voulez vous inscrire dans deux cours différents pour le même trimestre, vous devez soumettre deux formulaires. Vous pouvez vous inscrire au maximum à deux cours par trimestre.</strong> Si vous souhaitez suivre un seul cours, mais ne savez pas lequel choisir, car vous pourriez être intéressé(e) par plusieurs cours, remplissez un seul formulaire pour vous inscrire à votre cours préféré et indiquez dans la zone de commentaires les autres cours que vous pourriez suivre. Si vous suivez actuellement un cours avec nous, n’oubliez pas que vous pouvez également demander conseil à votre professeur.</p>

                            <h4><strong>Devrais-je passer un test de placement ?</strong></h4>
                            <p>Les débutants ne sont pas tenus de passer un test de classement. Si vous avez déjà étudié la langue que vous demandez en dehors de l’ONU, vous devrez passer un test de placement pour être placé(e) au niveau correct. Il est également obligatoire pour ceux qui ont eu une pause dans leur apprentissage de plus de deux trimestres.</p>
                            
                            <h4><strong>Que devrais-je faire si je veux annuler mon cours de langue ?</strong></h4>
                            <p>Allez à l’onglet " Register/Enrol Here ", choisissez le formulaire d'annulation et cliquez sur le bouton : "cancel enrolment " ou « cancel placement test »</p>
                            <p>Le délai d’annulation pour votre cours de langues est le 29 decembre 2019 à 23h59.</p>
                            
                            <h4><strong>Contact:</strong></h4>
                            <p>Si vous avez des questions, veuillez contacter, le secrétariat par courriel à : <strong>clm_language@un.org.</strong></p>
                        </div>   

                    </div>
                </div>
            </div>
        </div>  

        {{-- <div class="col-md-3">
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
        </div>  --}}
    </div>
</div>
@endsection
@section('scripts_link')

<script src="{{ asset('js/app.js') }}"></script>

@stop