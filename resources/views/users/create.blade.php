@extends('layouts.adminLTE3.index')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    
@stop
@section('content')
  @include('partials._messages')
  <div class="card">
    <div class="card-header border-0">
      <div class="d-flex justify-content-between">
        <h2>Add User (Auth & SDDEXTR)</h2>
      </div>
    </div>
    <div class="card-body">
      <form class="form-horizontal form-prevent-multi-submit" enctype="multipart/form-data" method="POST" action="{{ route('users.store') }}">
        {{ csrf_field() }}

        @include('users_new.registration_form_fields')
          
        <div class="row">
          <div class="col-sm-4 col-md-offset-2">
            <a href="{{ route('users.index') }}" class="btn btn-danger btn-block">Back</a>
          </div>
          <div class="col-sm-4">
            <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Add User</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
          </div>
        </div>
        <br />
        <div class="form-group">
            <label class="control-label">User will be added to the <span class="text-danger"><a href="/admin/newuser">New User Administration page</a></span></label>
        </div>

      </form>
    </div>
  </div>
  <!-- /.card -->
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