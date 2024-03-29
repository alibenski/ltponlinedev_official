@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop

@section('content')

<div class='col-sm-10 col-md-offset-1'>

    <h1><i class='fa fa-user-plus'></i> Create New Teacher</h1>
    <hr>
	    <form method="POST" action="{{ route('teachers.store') }}">
        {{ csrf_field() }}
        <div class="col-md-12">
          <!-- MAKE A DECISION SECTION -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Does the teacher have a valid Index from Umoja?</h3>
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
        </div>
          <div class="form-group{{ $errors->has('profile') ? 'is-invalid' : '' }}">
              <label for="profile">Profile:</label>
              @include('ajax-profile-select')
          </div>

          <div class="form-group{{ $errors->has('title') ? 'is-invalid' : '' }}">
              <label for="title">Title:</label>
              <div class="col-md-12">
                <select name="title" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" required="required" autocomplete="off">
                    <option></option>
                    <option value="Mr.">Mr.</option>
                    <option value="Ms.">Ms.</option>
                </select>

                <div class="col-md-6">
                    @if ($errors->has('title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>
              </div>
          </div>

          <div class="form-group">
            <label class="control-label">First Name: </label>
            <div class="col-md-12">
  				  <input name="nameFirst" type="text" class="form-control" value="{{ old('nameFirst') }}">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label">Last Name: </label>
            <div class="col-md-12">
            <input name="nameLast" type="text" class="form-control" value="{{ old('nameLast') }}">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label">Email: </label>
            <div class="col-md-12">
  				  <input name="email" type="email" class="form-control" value="{{ old('email') }}" readonly onfocus="this.removeAttribute('readonly');">
            </div>
          </div>

			    <div class='form-group'>
			    		<label class="control-label">Role: </label>
              <div class="col-md-12">
                <div class="checkbox">
                    <label>
                      @foreach ($roles as $role)
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" /><strong>{{ ucfirst($role->name) }}</strong>
                        <br>
                      @endforeach
                    </label>
                </div>
              </div>
			    </div>

          <div class='form-group'>
              <label class="control-label">Language: </label>
              <div class="checkbox">
                <div class="col-md-12">
                  <label>
                    @foreach ($languages as $language)
                      <input type="checkbox" name="L" value="{{ $language->code }}" /><strong>{{ ucfirst($language->name) }}</strong>
                      <br>
                    @endforeach
                  </label>
                </div>
              </div>
          </div>

          <div class="form-group{{ $errors->has('gender') ? 'is-invalid' : '' }}">
              <label for="gender" class=" control-label">Gender</label>
              <div class="col-md-12">
                <div class="dropdown">
                    <select class="form-control select2-basic-single" style="width: 50%;" name="gender" autocomplete="off" >
                        <option value="">--- Please Select ---</option>
                        <option value="F">Female</option>
                        <option value="M">Male</option>
                    </select>
                </div>

                <div class="col-md-6">
                    {{-- <input id="gender" type="text" class="form-control" name="gender" value="{{ old('gender') }}" required autofocus> --}}

                    @if ($errors->has('gender'))
                        <span class="help-block">
                            <strong>{{ $errors->first('gender') }}</strong>
                        </span>
                    @endif
                </div>
              </div>
          </div>

          <div class="form-group{{ $errors->has('org') ? 'is-invalid' : '' }}">
              <label for="org" class=" control-label">Organization</label>
              <div class="col-md-12">

                  {{-- <input id="org" type="text" class="form-control" name="org" value="{{ old('org') }}" required autofocus> --}}

                  <div class="dropdown">
                      <select class="form-control select2-basic-single" style="width: 100%;" name="org" autocomplete="off" >
                          <option value="">--- Please Select Organization ---</option>
                              @if(!empty($org))
                                @foreach($org as $value)
                                  <option class="wx" value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{$value['Org Full Name']}}</option>
                                @endforeach
                              @endif
                      </select>
                  </div>

                  @if ($errors->has('org'))
                      <span class="help-block">
                          <strong>{{ $errors->first('org') }}</strong>
                      </span>
                  @endif
              </div>
          </div>
          
          <div class="form-group{{ $errors->has('dob') ? 'is-invalid' : '' }}">
              <label for="dob" class="control-label">Date of Birth <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i></span></label>
              <div class="col-md-12">

                  <div class="input-group date form_datetime" data-date="" data-date-format="dd MM yyyy" data-link-field="dob">
                  <input class="form-control" size="16" type="text" value="" readonly>
                  <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                  <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="dob" id="dob" value="" required=""/>

                  @if ($errors->has('dob'))
                      <span class="help-block">
                          <strong>{{ $errors->first('dob') }}</strong>
                      </span>
                  @endif
              </div>
          </div>

          <div class="form-group{{ $errors->has('contact_num') ? 'is-invalid' : '' }}">
              <label for="contact_num" class=" control-label">Contact Number</label>
                <div class="col-md-12">
                  <input id="contact_num" type="text" class="form-control" name="contact_num" value="{{ old('contact_num') }}" required autofocus>

                  @if ($errors->has('contact_num'))
                      <span class="help-block">
                          <strong>{{ $errors->first('contact_num') }}</strong>
                      </span>
                  @endif
                </div>
          </div>

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
<script src="{{ asset('js/submit.js') }}"></script>
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
  $("input[name='decision']").click(function(){
      if($('#decision1').is(':checked')) {
        $('.indexno-section').removeClass('hidden');
        $('.message-section').addClass('hidden');
      } else if ($('#decision2').is(':checked')) {
        $('.indexno-section').addClass('hidden');
        $('.message-section').removeClass('hidden');
      }  
    });
</script> 
@stop