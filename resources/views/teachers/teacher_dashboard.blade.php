@extends('teachers.teacher_template')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<style>
  #placeholder {
    width: 500px;
    height: 300px;
  }
  #placeholder2 {
    width: 500px;
    height: 300px;
  }
</style>
@stop

@section('content')

@if(Session::has('Term'))
<div class="callout callout-success col-sm-12">
    <h4>Reminder!</h4>
    <p>
        All <b>Term</b> fields are currently set to: <strong>{{ Session::get('Term') }}</strong>
    </p>
</div>
@else
<a href="{{ route('teacher-dashboard') }}">
<div class="callout callout-danger col-sm-12">
    <h4>Warning!</h4>
    <p>
        <b>Term</b> is not set. Click here to set the Term field for this session.
    </p>
</div>
</a>
@endif

<h1 class="text-success">Dashboard</h1>

<div class="box box-success" data-widget="box-widget">
  <div class="box-header">
    <h3 class="box-title">Set the <b>Term</b> for your session:</h3>
    <div class="box-tools">
      <!-- This will cause the box to be removed when clicked -->
      {{-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> --}}
      <!-- This will cause the box to collapse when clicked -->
      <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <form id="set-term" method="GET" action="{{ route('set-session-term') }}">
      <div class="box-body">
      <div class="form-group">
      <label for="Term" class="col-md-12 control-label"></label>
      <div class="form-group col-sm-12">
          <div class="dropdown">
            <select id="Term" name="Term" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
              @foreach($terms as $value)
                  <option></option>
                  <option value="{{$value->Term_Code}}">{{$value->Term_Code}} - {{$value->Comments}} - {{$value->Term_Name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
      <!-- /.box-body -->
    <div class="box-footer">
      <div class="form-group">           
          <button type="submit" class="btn btn-success filter-submit-btn">Set Term</button>
          {{-- <a href="/admin" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a> --}}
      </div>
    </div>
  </form>
</div>


<div class="col-sm-4 col-xs-12">
  <a href="{{ route('preview-vsa-page-2') }}">
    <div class="info-box">
      <!-- Apply any bg-* class to to the icon to color it -->
      <span class="info-box-icon bg-purple"><i class="fa fa-bar-chart"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Assigned Classes </span>
        <span class="info-box-number"></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </a>
</div>
@endsection

@section('java_script')
<script src="{{ asset('js/submit.js') }}"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "select here",
    });
});
</script>
@stop