@extends('layouts.app')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
    html {
    font-family: "Helvetica Neue", sans-serif;
    width: 100%;
    color: #666666;
    text-align: center;
    }

    .popup-overlay {
    /*Hides pop-up when there is no "active" class*/
    visibility: hidden;
    position: absolute;
    background: #ffffff;
    border: 3px solid #666666;
    width: 50%;
    height: 50%;
    left: 25%;
    }

    .popup-overlay.active {
    /*displays pop-up when "active" class is present*/
    visibility: visible;
    text-align: center;
    }

    .popup-content {
    /*Hides pop-up content when there is no "active" class */
    visibility: hidden;
    }

    .popup-content.active {
    /*Shows pop-up content when "active" class is present */
    visibility: visible;
    }

    button {
    display: inline-block;
    vertical-align: middle;
    border-radius: 30px;
    margin: .20rem;
    font-size: 1rem;
    color: #666666;
    background: #ffffff;
    border: 1px solid #666666;
    }

    button:hover {
    border: 1px solid #666666;
    background: #666666;
    color: #ffffff;
    }
    </style>
@stop
@section('content')
@include('partials._messages')
@if (Session::has('warning')) 
    <div class="alert alert-warning alert-block text-center" role="alert">
        <strong>Note: </strong> {{ Session::get('warning') }}
    </div>
@endif
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">External Registration</div>

                <div class="card-body">
                    <form class="form-horizontal form-prevent-multi-submit" enctype="multipart/form-data" method="POST" action="{{ route('post-new-outside-user-form') }}">
                        {{ csrf_field() }}

                        {{-- <div class="form-group{{ $errors->has('indexno') ? 'is-invalid' : '' }}">
                            <label for="indexno" class="col-md-12 control-label">Index # <span class="small text-danger"></span></label>

                            <div class="col-md-12">
                                <input id="indexno" type="text" class="form-control" name="indexno" value="{{ old('indexno') }}" autofocus>

                                @if ($errors->has('indexno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('indexno') }}</strong>
                                    </span>
                                @endif
                                <p class="small text-danger mt-1"><strong>Please delete trailing zeroes if you have an index number which is less than 8 digits e.g. 00012345 -> 12345</strong></p>
                            </div>
                        </div>

                        <hr /> --}}
                        
                        {{-- <div class="form-group">
                            <label for="gender" class="col-md-12 control-label"><span style="color: red" class="form-control-static"><i class="fa fa-asterisk" aria-hidden="true"></i> required field</span></label>
                        </div> --}}
                        
                        @include('users_new.registration_form_fields')

                        <div class="form-group">
                            <div class="col-md-8 offset-md-5">
                                <button type="submit" class="btn btn-primary button-prevent-multi-submit">
                                    Register
                                </button>
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="showModalTitle"><strong> Stop! Before you continue... </strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>If you do not have a <em>@un.org</em> professional/work email address, please enter a personal email address i.e. yahoo, gmail, outlook, etc.</p>
        <p>Thank you for understanding and complying.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Yes, I understand</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('java_script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/profileOrgAssoc.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#datetimepicker4').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    $("select[name='profile']").on("change", function () {
        let profileChoice = $("select[name='profile']").val();
        const profileArray1 = ["STF", "INT", "CON", "WAE", "JPO"];
        console.log(profileChoice);
        if ($.inArray(profileChoice, profileArray1) >= 0) {
            showFileAttach();
        }
        else if (profileChoice == "MSU") {
            showFileAttachMSU();
        } 
        else if (profileChoice == "SPOUSE") {
            showFileAttachSpouse();
        } 
        else if (profileChoice == "RET") {
            showFileAttachRetired();
        } 
        else if (profileChoice == "SERV") {
            showFileAttachServ();
        } 
        else if (profileChoice == "NGO") {
            showFileAttachNgo();
        } 
        else if (profileChoice == "PRESS") {
            showFileAttachPress();
        } 
        else {
            $("#attachSection").html("");
        }
    });

    function showFileAttach() {
        $.ajax({
            url: "{{ route('ajax-file-attach-badge-cdl') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachMSU() {
        $.ajax({
            url: "{{ route('ajax-file-attach-msu') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachSpouse() {
        $.ajax({
            url: "{{ route('ajax-file-attach-spouse') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachRetired() {
        $.ajax({
            url: "{{ route('ajax-file-attach-retired') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachServ() {
        $.ajax({
            url: "{{ route('ajax-file-attach-serv') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachNgo() {
        $.ajax({
            url: "{{ route('ajax-file-attach-ngo') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    function showFileAttachPress() {
        $.ajax({
            url: "{{ route('ajax-file-attach-press') }}", 
            method: 'GET',
            success: function(data, status) {
            $("#attachSection").html("");
            $("#attachSection").html(data.options);
            }
        }); 
    }

    $("select[name='org']").on("change", function () {
        let choice = $("select[name='org']").val();
        if (choice == "MSU") {
            getCountry();
        } else {
            $("#countrySection").html("");
        }
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

    $("select[name='org']").on("change", function () {
        let choice = $("select[name='org']").val();
        if (choice == "NGO") {
            $("#ngoSection").html("<div class='col-md-12'><div class='form-group row'><label for='ngoName' class='col-md-12 control-label text-danger'>NGO Name: <span style='color: red'><i class='fa fa-asterisk' aria-hidden='true'></i> required field</span> </label><div class='col-md-12'><input id='ngoName' type='text' class='form-control' name='ngoName' placeholder='Enter NGO agency name' required></div></div></div>");
        } else {
            $("#ngoSection").html("");
        }
    });

    // $('.email-input').one('click', function () {
    //     $('#showModal').modal('show');
    // });
    
    $('#showModal').on('hidden.bs.modal', function (e) {
        $('input.email-input').focus();
    })
  });
</script>
@stop