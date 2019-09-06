@extends('admin.admin')
@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')

@if (Session::has('Term'))
	{{-- This view should only be accessbile after batch run... --}}
	<div class="row">
		<div class="col-md-12">
			
			@include('admin.partials._dropdownSetSessionTerm')

			<div class="box box-info">
		        <div class="box-header with-border">
		          <h3 class="box-title"><strong>Term: [{{ Session::get('Term') }}] {{$termSet->Comments}} {{ date('Y', strtotime($termSet->Term_Begin)) }} - </strong> {{count($merge)}} Student(s) with fully approved forms and assigned by teacher/admin but not inserted/placed in a class
		          </h3>
		          	<small class="text-danger"><p>Note: This view is only avaiable after the batch has run. The purpose of this view is to show the number of students who are not in a class or not in the waitlist after batch run due to various reasons such as late registration, late payment, etc.</p>
		          		<p>Please click on the Index No. or Name to open the User Administration view of the student.</p></small>

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
		                <th>Assigned by</th>
		                <th>Assigned to</th>
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
				                	@if (!is_null($element->preenrolment->first()->modified_by))
						                <td>
						                	<p>
						                		{{ $element->preenrolment->first()->modifyUser['name']}}
						                	</p>
						                </td>
						                <td>
						                	@if (!is_null($element->preenrolment->first()->Te_Code))
						                	<p>
						                		{{ $element->preenrolment->first()->courses['Description']}}
						                	</p>
						                	@endif
						                </td>
				                	@endif
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
				                	@if (!is_null($element->placement->first()->modified_by))
						                <td>
						                	<p>
						                		{{ $element->placement->first()->modifyUser['name']}}
						                	</p>
						                </td>
						                <td>
						                	@if (!is_null($element->placement->first()->Te_Code))
						                	<p>
						                		{{ $element->placement->first()->courses['Description']}}
						                	</p>
						                	@endif
						                </td>
				                	@endif
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