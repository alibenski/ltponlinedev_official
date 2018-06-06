@extends('admin.admin')

@section('customcss')
	{{-- <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" media="screen"> --}}
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop

@section('content')
<div class='col-lg-12'>
    <h1><i class='fa fa-snowflake-o'></i> Edit Semester Term {{ $term->Term_Code }}</h1>
    <hr>
	    <form method="POST" action="{{ route('terms.update', $term->Term_Code) }}">
        {{ csrf_field() }}
        <div class="col-md-4">
          <div class="form-group">
            <label for="termCode" class="control-label">Term Code: </label>
  				  <input name="termCode" type="text" class="form-control" value="" required="">
          </div>

          <div class="form-group">
            <label for="termBeginDate" class="control-label">Term Begin Date: </label>
            <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="termBeginDate">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="termBeginDate" id="termBeginDate" value="" />
          </div>

          <div class="form-group">
            <label for="termEndDate" class="control-label">Term End Date: </label>
  				  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="termEndDate">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="termEndDate" id="termEndDate" value="" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="season" class="control-label">Season: </label>
            <div class="dropdown">
              <select name="season" id="season" style="width: 100%;">
                @foreach($seasons as $id => $value)
                <option value="{{ $id }}">{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="col-md-4">
      		<div class="form-group">
                  <label for="enrolBeginInput" class="control-label">Enrolment Date Begin: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="enrolBeginInput">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="enrolBeginInput" id="enrolBeginInput" value="" />
          </div>

      		<div class="form-group">
                  <label for="enrolEndInput" class="control-label">Enrolment Date End: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="enrolEndInput">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="enrolEndInput" id="enrolEndInput" value="" />
          </div>

          <div class="form-group">
                  <label for="cancelDateInput" class="control-label">Cancellation Date Limit: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="cancelDateInput">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="cancelDateInput" id="cancelDateInput" value="" />
          </div>  
        </div>

          <div class="row">
            <div class="col-md-12">            
              <div class="col-md-4 col-md-offset-2">
                <a href="{{ route('terms.index') }}" class="btn btn-danger btn-block">Back</a>
              </div>
              <div class="col-md-4">
                <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                {{ method_field('PUT') }}
              </div>
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