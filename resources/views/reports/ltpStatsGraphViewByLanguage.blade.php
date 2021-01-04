@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="row">
  <div class="col-md-12">
    <canvas id="myAreaChart" width="1200" height="400"></canvas>
  </div>
</div>

<div class="row">
  <p><i class="fa fa-info-circle"></i> <small>Values above show all <strong>students enrolled</strong> to the classes in a specific year per language course excluding waitlisted and cancelled</small></p>
</div>

<div class="row">
  <div class="col-md-12">
    <canvas id="myAreaChart2" width="1200" height="400"></canvas>
  </div>
</div>

<div class="row">
  <p><i class="fa fa-info-circle"></i> <small>Values above show the number of <strong>classrooms created/generated</strong> in a specific term per language course excluding waitlist classrooms</small></p>
</div>

<div class="row">
  <div class="col-md-12">
    <canvas id="myAreaChart3" width="1200" height="400"></canvas>
  </div>
</div>

<div class="row">
  <p><i class="fa fa-info-circle"></i> <small>Values above show the total number of <strong>classrooms created/generated</strong> in a specific term excluding waitlist classrooms</small></p>
</div>
@stop

@section('java_script')

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script src="{{asset('js/chart-ltp-stats-by-language.js')}}"></script>
<script src="{{asset('js/chart-classes-term-language.js')}}"></script>
<script src="{{asset('js/chart-total-classes-per-term.js')}}"></script>

@stop