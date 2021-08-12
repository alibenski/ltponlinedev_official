@extends('main')
@section('tabtitle', 'UN Enrolment Form')
@section('customcss')
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <style>
    .select2-results .select2-disabled,  .select2-results__option[aria-disabled=true] { display: none; }
    </style>
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-header bg-info text-white">Enrolment Form for: 
            <strong>
              @if(empty($next_term))
              Enrolment Period Closed
              @else 
              {{ $next_term->Term_Name.' - '.$next_term->Comments.' Term' }}
              @endif
            </strong>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('whatform') }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <div class="form-group col-md-12">
                  <p>Hello <strong>{{ Auth::user()->name }},</strong></p>
                  <p class="text-justify">Welcome to the <strong>UNOG-CLM Language Training Programme (LTP) Online Enrolment Platform</strong>. Please refer to the information found <a href="https://learning.unog.ch/node/1301#position1" target="_blank"><strong>HERE</strong></a> to read the FAQs regarding enrolment eligibility.</p>
                  <p>Please find and choose from the latest courses available and their schedules <a href="https://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/ClassSchedule_en.pdf" target="_blank"><strong>HERE</strong></a> before proceeding below. </p>                  
                </div>

                <!-- MAKE A DECISION SECTION -->
                
                <div class="d-flex form-group">
                    <label class="col-md-3 control-label">Enrolment Type:</label>

                      <div class="col-md-5">
                                <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" required="required">
                                <label for="decision1" class="form-control-static">I am paying for my course / I am initially paying then my organization will reimburse</label>
                      </div>

                      <div class="col-md-4">
                                <input id="decision2" name="decision" class="with-font dno" type="radio" value="0" required="required">
                                <label for="decision2" class="form-control-static">My organization is paying for my course</label>
                      </div>
                </div>

                <div id="secretMsg1" class="col-md-12 alert alert-info" style="display: none">
                  <p>Before continuing, please follow the following instructions:</p>
                  <ol>
                    <li>Choose from the latest courses available and their schedules <a href="https://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/ClassSchedule_en.pdf" target="_blank">HERE</a></li>
                    <li>Prepare a copy of proof of payment (<a href="https://learning.unog.ch/node/1301#position5" target="_blank">how to pay</a>)</li>
                    <li>Prepare a copy of your carte de légitimation or work certificate</li>
                  </ol>
                  <p>After following the instructions, please fill in the fields below and click the "Next" button to continue.</p>
                  {{-- end of id="secretMsg1"  --}}
                </div>


                <div id="secretMsg2" class="col-md-12 alert alert-info" style="display: none">
                  <p class='text-justify'>You confirmed that you work for a UN organization which is paying for your enrolment. Please fill in the fields below. You can directly search for your organization or scroll through the box. When done, click the "Next" button to continue.</p>
                  {{-- end of id="secretMsg2"  --}}
                </div>
                
                <div id="profileSelect" class="form-group" style="display: none">
                  <label for="profile" class="col-md-2 control-label">Profile:</label>
                  <div class="col-md-9">
                    <div class="dropdown">
                      <select id="profile" name="profile" class="col-md-8 form-control select-profile-single" style="width: 100%;" required="required">
                            <option></option>
                            <option value="STF">Staff Member</option>
                            <option value="INT">Intern</option>
                            <option value="CON">Consultant</option>
                            <option value="WAE">When Actually Employed</option>
                            <option value="JPO">JPO</option>
                            <option value="MSU">Staff of Permanent Mission</option>
                            <option value="SPOUSE">Spouse of Staff from UN or Mission</option>
                            <option value="RET">Retired UN Staff Member</option>
                            <option value="SERV">Staff of Service Organizations in the Palais</option>
                            <option value="NGO">Staff of UN-accredited NGO's</option>
                            <option value="PRESS">Staff of UN Press Corps</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div id="orgSelect" class="form-group" style="display: none"> 
                    <label for="organization" class="col-md-2 control-label">Organization:</label>
                  <div class="col-md-9">
                    <div class="dropdown">
                      <select id="input" name="" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
                        @if(!empty($org))
                          @foreach($org as $value)
                            <option></option>
                            {{-- <option value="{{ $key }}" {{ (Auth::user()->sddextr->DEPT == $key) ? 'selected="selected"' : '' }}>{{ $value }}</option> --}}
                            <option value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{ $value['Org Full Name'] }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <p class="small text-danger"><strong>You can search for your organization directly or scroll through the box / drop-down menu.</strong></p>
                  </div>
                </div>

                <div class="pull-right col-md-2">
                  <a class="btn next-link btn-default btn-block button-prevent-multi-submit">Next</a>
                </div>
            </form>
          </div>
        </div>
    </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" id="modalshow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ooops! Just a moment...</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form method="POST" action="{{ route('whatform') }}" class="form-horizontal form-prevent-multi-submit">{{ csrf_field() }}
              <div class="modal-body">
                <p>It looks like you are a new student or you have changed organizations since your last enrolment. Please confirm and click the Next button.</p>
                <label for="organization">New Organization:</label> <input id="textOrg" type="text" value="" readonly="" style="width: 100%"> <input id="inputOrg" name="" type="hidden" value="" readonly="">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                    {{-- <input id="decision2" name="decision" type="hidden" value="0"> --}}
                    <button id="modalBtn" type="submit" class="btn btn-success button-prevent-multi-submit">Next</button>
                    <input id="inputProfile" name="profile" type="hidden" value="">
                    <input id="inputDecision" name="decision" type="hidden" value="">
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
              </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="showModal2" tabindex="-1" role="dialog" aria-labelledby="showModal2Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="showModal2Title"><strong> Stop! Before you continue... </strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>If you are not using a <em>@un.org</em> professional/work email address, please change it to a personal email address i.e. yahoo, gmail, outlook, etc.</p>
        <p>Please disregard if you already have done so. Thank you for understanding and complying.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close </button>
        <a href="{{ route('students.edit', Auth::user()->id) }}" class="btn btn-primary">Edit my email address</a>
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
    // $('#showModal2').modal('show');
    $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data

    //  select2 dropdown init
    $('.select-profile-single').select2({
    placeholder: "Select Profile",
    });

    $('.select2-basic-single').select2({
    placeholder: "Select Organization",
    });

    $("input[name='decision']").prop('checked', false);
  });
</script>

<script>
  $("input[name='decision']").click(function(){
      if($('#decision1').is(':checked')) {
        $('#profile option:gt(1)').removeAttr('disabled');
        $('.select-profile-single').select2({
          placeholder: "Select Profile",
          });
      } else if ($('#decision2').is(':checked')) {
        $('#profile option:gt(2)').attr('disabled', 'disabled'); 
        $('.select-profile-single').select2({
          placeholder: "Select Profile",
          });
      }  
    });
</script>

<script src="{{ asset('js/whatOrg.js') }}"></script>
@stop