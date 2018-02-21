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
                    <div class="col-md-12">
                    <h2>Enrolment Instructions and LTP Announcements</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus, iste. Sed, ab. Quod, molestiae! Repellendus cum, aliquid. Sit architecto fuga amet, tempore commodi, inventore voluptate quasi, qui nemo, voluptatem fugiat.</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero voluptatum tempore dicta reiciendis accusamus, quod quaerat facilis nostrum sapiente inventore porro itaque nam, aut alias molestiae sed, nisi quos doloribus.</p>
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