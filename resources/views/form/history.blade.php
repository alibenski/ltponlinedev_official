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
                <div class="panel-heading"><strong>Past Language Course Enrolment for {{ Auth::user()->name }}
                </div>
                <div class="panel-body">
                    @if(empty($historical_data))
                    <div class="alert alert-warning">
                        <p>There were no historical records found.</p>
                    </div>
                    @else
                    <ul  class="list-group">
                        @foreach($historical_data as $hist_datum)
                            <li class="list-group-item"><strong class="text-success">
                            @if(empty($hist_datum))
                            <div class="alert alert-warning">
                                <p>No records found.</p>
                            </div>
                            @else
                                @if(empty($hist_datum->Te_Code)) {{ $hist_datum->coursesOld->Description }} 
                                @else {{ $hist_datum->courses->Description }} 
                                @endif</strong> : {{ $hist_datum->terms->Term_Name }} (@if($hist_datum->Result == 'P') Passed @elseif($hist_datum->Result == 'F') Failed @elseif($hist_datum->Result == 'I') Incomplete @else @endif)</li>
                            @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>
</div>
@endsection
