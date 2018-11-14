@extends('admin.admin')

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

<h1 class="text-danger">Administrator Dashboard</h1>

<div class="box box-primary" data-widget="box-widget">
  <div class="box-header">
    <h3 class="box-title">Select Term Session:</h3>
    <div class="box-tools">
      <!-- This will cause the box to be removed when clicked -->
      <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
      <!-- This will cause the box to collapse when clicked -->
      <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
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
        <button type="submit" class="btn btn-success filter-submit-btn" name="submit-filter" value="submit-filter">Submit</button>
        {{-- <a href="/admin" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a> --}}
    </div>
  </div>
</div>


<div class="col-sm-4 col-xs-12">
	@if ($new_user_count < 5)
	<a href="{{ route('newuser.index') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-aqua"><i class="fa fa-star-o"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">New User Access Request </span>
		    <span class="info-box-number">{{ $new_user_count }}</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
	@else
	<a href="{{ route('newuser.index') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">New User Request </span>
		    <span class="info-box-number">{{ $new_user_count }}</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
	@endif
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