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
@endif

<h1 class="text-success">Dashboard</h1>
<div class="preloader hidden"></div>
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

@if(count($assigned_classes) > 0)
<a href="{{ route('teacher-view-classrooms') }}"> 
  <div class="col-sm-4 col-xs-12">
      <div class="info-box">
        <!-- Apply any bg-* class to to the icon to color it -->
        <span class="info-box-icon bg-purple"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Assigned Classes </span>
          <span class="info-box-number"> {{ count($assigned_classes) }} </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
  </div>
</a>

@else
<div class="col-sm-4 col-xs-12">
    <div class="info-box">
      <!-- Apply any bg-* class to to the icon to color it -->
      <span class="info-box-icon bg-purple"><i class="fa fa-bar-chart"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Assigned Classes </span>
        <span class="info-box-number">Set Term</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
@endif

@hasrole('Teacher FP')
  @if(count($all_classes) > 0)
  <a href="{{ route('teacher-view-all-classrooms') }}"> 
    <div class="col-sm-4 col-xs-12">
        <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-aqua"><i class="fa fa-columns"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">View All {{Auth::user()->teachers->languages->name}} Classes </span>
            <span class="info-box-number"> {{ count($all_classes) }} </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
  </a>

  @else
  <div class="col-sm-4 col-xs-12">
      <div class="info-box">
        <!-- Apply any bg-* class to to the icon to color it -->
        <span class="info-box-icon bg-aqua"><i class="fa fa-columns"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">View All {{Auth::user()->teachers->languages->name}} Classes </span>
          <span class="info-box-number">Set Term</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
  </div>
  @endif
@endhasrole

@hasrole('Teacher FP')
  @if(Session::has('Term'))
  <a href="{{ route('preview-merged-forms') }}"> 
    <div class="col-sm-4 col-xs-12">
        <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-navy"><i class="fa fa-list"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Preview for Term: <span class="lead"><strong>{{Session::get('Term')}}</strong></span></span>
            <span class="info-box-number"></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
  </a>

  @else
  <div class="col-sm-4 col-xs-12">
      <div class="info-box">
        <!-- Apply any bg-* class to to the icon to color it -->
        <span class="info-box-icon bg-navy"><i class="fa fa-list"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Preview </span>
          <span class="info-box-number">Set Term</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
  </div>
  @endif
@endhasrole

@hasrole('Teacher FP')
<div class="col-sm-4 col-xs-12">
  <div class="preloader hidden"></div>
  @if(Session::has('Term')) 
  <a class="link-to-orphans" href="{{ route('query-orphan-forms-to-assign') }}">
    <div class="info-box bg-navy">
      <!-- Apply any bg-* class to to the icon to color it -->
      <span class="info-box-icon bg-navy"><i class="fa  fa-tasks"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Manage ALL Unassigned Enrolment Forms </span>
        <span class="info-box-number">Term: {{Session::get('Term')}}</span>
        <span class="info-box-text">Shows ALL regular enrolment forms</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </a>
  @else 
    <div class="info-box bg-navy">
      <!-- Apply any bg-* class to to the icon to color it -->
      <span class="info-box-icon bg-navy"><i class="fa  fa-exclamation-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Manage ALL Unassigned Enrolment Forms </span>
        <span class="info-box-number">Set the Term</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  @endif
</div>
@endhasrole

@hasrole('Teacher FP')
  @if(Session::has('Term'))
  <a href="{{ route('placement-form-filtered') }}"> 
    <div class="col-sm-4 col-xs-12">
        <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-yellow"><i class="fa fa-file"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Placement Forms for Term: <span class="lead"><strong>{{Session::get('Term')}}</strong></span></span>
            <span class="info-box-number"></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
  </a>

  @else
  <div class="col-sm-4 col-xs-12">
      <div class="info-box">
        <!-- Apply any bg-* class to to the icon to color it -->
        <span class="info-box-icon bg-yellow"><i class="fa fa-file"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Placement Forms </span>
          <span class="info-box-number">Set Term</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
  </div>
  @endif
@endhasrole

@endsection

@section('java_script')
<script src="{{ asset('js/submit.js') }}"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "select here",
    });

    $("a.link-to-orphans").not('[target="_blank"]').click(function() {
      $(".preloader").removeClass('hidden');
    });
});
</script>
@stop