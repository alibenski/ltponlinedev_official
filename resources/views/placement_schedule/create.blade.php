@extends('admin.admin')

@section('customcss')
  {{-- <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" media="screen"> --}}
  <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <h2>Create Placement Test Schedule</h2>
    <h5 class="alert alert-info alert-block">On this page, language administrators create the placement test schedules</h5>
    <hr>

    <form method="POST" action="{{ route('placement-schedule.store') }}">
          <div class="col-md-8">
            <div class="panel panel-default">
              <div class="panel-heading">
                <strong>Basic Info</strong>
              </div>
                <div class="panel-body">
                  <div class="form-group">
                    <label name="term" class="col-md-3 control-label" style="margin: 5px 5px;">Term: </label>
                      <select class="col-md-8 form-control select2-basic-single" name="term" autocomplete="off" required="required" style="width: 100%">
                          <option value="">--- Select Term ---</option>
                          @foreach ($terms as $value)
                              <option value="{{$value->Term_Code}}">{{$value->Term_Code}} {{$value->Comments}} - {{$value->Term_Name}}</option>
                          @endforeach
                      </select>
                  </div>
                  
                  <div class="form-group">
                      <label name="L" class="col-md-3 control-label" style="margin: 5px 5px;">Language: </label>
                      <select class="col-md-8 form-control select2-basic-single" name="L" autocomplete="off" required="required">
                          <option value="">--- Select Language ---</option>
                          @foreach ($languages as $id => $name)
                              <option value="{{ $id }}"> {{ $name }}</option>
                          @endforeach
                      </select>
                  </div>

                </div>
            </div>   
          </div>

          <div class="col-md-4">  
            <div class="panel panel-default">
              <div class="panel-heading">
                <strong>Format</strong>
              </div>
                <div class="panel-body">
                    <table class="table">
                      <thead>
                        <th>Type of Test</th>
                      </thead>
                      <tbody>
                          <tr>
                            <td>
                              <div class="radio">
                                  <label> 
                                      <input id="online" type="radio" name="format_id" value="1" required="" /> Online
                                  </label>
                              </div>
                              <div class="radio">
                                  <label> 
                                      <input id="written" type="radio" name="format_id" value="0" required="" /> Written
                                  </label>
                              </div> 
                            </td>
                          </tr>
                      </tbody>
                    </table>
                </div>
            </div>
          </div>

          <div class="well col-md-12" style="margin-top: 20px;">
              <div class="row">
                <div class="col-md-12">
                  <button id="addDate" class="btn btn-success btn-space col-md-2 pull-right" style="display: none;">Add</button>
                  <table class="table">
                    <thead>
                      <th>Pick Date</th>
                    </thead>
                    <tbody>
                        <tr>
                          <td>
                            <button id="addDate" class="btn btn-success btn-space col-md-2 pull-right" style="display: none;">Add</button>
                            <div class="col-md-5">
                              <div class="form-group date_of_plexam">
                                <label for="date_of_plexam" class="control-label">Placement Test Date(s): </label>
                                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="date_of_plexam">
                                  <input class="form-control" size="16" type="text" value="" readonly>
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                <input type="hidden" name="date_of_plexam[]" id="date_of_plexam" value="" required=""/>
                                <input type="hidden" id="incrementValue" value="1" required=""/>
                              </div>

                              <div class="form-group date_of_plexam_end" style="display: none">
                                <label for="date_of_plexam_end" class="control-label">Placement Test Date End (For Online Only): </label>
                                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="date_of_plexam_end">
                                  <input class="form-control" size="16" type="text" value="" readonly>
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                                <input type="hidden" name="date_of_plexam_end" id="date_of_plexam_end" value=""/>
                              </div>
                            </div>
                          </td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
              <div class="well col-md-6 col-md-offset-3">
                <div class="row">
                  <div class="col-md-5 col-md-offset-1">
                    <a href="{{ route('placement-schedule.index') }}" class="btn btn-danger btn-block btn-space">Back</a>
                  </div>
                  <div class="col-md-5 ">  
                    <button id="setVal" type="submit" class="btn btn-success btn-block button-prevent-multi-submit btn-space">Save</button>
                    <input id="tokenSession" type="hidden" name="_token" value="{{ Session::token() }}">
                  </div>
                </div>
              </div>
    </form>
  </div>
</div>﻿

@stop

@section('java_script')
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>

<script>
  $(document).ready(function(){
    $('input[type=checkbox]').prop('checked',false);
    $('input[type=radio]').prop('checked',false);
  });
</script>

<script>
  $(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "--- Select ---",
    });
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minView: 2, 
    });
  });
</script>

<script>
  $("#online").on('click', function() {
    $("input").not("#written, #online, #incrementValue, #tokenSession").val("");
    $(".date_of_plexam_end").show();
    $("#addDate").hide();
    $('.addSectionDate').remove();
  });
  $("#written").on('click', function() {
    $("input").not("#written, #online, #incrementValue, #tokenSession").val("");
    $('.date_of_plexam_end').hide();
    $("#addDate").show();
    $('.date_of_plexam').append('<div class="addSectionDate"></div>');
  });
  $("#addDate").on('click touchstart', function(event) {
    event.preventDefault();
    var incrementValue = +$("#incrementValue").val() + 1;
    $("#incrementValue").val(incrementValue);
    $('.addSectionDate').append('<div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="date_of_plexam' + incrementValue + '">' +
          '<input class="form-control" size="16" type="text" value="" readonly>' +
          '<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>' +
          '<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>' +
        '</div>' 
        + '<input type="hidden" name="date_of_plexam[]" id="date_of_plexam' + incrementValue + '" value="" required=""/>'
    );

    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minView: 2, 
    });
  });

</script>
@stop