@extends('main_no_nav2')

@section('tabtitle', '| Self-Pay Enrolment Form - Add Attachment')

@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="table-responsive col-sm-12 filtered-table">
		<form method="POST" action="{{ route('add-attachments-placement-store') }}" class="col-sm-12 form-horizontal form-prevent-multi-submit" enctype="multipart/form-data">
		{{ csrf_field() }}
				<table class="table table-bordered table-striped">
				    <thead>
				        <tr>
				            <th>Status</th>
				            <th>Name</th>
				            <th>Organization</th>
			                <th>Term</th>
			                <th>Language</th>
				            <th>Current ID Proof</th>
				            <th>Current Payment Proof</th>
				        </tr>
				    </thead>
				    <tbody>
						@foreach($selfpayforms_placement as $form)
						<tr>
							<td>
			                	@if(is_null($form->selfpay_approval)) None @elseif( $form->selfpay_approval == 0 ) <span class="alert alert-danger">Disapproved</span> @elseif ($form->selfpay_approval == 1) <span class="label label-success">Approved</span> @else <span class="label label-warning">Pending</span> @endif   
			                </td>
							<td>
								@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
								<input type="hidden" name="INDEXID" value="{{$form->INDEXID}}">
							
							
							</td>
							<td>@if($form->DEPT == '999') SPOUSE @else {{ $form->DEPT }} @endif</td>
			                <td>
			                	{{ $form->terms->Comments }} {{ date('Y', strtotime($form->terms->Term_Begin)) }}
			                	<input type="hidden" name="Term" value="{{$form->Term}}">
			                </td>
			                <td>
			                	{{ $form->languages->name }}
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
            <h3>Attach your valid documents here</h3>

              <div class="big text-danger">
                <p><strong>Note: accepts pdf, doc, and docx files only. File size must less than 8MB.</strong><p>
              </div>
            
              <div class="form-group col-md-12">
                <label for="identityfile">Upload Proof of Identity: </label>
                <input name="identityfile" type="file" required="">
              </div>

              <div class="form-group col-md-12">
                <label for="payfile">Upload Proof of Payment: </label>
                <input name="payfile" type="file" required="">
              </div>  

              <div class="col-md-4 col-md-offset-4">
                  <button type="submit" class="btn btn-success btn-block">Submit Files</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
                  {{ method_field('PUT') }}
            </div>
		</form>
        </div>
	</div>
</div>
@stop