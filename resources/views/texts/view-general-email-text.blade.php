@extends('admin.admin')

@section('customcss')
<link href="https://fonts.googleapis.com/css?family=Eczar&display=swap" rel="stylesheet">
<style>
    .content-wrapper { background-color: white; }
    .eczar {font-family: Eczar;}
</style>
@stop

@section('content')

<div class='col-md-12'>

    <form>
        <div class="form-group">
            <div class="col-md-12">
                <h4 class="eczar"><i class="fa fa-arrow-right"></i> Viewing: {{ $text->name }} </h4> 
                <a href="{{ route('system-index') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
                <a href="{{ route('edit-enrolment-is-open-text', [$text->id]) }}" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
            </div>                      
        </div>
    </form>
</div>

<div class='container'>
        <div class="form-group">
            <div class="col-md-12">
                <h4 class="text-center eczar"><label for="subject">Subject:</label> {{ $text->subject }}</h4> 
            </div>                      
        </div>
</div>

@include('emails.generalEmail')

@stop

@section('java_script')

@stop