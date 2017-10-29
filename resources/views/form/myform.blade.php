@extends('main')
@section('tabtitle', '| MyForm')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <h1>Data Saved to Repo table - showing view(form.myform)</h1>

  <form method="POST" action="{{ route('myform.store') }}" class="well form-horizontal form-prevent-multi-submit">
      {{ csrf_field() }}
      <input  name="user_id" type="input" value="{{ $user->indexno }}" readonly>
      <input  name="term_id" type="input" value="{{ $terms->Term_Code }}" readonly>  
  
      <div class="form-group">
          <label for="" class="col-md-4 control-label">Index Number:</label>

          <div class="col-md-6 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-qrcode"></i></span><input  name="" class="form-control"  type="text" value="{{ Auth::user()->indexno }}" readonly>                                    
              </div>
          </div>
      </div>

      <div class="form-group">
          <label for="" class="col-md-4 control-label">Name:</label>

          <div class="col-md-6 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="" class="form-control"  type="text" value="{{ Auth::user()->name }}" readonly>                                    
              </div>
          </div>
      </div>

      <div class="form-group">
          <label for="name" class="col-md-4 control-label">Manager's Email Address:</label>
          
          <div class="col-md-6 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="name" placeholder="Enter Manager's Email" class="form-control"  type="text">                                    
              </div>
               <p class="small text-danger">Enter the correct email address of your manager because this form will be sent to this email address for approval.</p>
          </div>
      </div>
   
      <div class="form-group">
          <label for="name" class="col-md-4 control-label">Last UN Language Course:</label>

        <div class="col-md-6 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span><input  name="" class="form-control"  type="text" value="{{ 'language course '.'last '.$terms->Term_Name }}" readonly>                                    
              </div>
          </div>
      </div>

      <!-- radio checks -->
      <div class="form-group">
          <label class="col-md-4 control-label">Continue current course?</label>
          <div class="col-md-4">
              <div class="radio">
                  <label>
                      <input type="radio" name="hosting" value="yes" /> Yes
                  </label>
              </div>
              <div class="radio">
                  <label>
                      <input type="radio" name="hosting" value="no" /> No
                  </label>
              </div>
          </div>
      </div>

      <div class="form-group">
          <label name="language_id" class="col-md-4 control-label">Enrol to which language: </label>
          <select class="col-md-6 form-control-static" name="language_id">
              <option value="">Select</option>
              @foreach ($languages as $id => $name)
                  <option value="{{ $id }}"> {{ $name }}</option>
              @endforeach
          </select>
      </div>
      
      <div class="form-group">
          <label name="course_id" class="col-md-4 control-label">Enrol to which course: </label>
          <select class="col-md-6 form-control-static" name="course_id">
              <option value="">--- Select Course ---</option>
          </select>
      </div>

      <div class="col-sm-offset-5">
        <button type="submit" class="btn btn-default button-prevent-multi-submit">Send Enrolment</button>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
      </div>

  </form>
</div>

@stop   

@section('scripts_code')

<script src="{{ asset('js/submit.js') }}"></script>      

<script type="text/javascript">
  $("select[name='language_id']").change(function(){
      var language_id = $(this).val();
      var token = $("input[name='_token']").val();
      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {language_id:language_id, _token:token},
          success: function(data) {
            $("select[name='course_id'").html('');
            $("select[name='course_id'").html(data.options);
          }
      });
  }); 
</script>

@stop