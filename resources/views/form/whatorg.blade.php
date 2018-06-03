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
                  <p class="text-justify">Welcome to the <strong>CLM Online Language Training Programme (LTP) Enrolment</strong> page. Please refer to the information found <a href="https://learning.unog.ch/node/1301#position1" target="_blank"><strong>HERE</strong></a> to read the FAQs regarding enrolment eligibility.</p>
                  <p>Please find and choose from the latest available courses and their schedules <a href="https://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/ClassSchedule_en.pdf" target="_blank"><strong>HERE</strong></a> before proceeding below. </p>                  
                </div>

                <!-- MAKE A DECISION SECTION -->
                
                <div class="form-group">
                    <label class="col-md-4 control-label">Are you a self-paying student?</label>

                      <div class="col-md-4">
                                <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" required="required">
                                <label for="decision1" class="form-control-static">YES, I am paying for my enrolment</label>
                      </div>

                      <div class="col-md-4">
                                <input id="decision2" name="decision" class="with-font dno" type="radio" value="0" required="required">
                                <label for="decision2" class="form-control-static">NO, my organization is paying for my enrolment</label>
                      </div>
                </div>

                <div id="secretMsg1" class="col-md-12 alert alert-info" style="display: none">
                  <p>You confirmed that you are a <em>self-paying student</em>. Please follow the instructions below:</p>
                  <ol>
                    <li>Choose from the latest available courses and their schedules <a href="https://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/ClassSchedule_en.pdf" target="_blank">HERE</a></li>
                    <li>Prepare a copy of proof of payment</li>
                    <li>Prepare a copy of your carte de légitimation or work certificate</li>
                  </ol>
                  <p>After following the instructions, please fill out the field(s) below and click the Next button to continue.</p>
                  {{-- end of id="secretMsg1"  --}}
                </div>


                <div id="secretMsg2" class="col-md-12 alert alert-info" style="display: none">
                  <p class='text-justify'>You confirmed that you work for a UN organization. Please fill out the field(s) below. You can directly search your organization or scroll through the box. When done, click the Next button to continue.</p>
                  {{-- end of id="secretMsg2"  --}}
                </div>
                
                <div id="profileSelect" class="form-group" style="display: none">
                  <label for="profile" class="col-md-2 control-label">Profile:</label>
                  <div class="col-md-9">
                    <div class="dropdown">
                      <select id="profile" name="profile" class="col-md-8 form-control select-profile-single" style="width: 100%;" required="required">
                            <option></option>
                            <option value="1">UN Staff Member</option>
                            <option value="2">Intern</option>
                            <option value="3">Consultant</option>
                            <option value="4">Staff of Permanent Mission</option>
                            <option value="5">Spouse of Staff from UN or Mission</option>
                            <option value="6">Retired UN Staff Member</option>
                            <option value="7">Staff of Service Organizations in the Palais</option>
                            <option value="8">Staff of UN-accredited NGO's and Press Corps</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div id="secretMsg3" class="col-md-12 alert alert-danger" style="display: none">
                  {{-- end of id="secretMsg3"  --}}
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
                            <option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                    <p class="small text-danger"><strong>Please check that you select the correct Organization in this field.</strong></p>
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

<div class="modal fade" id="modalshow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ooops! Just a moment...</h4>
            </div>
            <form method="POST" action="{{ route('whatform') }}" class="form-horizontal form-prevent-multi-submit">{{ csrf_field() }}
              <div class="modal-body">
                <p>It looks like you are a new student or you have changed organizations since your last enrolment. Please confirm and click the Next button.</p>
                <label for="organization">New Organization:</label> <input id="inputOrg" name="" type="text" value="" readonly="">
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

@stop   

@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/submit.js') }}"></script>

<script>
  $(document).ready(function(){
    $.ajaxSetup({ cache: false }); // or iPhones don't get fresh data
    $("input[name='decision']").prop('checked', false);
  });
</script>

<script>
  $("input[name='decision']").click(function(){
      if($('#decision1').is(':checked')) {
        // reset select2 (4.0.3) value to NULL and show placeholder  
        $('.select-profile-single').val([]).trigger('change');
        $('#orgSelect').attr('style', 'display: none');
        $('.select2-basic-single').val([]).trigger('change');
        $('#secretMsg3').attr('style', 'display: none');        
        $('#secretMsg3').find('p').remove();        
        $('a.next-link').replaceWith('<button id="formBtn" type="submit" class="btn btn-block button-prevent-multi-submit">Next</button> <input type="hidden" name="_token" value="{{ Session::token() }}">');
        $('button[type="submit"]').addClass( "btn-success", 500);
        $('#profileSelect, #secretMsg2').fadeOut(500);
        $('#secretMsg1, #profileSelect').fadeIn(800);
      } else if ($('#decision2').is(':checked')) {
        // reset select2 (4.0.3) value to NULL and show placeholder  
        $('.select-profile-single').val([]).trigger('change');
        $('#orgSelect').attr('style', 'display: none');
        $('.select2-basic-single').val([]).trigger('change');
        $('#secretMsg3').attr('style', 'display: none');        
        $('#secretMsg3').find('p').remove();  
        $('button[id="formBtn"]').replaceWith('<a class="btn btn-success next-link btn-default btn-block button-prevent-multi-submit">Next</a>');
        $('a.next-link').removeClass( "btn-success", 500);
        $('#profileSelect, #secretMsg1').fadeOut(500);
        $('#secretMsg2, #profileSelect').fadeIn(800);
      }
  });

  // on event change of Profile, show orgSelect
  $('select[id="profile"]').on('change', function() {
    $('.select2-basic-single').val([]).trigger('change');
    var dProfile = $(this).val(); 
    console.log('Profile selected: ' + dProfile);
    if (dProfile === '1' || dProfile === '2' || dProfile === '3') {
      $('#secretMsg3').fadeOut(300); 
      $('#secretMsg3').find('p').remove(); 
      $('#orgSelect').fadeIn(300);
    } else {
      if (dProfile === '4') {
        $('#secretMsg3').find('p').remove(); 
        $('#secretMsg3').removeAttr('style');
        $('#secretMsg3').html("<p class='text-justify'>Please select <strong>MSU</strong> as your organization.</p>");
        $('#orgSelect').fadeIn(300);
      }
      if (dProfile === '5') {
        $('#secretMsg3').find('p').remove(); 
        $('#secretMsg3').removeAttr('style');
        $('#secretMsg3').html("<p class='text-justify'>Please select <strong>SPOUSE</strong> as your organization.</p>");
        $('#orgSelect').fadeIn(300);
      }
      if (dProfile === '6') {
        $('#secretMsg3').find('p').remove(); 
        $('#secretMsg3').removeAttr('style');
        $('#secretMsg3').html("<p class='text-justify'>Please select <strong>XXX</strong> as your organization.</p>");
        $('#orgSelect').fadeIn(300);
      }
      if (dProfile === '7') {
        $('#secretMsg3').find('p').remove(); 
        $('#secretMsg3').removeAttr('style');
        $('#secretMsg3').html("<p class='text-justify'>Please select <strong>XXX</strong> as your organization.</p>");
        $('#orgSelect').fadeIn(300);
      }
      if (dProfile === '8') {
        $('#secretMsg3').find('p').remove(); 
        $('#secretMsg3').removeAttr('style');
        $('#secretMsg3').html("<p class='text-justify'>Please select <strong>PRESS</strong> as your organization.</p>");
        $('#orgSelect').fadeIn(300);
      }
    }
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    //  select2 dropdown init
    $('.select-profile-single').select2({
    placeholder: "Select Profile",
    });

    $('.select2-basic-single').select2({
    placeholder: "Select Organization",
    });
    // ajax post on change event of Org dropdown
    $('select[id="input"]').change(function() {
      var dOrg = $('select[id="input"]').val();
      var dProfile = $('select[id="profile"]').val();
      var dDecision = $('input[name="decision"]:checked').val();
      var token = $('meta[name=csrf-token]').attr('content');
      // do not execute ajax and modal if value of selection is NULL
      if (dOrg != null) {
        $.post("/org-compare-ajax", { 'organization':dOrg, '_token':token }, function(data) {
              if (data === false) {
                $('#modalshow').modal('show');
                $('input[id="inputOrg"]').attr('name','organization');
                $('input[name="organization"]').attr('value', dOrg);
                $('input[id="inputProfile"]').attr('value', dProfile);
                $('input[id="inputDecision"]').attr('value', dDecision);
                console.log('profile: ' + dProfile);
                console.log('decision: ' + dDecision);
              } 
              if (data === true) {
                $('select[id="input"]').attr('name','organization');
                $('a.next-link').replaceWith('<button id="formBtn" type="submit" class="btn btn-block button-prevent-multi-submit">Next</button> <input type="hidden" name="_token" value="{{ Session::token() }}">');
                $('button[type="submit"]').addClass( "btn-success", 800);
              }
        });
      } else {
        console.log('reset value to ' + dOrg);
      }
    });
  });
</script>

@stop