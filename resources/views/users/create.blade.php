@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <style>
      html {
      font-family: "Helvetica Neue", sans-serif;
      width: 100%;
      color: #666666;
      text-align: left;
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

<div class='col-sm-10 col-md-offset-1'>

    <h1><i class='fa fa-user-plus'></i> Add User (Auth & SDDEXTR)</h1>
    <hr>
	    <form method="POST" action="{{ route('users.store') }}">
        {{ csrf_field() }}
          <!-- MAKE A DECISION SECTION -->
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Does the user have a valid Index from Umoja?</h3>
          </div>
          <div class="panel-body">
          <div class="form-group">

                  <div class="col-sm-12">
                            <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" required="required" autocomplete="off">
                            <label for="decision1" class="form-control-static">Yes</label>
                  </div>

                  <div class="col-sm-12">
                            <input id="decision2" name="decision" class="with-font dno" type="radio" value="0" required="required" autocomplete="off">
                            <label for="decision2" class="form-control-static">No</label>
                  </div>
            </div>

            <div class="indexno-section hidden"> {{-- start of hidden fields --}}

              <div class="form-group">
                <label class="control-label">Index: </label>
                <input name="indexno" type="text" class="form-control" value="">
              </div>

            </div> {{-- end of hidden fields --}}

            <div class="message-section hidden"> {{-- start of hidden fields --}}

              <div class="form-group">
                <h4><span class="label label-info">EXT Index will be generated automatically</span></h4>
              </div>

            </div> {{-- end of hidden fields --}}
          </div>
        </div> {{-- end of panel --}}

          @include('users_new.registration_form_fields')

          <div class="form-group">
            <label class="control-label">Password is automatically set to <span class="text-danger">"Welcome2CLM"</span></label>
    				{{-- <input name="password" type="password" class="form-control" value=""> --}}
          </div>

          {{-- <div class="form-group">
            <label class="control-label">Confirm Password: </label>
    				<input name="password_confirmation" type="password" class="form-control" value="">
          </div> --}}
          
          <div class="row">
            <div class="col-sm-4 col-md-offset-2">
              <a href="{{ route('users.index') }}" class="btn btn-danger btn-block">Back</a>
            </div>
            <div class="col-sm-4">
              <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Add User</button>
              <input type="hidden" name="_token" value="{{ Session::token() }}">
            </div>
          </div>
      </form>
</div>

@stop

@section('java_script')
<script src="{{ asset('js/app.js') }}"></script>
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