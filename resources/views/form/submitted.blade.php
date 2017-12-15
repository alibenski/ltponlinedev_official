@extends('main')
@section('tabtitle', '| Submitted Forms')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                    <div class="panel-heading"><strong>Submitted Enrolment Forms for the 
                        @if(empty($next_term->Term_Name))
                        DB NO ENTRY
                        @else
                        {{ $next_term->Term_Name }} 
                        @endif
                        Term
                      </strong>
                    </div>
                        <div class="panel-body">
                            @foreach($forms_submitted as $form)
                            <div class="row">
                            <div class="col-sm-12">
                                <p><span for="" class="label label-warning" style="margin-right: 10px;">For Approval</span><strong>{{ $form->courses->EDescription}}</strong></p>
                                    <div class="col-sm-6">
                                        <a href="" class="btn btn-sm btn-info btn-block btn-space">View Info</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="" class="btn btn-sm btn-danger btn-block btn-space">Cancel Enrolment</a>
                                    </div>
                            </div>
                            </div>
                            <hr>
                            @endforeach
                            <div class="col-sm-2">
                            <!--<a href="{{ route('noform.edit', Crypt::encrypt($form->id)) }}" class="btn btn-default btn-sm">View</a>-->
                            </div>    
                        </div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>
@stop 