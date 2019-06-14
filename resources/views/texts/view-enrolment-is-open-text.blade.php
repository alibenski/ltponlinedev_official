@extends('admin.admin')

@section('customcss')
<style>
    .content-wrapper { background-color: white; }
</style>
@stop

@section('content')

<div class='col-md-12'>

    <form>
        <div class="form-group">
            <div class="col-md-12">
                <h4><i class="fa fa-arrow-right"></i> {{ $text->name }} Preview</h4> 
                <a href="{{ route('system-index') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
                <a href="{{ route('edit-enrolment-is-open-text', ['id' => $text->id]) }}" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
            </div>                      
        </div>
    </form> 

</div>

<div class='container'>
        <div class="form-group">
            <div class="col-md-12">
                <h4 class="text-center"><label for="subject">Subject:</label> {{ $text->subject }}</h4> 
            </div>                      
        </div>
</div>

@include('emails.sendBroadcastEnrolmentIsOpen')

@stop

@section('java_script')

@stop