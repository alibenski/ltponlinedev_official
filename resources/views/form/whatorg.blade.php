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
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-info">
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
                  <p class="text-justify">Welcome to the <strong>CLM Online Language Training Programme (LTP) Enrolment</strong> page. Please refer to the information found <a href="https://learning.unog.ch/node/1301#position1" target="_blank"><strong>HERE</strong></a> to read the FAQ's regarding enrolment eligibility.</p>                  
                </div>

                <!-- MAKE A DECISION SECTION -->
                
                <div class="form-group">
                    <label class="col-md-2 control-label">Self-paying student?</label>

                      <div class="col-md-2">
                                <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" required="required">
                                <label for="decision1" class="form-control-static">YES</label>
                      </div>

                      <div class="col-md-2">
                                <input id="decision2" name="decision" class="with-font dno" type="radio" value="0" required="required">
                                <label for="decision2" class="form-control-static">NO</label>
                      </div>
                </div>

                <div id="secretMsg" class="col-md-12"></div>

                <div id="orgSelect" class="form-group 0 box" style="display: none">
                    <label for="organization" class="col-md-2 control-label">Organization:</label>
                  <div class="col-md-9">
                    <div class="dropdown">
                      <select name="organization" id="input" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
                        @if(!empty($org))
                          @foreach($org as $key => $value)
                            {{-- <option value="{{ $key }}" {{ (Auth::user()->sddextr->DEPT == $key) ? 'selected="selected"' : '' }}>{{ $value }}</option> --}}
                            <option value="{{ $key }}">{{ $value }}</option>
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
<script src="{{ asset('js/submit.js') }}"></script>
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

<script>
  $("input[name='decision']").click(function(){
    $('button').css('font-weight', '700');
      if($('#decision1').is(':checked')) { 
        $('#secretMsg').html("<p>You confirmed that you are a <em>self-paying student</em>, please click the Next button to continue. </p>");
      } else if ($('#decision2').is(':checked')) { 
        $('#secretMsg').html("<p class='text-justify'>You confirmed that you work for a UN organization. Please select your <strong>Organization</strong> below. You can directly search your organization or scroll through the box. When done, click the Next button to continue.</p>");
      }
  });
</script>

@stop