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
@include('admin.partials._termSessionMsg')
<h1 class="text-success">Administrator Dashboard</h1>

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

<div class="col-md-6 col-sm-6 col-xs-12">
	<a href="{{ route('preenrolment.index') }}">
		<div class="info-box bg-aqua">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-aqua"><i class="fa fa-file-o"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Regular Enrolment Forms</span>
		    <span class="info-box-number">@if(Session::has('Term')) Submission Total: {{$enrolment_forms}} @else Set the Term @endif</span>
		    <span class="info-box-text">Total number of submitted forms (approved + unapproved)</span>
		    <span class="info-box-number">.</span>
		    <span class="info-box-number">.</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
</div>
<div class="col-md-6 col-sm-6 col-xs-12">
	<a href="{{ route('placement-form.index') }}">
		<div class="info-box bg-yellow">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-yellow"><i class="fa fa-file"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Placement Forms</span>
		    <span class="info-box-number">@if(Session::has('Term')) Submission Total: {{$placement_forms}} @else Set the Term @endif</span>
		    <span class="info-box-text">Total number of submitted forms (approved + unapproved)</span>
		    <span class="info-box-number">.</span>
		    <span class="info-box-number">.</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
</div>
{{-- <div class="col-md-3 col-sm-6 col-xs-12"> --}}
<div class="col-md-6 col-sm-6 col-xs-12">
	<a href="{{ route('selfpayform.index') }}">
		<div class="info-box bg-purple">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-purple"><i class="fa fa-file-o"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Payment-based Enrolment Forms</span>
		    <span class="info-box-number">@if(Session::has('Term')) Total: {{$selfpay_enrolment_forms}} @else Set the Term @endif</span>
		    <span class="info-box-text">Validated <span class="label label-success">{{ $selfpay_enrolment_forms_validated }}</span></span>
		    <span class="info-box-text">Pending <span class="label label-warning">{{ $selfpay_enrolment_forms_pending }}</span></span>
		    <span class="info-box-text">Disapproved <span class="label label-danger">{{ $selfpay_enrolment_forms_disapproved }}</span></span>
		    <span class="info-box-text">Waiting for Admin <span class="label label-info">{{ $selfpay_enrolment_forms_waiting }}</span></span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
</div>
<div class="col-md-6 col-sm-6 col-xs-12">
	<a href="{{ route('index-placement-selfpay') }}">
		<div class="info-box alert-selfpay">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon alert-selfpay"><i class="fa fa-file"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Payment-based Placement Forms</span>
		    <span class="info-box-number">@if(Session::has('Term')) Total: {{$selfpay_placement_forms}} @else Set the Term @endif</span>
		    <span class="info-box-text">Validated <span class="label label-success">{{ $selfpay_placement_forms_validated }}</span></span>
		    <span class="info-box-text">Pending <span class="label label-warning">{{ $selfpay_placement_forms_pending }}</span></span>
		    <span class="info-box-text">Disapproved <span class="label label-danger">{{ $selfpay_placement_forms_disapproved }}</span></span>
		    <span class="info-box-text">Waiting for Admin <span class="label label-info">{{ $selfpay_placement_forms_waiting }}</span></span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
</div>

<div class="col-md-6 col-sm-6 col-xs-12">
	@if(Session::has('Term')) 
	<a href="{{ route('query-orphan-forms-to-assign') }}">
		<div class="info-box bg-navy">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-navy"><i class="fa  fa-tasks"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Manage ALL Unassigned Enrolment Forms </span>
		    <span class="info-box-number">{{$all_unassigned_enrolment_form}} Form(s)</span>
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

<div class="col-md-6 col-sm-6 col-xs-12">
	@if(Session::has('Term')) 
	<a href="{{ route('placement-form-filtered') }}">
		<div class="info-box bg-orange">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-orange"><i class="fa  fa-files-o"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Manage Non-assigned Placement Forms </span>
		    <span class="info-box-number">{{$countNonAssignedPlacement}} Form(s)</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
	@else 
		<div class="info-box bg-orange">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-orange"><i class="fa  fa-exclamation-circle"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Manage Non-assigned Placement Forms </span>
		    <span class="info-box-number">Set the Term</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	@endif
</div>

<div class="col-md-6 col-sm-6 col-xs-12">
	@if(Session::has('Term')) 
	<a href="{{ route('query-regular-forms-to-assign') }}">
		<div class="info-box bg-teal">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-teal"><i class="fa  fa-files-o"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Manage Unassigned Enrolment Forms </span>
		    <span class="info-box-number">{{$arr3_count}} forms</span>
		    <span class="info-box-text"><small>Shows only students not in a class</small></span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
	@else 
		<div class="info-box bg-teal">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-teal"><i class="fa  fa-exclamation-circle"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Manage Unassigned Enrolment Forms </span>
		    <span class="info-box-number">Set the Term</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	@endif
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
	@if ($new_user_count < 5)
	<a href="{{ route('newuser.index') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-navy"><i class="fa fa-users"></i></span>
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
<div class="col-md-3 col-sm-6 col-xs-12">
	<a href="{{ route('preview-merged-forms') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-navy"><i class="fa fa-eye"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Preview </span>
		    <span class="info-box-number"></span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
	<a href="{{ route('preview-vsa-page-2') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-navy"><i class="fa fa-gears"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Manage Classes </span>
		    <span class="info-box-number"></span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
	@if(Session::has('Term')) 
	<a href="{{ route('preview-waitlisted') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-navy"><i class="fa  fa-exclamation-circle"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Waitlisted Students </span>
		    <span class="info-box-number"></span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
	@else 
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-navy"><i class="fa  fa-exclamation-circle"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Waitlisted Students </span>
		    <span class="info-box-number">Set the Term</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	@endif
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
	<a href="{{ route('cancelled-convocation-view') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-red"><i class="fa fa-remove"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Cancelled Convocations </span>
		    <span class="info-box-number">@if(Session::has('Term')){{$cancelled_convocations}} @else Set the Term @endif</span>
		  </div>
		  <!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</a>
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
	<a href="{{ route('preview-class-status') }}">
		<div class="info-box">
		  <!-- Apply any bg-* class to to the icon to color it -->
		  <span class="info-box-icon bg-navy"><i class="fa fa-list"></i></span>
		  <div class="info-box-content">
		    <span class="info-box-text">Class Table </span>
		    <span class="info-box-number">@if(Session::has('Term')) @else Set the Term @endif</span>
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