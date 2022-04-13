@extends('main')
@section('tabtitle', 'Profile')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
        .overlay {  
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            z-index: 2;
            opacity: 0;
            background: rgba(39, 42, 43, 0.8);
            transition: opacity 200ms ease-in-out;
            border-radius: 4px;
            margin: -15px 0 0 -15px;

        }

    </style>
@stop
@section('content')
    {{-- Modal Dialog Box --}}
    <div class="modal fade" id="modalshow">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <span><h5 class="modal-title" id="memberModalLabel"><i class="fa fa-lg fa-info-circle text-danger btn-space"></i>Changing your CLM Online Email Address</h5></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white"><strong> Edit Student Profile </strong></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="updateProfileForm" method="POST" action="{{ route('students.update', $student->id) }}" class="form-horizontal">
                        {{ csrf_field() }}
                        <div id="profileSelect" class="form-group">
                          <label for="profile" class="col-md-12 control-label">Profile: <span class="text-danger"> * required field start here </span></label>
                        
                          @include('ajax-profile-select')
                          
                        </div>
                        
                      <div id="step2" class="d-none">
                        <div class="form-group">
                            <label for="currentOrg" class="col-md-12 control-label">Current Organization:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-globe"></i></span>
                                </div>
                                <input name="currentOrg" placeholder="@if(empty($student->sddextr)) Update Needed 
                                @else {{ $student->sddextr->torgan['Org name'] }} - {{ $student->sddextr->torgan['Org Full Name'] }} @if (Auth::user()->sddextr->DEPT === 'MSU') @if (Auth::user()->sddextr->countryMission) - {{ Auth::user()->sddextr->countryMission->ABBRV_NAME }} @else - (country update needed) @endif @endif @if (Auth::user()->sddextr->DEPT === 'NGO') @if (Auth::user()->sddextr->ngo_name)- {{ Auth::user()->sddextr->ngo_name }} @else - (NGO name update needed) @endif @endif @endif
                                " class="form-control"  type="text" readonly="">
                                <input name="currentOrganization" type="hidden" value="@if(empty($student->sddextr)) [] @else{{Auth::user()->sddextr->DEPT}}@endif" />
                        
                            </div>
                        </div>

                        <div id="hiddenSection" class="form-group d-none">
                            
                            <div id="hiddenDecision" class="form-group d-none">
                                <!-- MAKE A DECISION SECTION -->
                                <label class="col-md-12 control-label">Change current organization?</label>

                                <div class="col-md-12">
                                    <input id="decision1" name="decision" class="with-font dyes" type="checkbox" value="1">
                                    <label for="decision1" class="form-control-static">YES</label> 
                                </div>
                            </div>

                            {{-- insert org dropdown if decision is YES --}}
                            
                                    <div id="orgSelect" class="col-md-12 mt-2"></div>
                                    <div id="countrySection"></div>
                                    <div id="ngoSection"></div>
                              
                        </div>

                        <input id="selectInput" type="hidden">

                        <div class="form-group">
                            <label for="TITLE" class="col-md-12 control-label">Title:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
                                </div>
                                <input  name="TITLE" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->TITLE }} @endif" class="form-control"  type="text">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastName" class="col-md-12 control-label">Last Name:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input  name="lastName" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->LASTNAME }} @endif" class="form-control"  type="text">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firstName" class="col-md-12 control-label">First Name:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input  name="firstName" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->FIRSTNAME }} @endif" class="form-control"  type="text">
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="email" class="col-md-12 control-label">Email Address:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    {{-- apply jS or HTML preferred characters for this field --}}
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                </div>
                                <input id="email" name="email" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->email }} @endif" class="form-control"  type="text" @if(substr($student->email, strpos($student->email, "@") + 1)   === "un.org") disabled @endif>
                                @if(substr($student->email, strpos($student->email, "@") + 1)   === "un.org") <p class="small text-danger"><strong>IMPORTANT NOTE:</strong> You are using the official un.org email address. You cannot change your email address without approval. Please send an email to the language secretariat (<a href="mailto:webmaster@example.com">click here</a>) and provide the new email address with a justification for the change. </p> @endif                                
                                <p class="small text-danger"><strong>IMPORTANT NOTE:</strong> Once you change your email address, this will become <strong>your login and your official email address</strong> to which we will be sending notifications and other future correspondences.</p>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                            <label for="gender" class="col-md-12 control-label">Gender:
                                @if(empty ( Auth::user()->sddextr )) Update Needed 
                                @else 
                                    @if (strtoupper(Auth::user()->sddextr->SEX) == "M") Male @endif
                                    @if (strtoupper(Auth::user()->sddextr->SEX) == "F") Female @endif
                                    @if (strtoupper(Auth::user()->sddextr->SEX) == "O") Other @endif
                                @endif
                            </label>
                            <div class="col-md-12">
                            <div class="dropdown">
                                <select class="col-md-12 form-control select2-basic-single" style="width: 100%;" id="gender" name="gender" autocomplete="off">
                                    <option value="">--- Please Select ---</option>
                                    <option value="F">Female</option>
                                    <option value="M">Male</option>
                                    <option value="O">Other</option>
                                </select>
                            </div>

                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contactNo" class="col-md-12 control-label">Contact Number:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                </div>
                                <input  name="contactNo" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->PHONE }} @endif" class="form-control"  type="text">                                    
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('dob') ? 'is-invalid' : '' }}">
                            <label for="dob" class="col-md-12 control-label">Date of birth (YYYY-MM-DD)</label>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                        <input type="text" id="dob" name="dob" class="form-control datetimepicker-input" data-target="#datetimepicker4" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->BIRTH }} @endif">

                                        <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                @if ($errors->has('dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobAppointment" class="col-md-12 control-label">Type of Appointment:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-folder-open"></i></span>
                                </div>
                                <input  name="jobAppointment" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->CATEGORY }} @endif" class="form-control"  type="text">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gradeLevel" class="col-md-12 control-label">Grade Level:</label>

                            <div class="input-group col-md-12 inputGroupContainer">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-folder-open"></i></span>
                                </div>
                                <input  name="gradeLevel" placeholder="@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->LEVEL }} @endif" class="form-control"  type="text">
                            </div>
                        </div>
                        
                        <div class="col-md-4 offset-md-4">
                              <button type="submit" class="btn btn-success btn-block">Submit Changes</button>
                              <input type="hidden" name="_token" value="{{ Session::token() }}">
                              {{ method_field('PUT') }}
                        </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- no further div -->
@stop

@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ asset('js/profileOrgValidator.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#datetimepicker4').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2-basic-single').select2({
          placeholder: "Select from dropdown",
          });
        $('.select-profile-single').select2({
          placeholder: "Select Profile Required",
          });
    });
</script>

<script>
    // Check if at least one input field is filled 
    $(function(){
        $("#updateProfileForm").submit(function(){
            var valid=0;
            $(this).find('input[type=text], input.datetimepicker-input, #selectInput, select#profile, select#gender').each(function(){
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

                // disable MSU, NGO, etc options from Org select dropdown
                $("select[name='organization'] option[value='MSU']").prop('disabled', true);
                $("select[name='organization'] option[value='999']").prop('disabled', true);
                $("select[name='organization'] option[value='RET']").prop('disabled', true);
                $("select[name='organization'] option[value='SERV']").prop('disabled', true);
                $("select[name='organization'] option[value='NGO']").prop('disabled', true);
                $("select[name='organization'] option[value='PRESS']").prop('disabled', true);
                $("select[name='organization']").attr("required", true);

                    $("select[name='organization']").on("change", function () {
                        let choice = $("select[name='organization']").val();

                        if (choice == "MSU") {
                            getCountry();

                            $("select[name='profile'] option[value='MSU']").prop('selected', true);
                            $("select[name='profile'] option").not(':selected').prop('disabled', true);
                            $('select[name="profile"]').val('MSU').trigger('change');
                        } else {
                            $("#countrySection").html("");
                        }

                        if (choice == "NGO") {
                            $("#ngoSection").html("<div class='col-md-12'><div class='form-group row'><label for='ngoName' class='col-md-12 control-label text-danger'>NGO Name: <span style='color: red'><i class='fa fa-asterisk' aria-hidden='true'></i> required field</span> </label><div class='col-md-12'><input id='ngoName' type='text' class='form-control' name='ngoName' placeholder='Enter NGO agency name' required></div></div></div>");

                            $("select[name='profile'] option[value='NGO']").prop('selected', true);
                            $("select[name='profile'] option").not(':selected').prop('disabled', true);
                            $('select[name="profile"]').val('NGO').trigger('change');
                        } else {
                            $("#ngoSection").html("");
                        }
                        
                        if (choice == "999") {
                            $("select[name='profile'] option[value='SPOUSE']").prop('selected', true);
                            $("select[name='profile'] option").not(':selected').prop('disabled', true);
                            $('select[name="profile"]').val('SPOUSE').trigger('change');
                        } 

                        if (choice == "RET") {
                            $("select[name='profile'] option[value='RET']").prop('selected', true);
                            $("select[name='profile'] option").not(':selected').prop('disabled', true);
                            $('select[name="profile"]').val('RET').trigger('change');
                        } 

                        if (choice == "SERV") {
                            $("select[name='profile'] option[value='SERV']").prop('selected', true);
                            $("select[name='profile'] option").not(':selected').prop('disabled', true);
                            $('select[name="profile"]').val('SERV').trigger('change');
                        } 

                        if (choice == "PRESS") {
                            $("select[name='profile'] option[value='PRESS']").prop('selected', true);
                            $("select[name='profile'] option").not(':selected').prop('disabled', true);
                            $('select[name="profile"]').val('PRESS').trigger('change');
                        } 

                    });
                });        
        } else {
                console.log('hide it');
                $('#orgSelect').html("");
                $("#countrySection").html("");
                $("#ngoSection").html("");
            }                
    });
    $('#email').one('click', function () {
            $('#modalshow').modal('show');
        });
});

function getCountry() {
    $.ajax({
        url: "{{ route('ajax-select-country') }}", 
        method: 'GET',
        success: function(data, status) {
            console.log(data)
        $("#countrySection").html("");
        $("#countrySection").html(data.options);
        }
    });  
}

</script>

@stop