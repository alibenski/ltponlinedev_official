@extends('main')
@section('tabtitle', '| Home')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
                        <div class="col-sm-12 text-justify">
                        <h2 class="text-center">Enrolment Instructions and Language Training Programme Announcements</h2> 
                            <br>
                            <h4>Welcome to the online registration platform.</h4>
                            <p><strong>For registration in the winter language courses in Arabic, Chinese, English French, Spanish and Russian please follow the instructions below. </strong>
                            <h4><strong>How can I enrol?</strong></h4>
                            <p>Go to the button “Register/Enrol Here”.  Then follow the instructions. You can do all the registration steps online, which replaces the previous paperwork and physical signatures. Your supervisor and the learning focal point will receive an email to approve your registration.</p>
                            <p>Once you have submitted your enrolment form, you can go to the tab “current submitted forms” to check the status of your enrolment. When your supervisor (and learning focal point when required) have approved your enrolment, you will receive an email.</p>
                            <p>Only once your supervisor and your learning focal point when required have approved your enrolment will your enrolment be confirmed, indicating the date and time of your enrolment.</p>
                            <p><strong>If you want to enrol in two courses for the same term, you need to submit two forms. You may submit a maximum of two enrolment forms per term.</strong></p>
                            <h4><strong>Should I take a placement test?</strong></h4>
                            <p>If you have previously studied the language you request outside the UN, you will need to take a placement test to be placed in the correct level. The placement test is mandatory for all new language students, unless they are complete beginners.  Complete beginners are not required to take the placement test.</p>
                            <p>The system will identify if you are a new student in the language requested, or if you have had a break in study of more than two terms.   In these cases, a placement test will be automatically required, unless you are a complete beginner.</p>
                            <h4><strong>What should I do if I want to cancel my language course?</strong></h4>
                            <p>Go to the tab “current submitted forms”, select the cancellation form   and click on the button: “Cancel Enrolment” " or “ cancel placement test “.</p>
                            <p>The deadline for cancelling your course is 30 December 2018 at 11:59 p.m.</p>
                            <h4><strong>Contact:</strong></h4>
                            <p>If you have any questions, please contact the Language Secretariat by email: <strong>clm_language@un.org.</strong></p>
                        </div>                   
                        <hr>
                        <div class="col-sm-12 text-justify">
                            <h2 class="text-center">Instructions pour l’inscription et annonces du Programme de formation linguistique</h2>
                            <br>
                            <h4>Bienvenue sur la plateforme d’inscription en ligne.</h4>
                            <p><strong>Pour l’inscription aux cours d’hiver 2018 de langues arabe, chinois, anglais français, espagnol et russe ; veuillez suivre les indications ci-dessous.</strong>
                            <h4><strong>Comment puis-je m'inscrire ?</strong></h4>
                            <p>Vous devez vous inscrire pendant la période d’inscription en allant sur la page d’inscription. Cliquer sur "“Register/Enrol Here”. ". Suivez alors les instructions. Vous pouvez effectuer toutes les étapes d’inscription en ligne, ces dernières remplacent les formulaires papiers et les signatures physiques. Votre superviseur et  et le service de la formation du personnel de votre organisation/institution le cas échéant recevront un courrier électronique afin d’approuver votre demande d’inscription.</p>
                            <p>Une fois que vous avez soumis votre formulaire d'inscription, vous pouvez aller à l’onglet "Submitted forms" pour vérifier le statut de votre inscription. Quand votre superviseur et le service de la formation du personnel de votre organisation/institution si nécessaire a approuvé votre inscription, vous recevrez un courrier électronique.</p>
                            <p>Ce n’est qu’une fois que votre superviseur et, le cas échéant, le service de la formation du personnel de votre organisation ont approuvé votre inscription qu’elle sera validée et la date et heure de votre inscription consignées.</p>
                            <p><strong>Si vous voulez vous inscrire dans deux cours différents pour le même trimestre, vous devez soumettre deux formulaires. Vous pouvez vous inscrire au maximum à deux cours par trimestre.</strong></p>
                            <h4><strong>Devrai-je passer un test de placement ?</strong></h4>
                            <p>Si vous avez déjà étudié la langue en question en dehors de l'ONU, vous devrez passer un test de placement afin d’être placé(e) dans le niveau approprié. Un test de placement est obligatoire pour tous les nouveaux étudiants, à moins qu'ils soient débutants. Les débutants ne doivent pas passer le test de placement.</p>
                            <p>Si vous retournez dans le Programme de formation linguistique, le système identifiera si vous êtes un nouvel étudiant dans la langue demandée, ou si vous avez fait une pause de plus de deux trimestres, vous serez alors invité(e) à passer un test de placement avant de reprendre votre apprentissage.</p>
                            <h4><strong>Que devrais-je faire si je veux annuler mon cours de langue ?</strong></h4>
                            <p>Allez à l’onglet " Register/Enrol Here ", choisissezle formulaire d'annulation et cliquez sur le bouton : "cancel enrolment " ou «  cancel placement test »</p>
                            <p>Le délai d’annulation pour votre cours de langues est le 30 décembre à 23h59.</p>
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