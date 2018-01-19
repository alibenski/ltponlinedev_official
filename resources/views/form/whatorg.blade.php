@extends('main')
@section('tabtitle', '| UN Enrolment Form')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">Enrolment Form for Semester: 
            <strong>
              @if(empty($next_term && $terms))
              NO DB ENTRY
              @else 
              {{ $terms->Term_Next.' - '.$next_term->Term_Name.' - '.$next_term->Comments.' Season' }}
              @endif
            </strong>
          </div>
          <div class="panel-body">
            <form method="POST" action="{{ route('whatform') }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <div class="form-group col-md-12">
                  <p>Hello <strong>{{ Auth::user()->name }},</strong></p>
                  <p>Welcome to the CLM Online Language Training Programme (LTP) Enrolment page. First of all, we would need to know which organization you are working for. Please refer to the information found <a href="https://learning.unog.ch/node/1301#position1"><strong>here</strong></a> to read the FAQ's regarding enrolment eligibility.</p>
                  <p>Please select your organization below. You could search your organization or scroll down from the dropdown box. Please click the Next button to continue.</p>
                  <p>Thank you.</p><br>
                </div>

                <div class="form-group">
                    <label for="org" class="col-md-1 control-label">Organization:</label>
                  <div class="col-md-8">
                    <div class="dropdown">
                      <select name="org" id="input" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
                        @if(!empty($org))
                          @foreach($org as $key => $value)
                            <option value="{{ $key }}" {{ (Auth::user()->sddextr->DEPT == $key) ? 'selected="selected"' : '' }}>{{ $value }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <p class="small text-danger"><strong>Please check that you belong to the correct Organization in this field.</strong></p>
                  </div>
                </div>

                <div class="pull-right col-md-2">
                  <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Next</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
                </div>
            </form>
          </div>
        </div>
    </div>
    </div>
  </div>
</div>

@stop   

@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>

<script>
  $(document).ready(function(){
    $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.select2-basic-single').select2();
  });
</script>

@stop