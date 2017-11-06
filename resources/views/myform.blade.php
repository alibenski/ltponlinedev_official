@extends('main')
@section('tabtitle', '| MyForm')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <h1>Data Saved to Repo table</h1>

  <form method="POST" action=" " class="well form-horizontal">
      {{ csrf_field() }}

      <div class="form-group">
          <label name="language_id" class="col-md-4 control-label">Enrol to which language: </label>
          <select class="col-md-4 form-control-static" name="language_id">
              <option value="">Select</option>
              @foreach ($languages as $id => $name)
                  <option value="{{ $id }}"> {{ $name }}</option>
              @endforeach
          </select>
      </div>
      
      <div class="form-group">
          <label name="course_id" class="col-md-4 control-label">Enrol to which course: </label>
          <select class="col-md-4 form-control-static" name="course_id">
              <option value="">--- Select Course ---</option>
          </select>
      </div>

      <div class="col-sm-offset-5">
        <button type="submit" class="btn btn-default">Send Enrolment</button>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        {{ method_field('PUT') }}
      </div>

  </form>
</div>
@stop   

@section('scripts_code')
                
<script type="text/javascript">
  $("select[name='language_id']").change(function(){
      var language_id = $(this).val();
      var token = $("input[name='_token']").val();
      $.ajax({
          url: "http://enrol.local/select-ajax", 
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