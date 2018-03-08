@extends('admin.admin')

@section('customcss')
	<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop

@section('content')
<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-snowflake-o'></i> Create Semester Term </h1>
    <hr>
	    <form method="POST" action="{{ route('terms.store') }}">
        {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label">Term Code: </label>
  				  <input name="" type="text" class="form-control" value="">
          </div>
		  
		  <div class="form-group">
            <label class="control-label">Term Name: </label>
  				  <input name="" type="text" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="control-label">Next Term Code: </label>
  				  <input name="" type="text" class="form-control" value="">
          </div>

		<div class="form-group">
            <label class="control-label">Enrolment Date Begin: </label>
  				  <input name="" type="text" class="form-control" value="">
        </div>

		<div class="form-group">
            <label class="control-label">Enrolment Date End: </label>
  				  <input name="" type="text" class="form-control" value="">
        </div>

    <div class="form-group">
                    <label for="dtp_input1" class="control-label">DateTime Picking</label>
                    <div class="input-group date form_datetime col-md-12" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                        <input class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
              <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
            <input type="hidden" id="dtp_input1" value="" /><br/>
          </div>


          <div class="row">
            <div class="col-sm-4 col-md-offset-2">
              <a href="{{ route('terms.index') }}" class="btn btn-danger btn-block">Back</a>
            </div>
            <div class="col-sm-4">
              <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save</button>
              <input type="hidden" name="_token" value="{{ Session::token() }}">
            </div>
          </div>
      </form>
</div>
@stop

@section('java_script')

<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script>
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
</script>

@stop