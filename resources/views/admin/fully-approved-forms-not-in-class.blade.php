@extends('admin.admin')
@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')

@if (Session::has('Term'))
	This page should only be accessbile after batch run...
	<div class="row">
		<div class="col-md-12">
			
			@include('admin.partials._dropdownSetSessionTerm')

			<div class="box box-info">
		        <div class="box-header with-border">
		          <h3 class="box-title"><strong>Term: {{ Session::get('Term') }}:</strong> {{count($merge)}} students with fully approved forms but not in a class</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		          </div>
		        </div>
		        <!-- /.box-header -->
		        <div class="box-body">
		          <div class="table-responsive">
		            <table class="table no-margin">
		              <thead>
		              <tr>
		                <th>Index No.</th>
		                <th>Name</th>
		                <th>Form</th>
		                <th>Language</th>
		              </tr>
		              </thead>
		              <tbody>
		              	@if ($studentIndexEnrol->isNotEmpty())
			              	@foreach ($studentIndexEnrol as $element)
				              <tr>
				                <td><a href="{{ route('manage-user-enrolment-data', $element->id) }}" target="_blank">{{ $element->indexno }}</a></td>
				                <td><a href="{{ route('manage-user-enrolment-data', $element->id) }}" target="_blank">{{ $element->name }}</a></td>
				                <td><a href="{{ route('manage-user-enrolment-data', $element->id) }}" target="_blank"><span class="label label-success"><i class="fa fa-file-o"></i></a> Enrolment Form</span></td>
				                <td>
				                	<p>{{ $element->preenrolment->first()->L}}</p>
				                </td>
				              </tr>
			              	@endforeach
		              	@endif
						
						@if ($studentIndexPlacement->isNotEmpty())
			              	@foreach ($studentIndexPlacement as $element)
				              <tr>
				                <td><a href="{{ route('manage-user-enrolment-data', $element->id) }}" target="_blank">{{ $element->indexno }}</a></td>
				                <td><a href="{{ route('manage-user-enrolment-data', $element->id) }}" target="_blank">{{ $element->name }}</a></td>
				                <td><a href="{{ route('manage-user-enrolment-data', $element->id) }}" target="_blank"><span class="label label-success"><i class="fa fa-file"></i></a> Placement Form</span></td>
				                <td>
				                 	<p>{{ $element->placement->first()->L}}</p>
				                </td>
				              </tr>
			              	@endforeach
						@endif

		              </tbody>
		            </table>
		          </div>
		          <!-- /.table-responsive -->
		        </div>
		        <!-- /.box-body -->
		        <div class="box-footer clearfix">
		          <a href="{{ route('admin_dashboard') }}" class="btn btn-sm btn-danger btn-flat pull-left">Back to Dashboard</a>
		        </div>
		        <!-- /.box-footer -->
		    </div>

		</div>
	</div>

	@else
		<a href="{{ route('admin_dashboard') }}">
			<div class="callout callout-danger col-sm-12">
			    <h4>Warning!</h4>
			    <p>
			        <b>Term</b> is not set. Click here to set the Term field for this session.
			    </p>
			</div>
		</a>
@endif

@stop

@section('java_script')

<script src="{{ asset('js/select2.full.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "select here",
    });
});
</script>
@stop