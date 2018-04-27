@extends('main')
@section('tabtitle', '| Historical Data')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                    <div class="panel-heading"><strong>Past Language Course Enrolment for {{ Auth::user()->name }}</div>
                        <div class="panel-body">
                            <ul  class="list-group">
                                @foreach($historical_data as $hist_datum)
                                        <li class="list-group-item"><strong class="text-success">{{ $hist_datum->courses->Description }}</strong> : {{ $hist_datum->terms->Term_Name }}</li>
                                @endforeach
                            </ul>
                        </div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>
</div>
@endsection
