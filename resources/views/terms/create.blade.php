@extends('admin.admin')

@section('customcss')
	{{-- <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" media="screen"> --}}
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class='col-lg-12'>
    <h1><i class='fa fa-snowflake-o'></i> Create Semester Term </h1>
    <hr>
	    <form method="POST" action="{{ route('terms.store') }}">
        {{ csrf_field() }}
        <div class="col-md-4">
          <div class="form-group">
            <label for="Term_Code" class="control-label">Term Code: </label>
  				  <input name="Term_Code" type="number" class="form-control" value="" required="">
          </div>

          <div class="form-group">
            <label for="Term_Begin" class="control-label">Term Begin Date: </label>
            <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="Term_Begin">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="Term_Begin" id="Term_Begin" value="" required="" />
          </div>

          <div class="form-group">
            <label for="Term_End" class="control-label">Term End Date: </label>
  				  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="Term_End">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="Term_End" id="Term_End" value="" required=""/>
          </div>

          <div class="form-group">
            <label for="Remind_Mgr_After" class="control-label">Send reminder emails to managers after how many days? </label>
            <input name="Remind_Mgr_After" type="number" class="form-control" value="" required="">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group" style="margin-bottom: 21px;">
            <label for="Comments" class="control-label">Season: </label>
            <div class="dropdown">
              <select name="Comments" id="Comments" style="width: 100%;">
                <option></option>
                @foreach($seasons as $id => $value)
                <option value="{{ $id }}">{{ $value }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="Term_Prev" class="control-label">Previous Term Code: </label>
            <input name="Term_Prev" type="text" class="form-control" value="">
          </div>          

          <div class="form-group">
            <label for="Term_Next" class="control-label">Next Term Code: </label>
            <input name="Term_Next" type="text" class="form-control" value="">
          </div>
          
          <div class="form-group">
            <label for="Remind_HR_After" class="control-label">Send reminder emails to HR partner after how many days? </label>
            <input name="Remind_HR_After" type="number" class="form-control" value="" required="">
          </div>
        </div>

        <div class="col-md-4">
      		<div class="form-group">
                  <label for="Enrol_Date_Begin" class="control-label">Enrolment Date Begin: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="Enrol_Date_Begin">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="Enrol_Date_Begin" id="Enrol_Date_Begin" value="" required=""/>
          </div>

      		<div class="form-group">
                  <label for="Enrol_Date_End" class="control-label">Enrolment Date End: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="Enrol_Date_End">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="Enrol_Date_End" id="Enrol_Date_End" value="" required="" />
          </div>

          <div class="form-group">
                  <label for="Cancel_Date_Limit" class="control-label">Cancellation Date Limit: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="Cancel_Date_Limit">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="Cancel_Date_Limit" id="Cancel_Date_Limit" value="" />
          </div>

          <div class="form-group">
                  <label for="Approval_Date_Limit" class="control-label">Approval Date Limit for Manager: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="Approval_Date_Limit">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="Approval_Date_Limit" id="Approval_Date_Limit" value="" />
          </div>

          <div class="form-group">
                  <label for="Approval_Date_Limit_HR" class="control-label">Approval Date Limit for HR: </label>
                  <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="Approval_Date_Limit_HR">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                  </div>
                  <input type="hidden" name="Approval_Date_Limit_HR" id="Approval_Date_Limit_HR" value="" />
          </div>   
        </div>

          <div class="row">
            <div class="col-md-12">            
              <div class="col-md-2 col-md-offset-4">
                <a href="{{ route('terms.index') }}" class="btn btn-danger btn-block">Back</a>
              </div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-block button-prevent-multi-submit">Save</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
              </div>
            </div>
          </div>
      </form>
</div>
@stop

@section('java_script')

<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script>
  $(document).ready(function() {
    $('select[name="Comments"]').select2({
    placeholder: "Select Season",
    minimumResultsForSearch: Infinity,
    });
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
  });
</script>
@stop