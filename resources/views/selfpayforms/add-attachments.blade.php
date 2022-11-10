@extends('main_no_nav2')

@section('tabtitle', 'Self-Pay Enrolment Form - Add Attachment')

@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="row">
	<div class="col-md-12">
		<form method="POST" action="{{ route('add-attachments-store') }}" class="col-sm-12 form-horizontal form-prevent-multi-submit" enctype="multipart/form-data">
		{{ csrf_field() }}
			<div class="table-responsive col-sm-12 filtered-table">
				<table class="table table-bordered table-striped">
				    <thead>
				        <tr>
				            <th>Status</th>
				            <th>Name</th>
				            <th>Organization</th>
			                <th>Term</th>
			                <th>Course</th>
				            <th>Current ID Proof</th>
				            <th>Current Payment Proof</th>
				        </tr>
				    </thead>
				    <tbody>
						@foreach($selfpayforms as $form)
						<tr>
							<td>
			                	@if(is_null($form->selfpay_approval)) None @elseif( $form->selfpay_approval == 0 ) <span class="alert alert-danger">Disapproved</span> @elseif ($form->selfpay_approval == 1) <span class="badge badge-success">Approved</span> @else <span class="badge badge-warning">Pending</span> @endif   
			                </td>
							<td>
								@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
								<input type="hidden" name="INDEXID" value="{{$form->INDEXID}}">
							
							
							</td>
							<td>
								@if(empty($form->DEPT)) None @else {{ $form->DEPT }}  @endif
								@if($form->DEPT == '999') SPOUSE @endif
								@if ($form->DEPT === 'MSU')
									@if ($form->users->sddextr->countryMission)
									- {{ $form->users->sddextr->countryMission->ABBRV_NAME }} 
									@else 
									- (country update needed)
									@endif
								@endif

								@if ($form->DEPT === 'NGO')
									@if ($form->users->sddextr->ngo_name)
									- {{ $form->users->sddextr->ngo_name }} 
									@else
									- (NGO name update needed)
									@endif
								@endif	
							</td>
			                <td>
			                	{{ $form->terms->Comments }} {{ date('Y', strtotime($form->terms->Term_Begin)) }}
			                	<input type="hidden" name="Term" value="{{$form->Term}}">
			                </td>
			                <td>
			                	{{ $form->courses->Description }}
								<input type="hidden" name="L" value="{{$form->L}}">
								<input type="hidden" name="Te_Code" value="{{$form->Te_Code}}">
								<input type="hidden" name="eform_submit_count" value="{{$form->eform_submit_count}}">
			                </td>
							<td>
								@if(empty($form->filesId->path)) None 
								@else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> 
								<input type="hidden" name="identity_id" value="{{ $form->filesId->id }}">
								@endif
							</td>
							<td>
								@if(empty($form->filesPay->path)) None 
								@else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> 
								<input type="hidden" name="payment_id" value="{{ $form->filesPay->id }}">
								@endif 
							</td>
						</tr>
						@endforeach
				    </tbody>
				</table>
			</div>

			<div class="form-group col-md-12 file-section">
				<div class="col-md-12">
					<h3>Attach your valid documents here</h3>
					<div class="big text-danger">
					<p><strong>Note: accepts pdf, doc, and docx files only. File size must less than 8MB.</strong><p>
					<p><strong>Please select file to upload.</strong><p>
					</div>
				
					@include('selfpayforms.partials-upload-attachment.upload-attachments')

				</div>
			</div>
		</form>
	</div>
</div>
@stop
@section('scripts_code')
<script src="{{ asset('js/upload-attachments.js') }}"></script>
<script>
	$( document ).ready(function() {
		$("button#addFile").prop("hidden", true);
		$("label[for='addFile']").prop("hidden", true);
	});
</script>
@stop