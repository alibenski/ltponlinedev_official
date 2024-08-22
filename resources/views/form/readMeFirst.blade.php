@extends('main')
@section('tabtitle', 'UN Enrolment Form')
@section('customcss')
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <style>
    .select2-results .select2-disabled,  .select2-results__option[aria-disabled=true] { display: none; }
    </style>
@stop
@section('content')
    <div class="container">
    <div class="row">

        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-info text-center"><strong>Course offer and other information</strong></div>
                <div class="card-body">
                    <ul  class="list-group">
                        <a href="https://learning.unog.ch/language-course-arabic" target="_blank" class=" text-center arab-txt">Arabic Info</a>
                        <a href="https://learning.unog.ch/language-course-chinese" target="_blank" class=" text-center chi-txt">Chinese Info</a>
                        <a href="https://learning.unog.ch/language-course-english" target="_blank" class=" text-center eng-txt">English Info</a>
                        <a href="https://learning.unog.ch/language-course-french" target="_blank" class=" text-center fr-txt">French Info</a>
                        <a href="https://learning.unog.ch/language-course-russian" target="_blank" class=" text-center ru-txt">Russian Info</a>
                        <a href="https://learning.unog.ch/language-course-spanish" target="_blank" class=" text-center sp-txt">Spanish Info</a>
                    </ul>
                </div>
            </div>
        </div> 

        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-info text-white">Enrolment Form for: 
                    <strong>
                    @if(empty($next_term))
                    Enrolment Period Closed
                    @else 
                    {{ $next_term->Term_Name.' - '.$next_term->Comments.' Term' }}
                    @endif
                    </strong>
                </div>
                <div class="card-body">
                    
                    <div class="form-group col-md-12">
                        <p>Hello <strong>{{ Auth::user()->name }},</strong></p>
                        <p class="text-justify">Welcome to the <strong>UNOG-CLM Language Training Programme (LTP) Online Enrolment Platform</strong>. 
                        </p>
                        <p>
                        Before you start your enrolment, please check:
                            <ul>
                                <li>your eligibility <a href="https://learning.unog.ch/node/1301#position1" target="_blank"><strong>HERE</strong></a>
                                </li>
                                <li>the course offer, formats, and requirements <a href="https://learning.unog.ch/language-index" target="_blank"><strong>HERE</strong></a> or on the left block
                                </li>
                                <li>the information circular <a href="https://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/LanguageCourses_en.pdf" target="_blank"><strong>HERE</strong></a>
                                </li>
                            </ul>
                        </p>                        
                        <br />
                        <p>
                        <input id="ReadMeCheckBox" type="checkbox" />
                        I have read and understood the above information and can proceed with my enrolment.     
                        </p>                  
                    </div>

                    <div class="pull-right col-md-2">
                        <a class="btn btn-primary btn-block button-prevent-multi-submit invisible btn-to-whatorg" href="/whatorg">Next</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@stop

@section('scripts_code')

<script>
    $("input#ReadMeCheckBox").on("click", function () {
        if ($(this).is(':checked', true)) {
            $("a.btn-to-whatorg").removeClass("invisible");
        } else {
            $("a.btn-to-whatorg").addClass("invisible");
        }
    })
</script>

@stop