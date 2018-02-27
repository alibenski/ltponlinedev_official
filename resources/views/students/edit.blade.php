@extends('main')
@section('tabtitle', '| Profile')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
    {{-- Modal Dialog Box --}}
    <div class="modal fade" id="modalshow">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <span><h4 class="modal-title" id="memberModalLabel"><i class="fa fa-lg fa-info-circle text-danger btn-space"></i>Changing your CLM Online E-mail Address</h4></span>
            </div>
            <div class="modal-body">
                <p>Once you change your e-mail address, this will become your <strong>login</strong> and your <strong>official</strong> e-mail address to which we will be sending notifications and other future correspondences. Please be careful and sure when updating the e-mail address field.</p>
                <p>For your security, we will send a verification e-mail to the <strong>new e-mail address</strong> you will set here. The update will not take effect until you confirm. Please note that the confirmation is only valid for 24 hours upon receipt of request.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Continue</button>
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

                        <div class="form-group">
                            <label for="title" class="col-md-2 control-label">Title:</label>

                            <div class="col-md-2 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user-o"></i></span><input  name="title" placeholder="{{ $student->sddextr->TITLE }}" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastName" class="col-md-2 control-label">Last Name:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span><input  name="lastName" placeholder="{{ $student->sddextr->LASTNAME }}" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firstName" class="col-md-2 control-label">First Name:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span><input  name="firstName" placeholder="{{ $student->sddextr->FIRSTNAME }}" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="email" class="col-md-2 control-label">Email Address:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    {{-- apply jS or HTML preferred characters for this field --}}
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span><input id="email" name="email" placeholder="{{ $student->email }}" class="form-control"  type="text">                                    
                                </div>
                                <p class="small text-danger"><strong>IMPORTANT NOTE:</strong> Once you change your e-mail address, this will become <strong>your login and your official e-mail address</strong> to which we will be sending notifications and other future correspondences.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="org" class="col-md-2 control-label">Organization:</label>
                            <div class="col-md-8">
                                <div class="dropdown">
                                  <select name="org" id="input" class="col-md-8 form-control select2-basic-single" style="width: 100%;">

                                    @if(!empty($org))
                                      @foreach($org as $key => $value)
                                        <option value="{{ $key }}" {{ ($student->sddextr->DEPT == $key) ? 'selected="selected"' : '' }}>{{ $value }}</option>
                                      @endforeach
                                    @endif

                                  </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contactNo" class="col-md-2 control-label">Contact Number:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span><input  name="contactNo" placeholder="{{ $student->sddextr->PHONE }}" class="form-control"  type="text">                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jobAppointment" class="col-md-2 control-label">Type of Appointment:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-folder-open"></i></span><input  name="jobAppointment" placeholder="{{ $student->sddextr->CATEGORY }}" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gradeLevel" class="col-md-2 control-label">Professional Grade Level:</label>

                            <div class="col-md-8 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-folder-open"></i></span><input  name="gradeLevel" placeholder="{{ $student->sddextr->LEVEL }}" class="form-control"  type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contractExp" class="col-md-2 control-label">Contract Expiration:</label>

                            <div class="col-md-8 form-control-static">
                                <p>{{ Auth::user()->sddextr->CONEXP }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-md-offset-4">
                              <button type="submit" class="btn btn-success btn-block">Save</button>
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

<script>
    // Check if at least one input field is filled 
    $(function(){
        $("#updateProfileForm", "select").submit(function(){

            var valid=0;
            $(this).find('input[type=text]').each(function(){
                if($(this).val() != "") valid+=1;
            });

            if(valid){
                alert(valid + " input(s) filled. Thank you.");
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
        $('#email').one('click', function () {
            $('#modalshow').modal('show');
        });
  });
</script>

<script>
    $(document).ready(function() {
        $('.select2-basic-single').select2();
        });
</script>

@stop