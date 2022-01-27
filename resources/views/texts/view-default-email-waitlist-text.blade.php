@extends('admin.no_sidebar_admin')

@section('content')

<div class='col-md-12'>

    <form>
        <div class="form-group">
            <div class="col-md-12">
                <h4 class="eczar"><i class="fa fa-arrow-right"></i> Viewing: Default Waitlist Notification </h4>
            </div>                      
        </div>
    </form>
</div>

<div class='container'>
        <div class="form-group">
            <div class="col-md-12">
                <h4 class="text-center eczar"><label for="subject">Subject:</label> Waiting List Notification </h4> 
            </div>                      
        </div>
</div>

@include('emails.defaultEmailWaitlist')

@stop