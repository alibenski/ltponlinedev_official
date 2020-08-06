@extends('main')
@section('tabtitle', '| Historical Data')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-secondary"><strong class="text-white">Past Language Course Enrolment for {{ Auth::user()->name }}</strong>
                </div>
                <div class="card-body">
                    @if(empty($historical_data))
                    <div class="alert alert-warning">
                        <p>There were no historical records found.</p>
                    </div>
                    @else
                    <ul  class="list-group">
                        @foreach($historical_data as $hist_datum)
                            <li class="list-group-item"><strong class="text-secondary">
                            @if(empty($hist_datum))
                            <div class="alert alert-warning">
                                <p>No records found.</p>
                            </div>
                            @else
                                @if(empty($hist_datum->Te_Code)) {{ $hist_datum->coursesOld->Description }} 
                                @else {{ $hist_datum->courses->Description }} 
                                @endif</strong> : {{ $hist_datum->terms->Term_Name }} 

                                <em>
                                @if (empty($hist_datum->classrooms))
                                @else
                                    @if (is_null($hist_datum->classrooms->Tch_ID))
                                        Waitlisted
                                    @elseif($hist_datum->classrooms->Tch_ID == 'TBD')
                                        Waitlisted
                                    @else
                                        * {{ $hist_datum->classrooms->Tch_ID }} *
                                    @endif
                                @endif
                                </em>

                                (@if($hist_datum->Result == 'P') Passed @elseif($hist_datum->Result == 'F') Failed @elseif($hist_datum->Result == 'I') Incomplete @else @endif)
                
                                @if($hist_datum->Term >= 191 )
                                <a class="btn btn-default hidden" href="{{ route('pdfAttestation',['language' =>'En', 'download'=>'pdf', 'id'=> $hist_datum->id]) }}" target="_blank"><i class="fa fa-print"></i> Print EN</a>
                                <a class="btn btn-default hidden" href="{{ route('pdfAttestation',['language' =>'Fr', 'download'=>'pdf', 'id'=> $hist_datum->id]) }}" target="_blank"><i class="fa fa-print"></i> Print FR</a>
                                @endif
                            </li>
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
