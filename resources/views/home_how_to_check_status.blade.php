@extends('main')
@section('tabtitle', '| Home')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    .active-home {
        color: #fff;
        font-weight: bold;
        background-color: #636b6f;
        }
    .active-home:hover {
        color: #fff;
        background-color: #636b6f;
        }
    </style>

    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css"/>
    <style>
        .container-slick {
            margin: 0 auto;
            padding: 40px;
            width: 80%;
            color: #333;
            /*background: #419be0;*/
            background: #fff;
        }

        .slick-slide {
            text-align: center;
            color: #419be0;
            background: white;
            margin:10px;
        }

        img {
            min-width: 80%;
            height: auto;
        }

        .slick-slide img{
          width:100%;
          /*border: 2px solid #fff;*/
        }
        .slick-prev.slick-arrow::before {
            color: #419be0;
        }
        .slick-next.slick-arrow::before {
            color: #419be0;
        }

        .bounding-box {
            background-size: contain;
            /*position: absolute;*/
            background-position: center;
            background-repeat: no-repeat;
            height: 100%;
            width: 100%;
        }
    </style>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white bg-primary"><strong>Dashboard</strong></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="col-sm-12">
                        
                        @include('home_dashboard_nav')
                            
                        <div class="form-group">

                            <div class='container-slick'>
                              <div class='single-item'>
                                <div>
                                    <div class='bounding-box'>
                                        <img src="{{ asset('img/slide_1.png') }}" alt="slide_1">
                                        <h2>Click "Submitted Forms"</h2>
                                    </div>
                                </div>
                                <div>
                                    <div class='bounding-box'>
                                        <img src="{{ asset('img/slide_2.png') }}" alt="slide_2">
                                        <h2>Select the current Term and click "Submit"</h2>
                                    </div>
                                </div>
                                <div>
                                    <div class='bounding-box'>
                                        <img src="{{ asset('img/slide_3.png') }}" alt="slide_3">
                                        <h2>Click on "View Info" to see enrolment status</h2>
                                    </div>
                                </div>
                                <div>
                                    <div class='bounding-box'>
                                        <img src="{{ asset('img/slide_4.png') }}" alt="slide_4">
                                        <h2>A pop-up box will show the enrolment status</h2>
                                    </div>
                                </div>
                              </div>
                            </div>
                        
                        </div>

                    </div>
                </div>
            </div>
        </div>  

        {{-- <div class="col-md-3">
            <div class="card card-info">
                <div class="card-heading text-center"><strong>UN Language Courses</strong></div>
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
        </div>  --}}
    </div>
</div>
@stop

@section('scripts_code')

{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="{{ asset('js/slick.js') }}"></script>
@stop