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
            <div class="well row">
                <div class="form-group">
                    <h3 style="text-align: center;"><strong>Submitted Enrolment Forms for the 
                        @if(empty($next_term->Term_Name))
                        DB NO ENTRY
                        @else
                        {{ $next_term->Term_Name }} 
                        @endif
                        Term
                      </strong>
                    </h3>
                        <ul>
                            @foreach($forms_submitted as $form)
                            <div class="col-sm-12">
                            <p><span for="" class="label label-warning" style="margin-right: 10px;">STATUS</span>{{ $form->courses->Description.' - '.$form->schedule->name}}</p>
                            <hr>
                            </div>
                            <div class="col-sm-2">
                            <!--<a href="{{ route('noform.edit', Crypt::encrypt($form->id)) }}" class="btn btn-default btn-sm">View</a>-->
                            </div>
                            @endforeach
                        </ul>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>
@stop 