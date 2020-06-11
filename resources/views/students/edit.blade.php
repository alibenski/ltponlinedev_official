@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop
@section('content')
    {{-- Modal Dialog Box --}}
    <div class="modal fade" id="modalshow">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <span><h4 class="modal-title" id="memberModalLabel"><i class="fa fa-lg fa-info-circle text-danger btn-space"></i>Changing your CLM Online Email Address</h4></span>
            </div>
            <div class="modal-body">
                <p>Once you change your email address, this will become your <strong>login</strong> and your <strong>official</strong> email address to which we will be sending notifications and other future correspondences. Thus, please be careful when updating the email address field.</p>
                <p>For your security,  after submitting the changes, you will be automatically logged out from the application and  we will send a verification email to the <strong>new email address</strong> you will set here. <strong><em>The email update will not take effect until you confirm.</em></strong></p> 
                <p>Please note that the confirmation is only valid for <strong>24 hours</strong> upon submission of your request.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Continue</button>
            </div>
        </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">Edit Student Profile</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="updateProfileForm" method="POST" action="{{ route('students.update', $student->id) }}" class="form-horizontal">
                        {{ csrf_field() }}
                        
                        <div id="profileSelect" class="form-group">
                          <label for="profile" class="col-md-2 control-label">Profile:</label>
                          <div class="col-md-9">
                            <div class="dropdown">
                              <select id="profile" name="profile" class="col-md-8 form-control select2-basic-single" style="width: 100%;">
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

                        <div class="form-group">
                            <label for="TITLE" class="col-md-2 control-label">Title:</label>

                            <div class="col-md-2 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-venus-mars"></i></span><input  name="TITLE" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->TITLE }} @endif" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastName" class="col-md-2 control-label">Last Name:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span><input  name="lastName" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->LASTNAME }} @endif" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firstName" class="col-md-2 control-label">First Name:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span><input  name="firstName" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->FIRSTNAME }} @endif" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email Address:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    {{-- apply jS or HTML preferred characters for this field --}}
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span><input id="email" name="email" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->email }} @endif" class="form-control"  type="text">                                    
                                </div>
                                <p class="small text-danger"><strong>IMPORTANT NOTE:</strong> Once you change your email address, this will become <strong>your login and your official email address</strong> to which we will be sending notifications and other future correspondences.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="currentOrg" class="col-md-2 control-label">Organization:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-globe"></i></span><input  name="currentOrg" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->torgan['Org name'] }} - {{ $student->sddextr->torgan['Org Full Name'] }} @endif" class="form-control"  type="text" readonly="">
                                </div>
                            </div>
                        </div>

                        <!-- MAKE A DECISION SECTION -->
                
                        <div class="form-group">
                            <label class="col-md-2 control-label">Change Organization?</label>

                              <div class="col-md-8">
                                        <input id="decision1" name="decision" class="with-font dyes" type="checkbox" value="1">
                                        <label for="decision1" class="form-control-static">YES</label> 
                              </div>
                        </div>
                        {{-- insert org dropdown if decision is YES --}}
                        <div id="orgSelect"></div>
                        <input id="selectInput" type="hidden">
                        <div class="form-group">
                            <label for="contactNo" class="col-md-2 control-label">Contact Number:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span><input  name="contactNo" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->PHONE }} @endif" class="form-control"  type="text">                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                            <label for="dob" class="col-md-2 control-label">Date of birth </label>

                            <div class="col-md-6">
                                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dob">
                                <input class="form-control" size="16" type="text" value="" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->BIRTH }} @endif" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                <input type="hidden" name="dob" id="dob" value=""/>

                                @if ($errors->has('dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobAppointment" class="col-md-2 control-label">Type of Appointment:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-folder-open"></i></span><input  name="jobAppointment" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->CATEGORY }} @endif" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gradeLevel" class="col-md-2 control-label">Grade Level:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-folder-open"></i></span><input  name="gradeLevel" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->LEVEL }} @endif" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        {{-- <div class="form-group">
                            <label for="contractExp" class="col-md-2 control-label">Contract Expiration:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->sddextr->CONEXP }}</p>
                            </div>
                        </div> --}}
                        
                        <div class="col-md-4 col-md-offset-4">
                              <button type="submit" class="btn btn-success btn-block">Submit Changes</button>
                              <input type="hidden" name="_token" value="{{ Session::token() }}">
                              {{ method_field('PUT') }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- no further div -->
@endsection

@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script>
  $(document).ready(function() {
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 4,
        forceParse: 0,
        showMeridian: 1,
        minView: 2
    });
  });
</script>
<script>
    $(document).ready(function() {
        $('.select2-basic-single').select2({
          placeholder: "Select Profile",
          });
    });
</script>

<script>
    // Check if at least one input field is filled 
    $(function(){
        $("#updateProfileForm").submit(function(){
            var valid=0;
            $(this).find('input[type=text], #selectInput, select#profile').each(function(){
                if($(this).val() != "") valid+=1;
            });
                        
            if(valid){
                // alert(valid + " input(s) filled. Thank you.");
                return true;
            }
            else {
                alert("Error: you must fill in at least one field.");
                return false;
            }
    });
});
</script>

<script>
$(document).ready(function () {
    $("input[name='decision']").on("click", function(){
        if ($("input[name='decision']").is(':checked')) {
            // ajax call
            $.get("/org-select-ajax", function(data) {
                $('#orgSelect').html(data);
                $(document).find('.select2-basic-single').select2();
                $('#selectInput').val('1');
                console.log($('#selectInput').val());
                });        
        } else {
                console.log('hide it');
                $('#orgSelect').html("");
            }                
    });
    $('#email').one('click', function () {
            $('#modalshow').modal('show');
        });
});

</script>

@stop