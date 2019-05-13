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
                <h4><i class="fa fa-arrow-right"></i> Send Broadcast Email that Enrolment is Open</h4> 
                <a href="{{URL::previous()}}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
                <a href="{{ route('edit-enrolment-is-open-text') }}" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
            </div>                      
        </div>
    </form> 

</div>

@include('emails.sendBroadcastEnrolmentIsOpen')

@stop

@section('java_script')

@stop