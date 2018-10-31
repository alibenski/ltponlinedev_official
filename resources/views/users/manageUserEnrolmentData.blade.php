@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop


@section('content')
@include('admin.partials._userAdminNav')
<div class="row col-sm-12">
	<h3>Viewing: <strong>{{ $student->name }}</strong></h3>
	<div class="panel panel-primary">
        <div class="panel-heading"><strong>Student Profile</strong></div>
        <div class="panel-body">
			<form class="form-horizontal">
		        <div class="form-group">
		            <label for="title" class="col-md-4 control-label">Title:</label>

		            <div class="col-md-8 form-control-static">
		                <p>@if(empty ( $student->sddextr )) Update Needed @else {{ $student->sddextr->TITLE }} @endif</p>
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="fullName" class="col-md-4 control-label">Full Name:</label>

		            <div class="col-md-8 form-control-static">
		                <p>@if(empty( $student->sddextr )) Update Needed @else {{ $student->sddextr->LASTNAME }}, {{ $student->sddextr->FIRSTNAME }} @endif</p>
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="email" class="col-md-4 control-label">Email Address:</label>

		            <div class="col-md-8 form-control-static">
		                <p>{{ $student->email }}</p>
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="org" class="col-md-4 control-label">Organization:</label>

		            <div class="col-md-8 form-control-static">
		                <p>@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->torgan['Org name'] }} - {{ $student->sddextr->torgan['Org Full Name'] }} @endif</p>
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="contactNo" class="col-md-4 control-label">Contact Number:</label>

		            <div class="col-md-8 form-control-static">
		                <p>@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->PHONE }} @endif</p>
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="jobAppointment" class="col-md-4 control-label">Type of Appointment:</label>

		            <div class="col-md-8 form-control-static">
		                <p>@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->CATEGORY }} @endif</p>
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="gradeLevel" class="col-md-4 control-label">Grade Level:</label>

		            <div class="col-md-8 form-control-static">
		                <p>@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->LEVEL }}@endif</p>
		            </div>
		        </div>

		        {{-- <div class="form-group">
		            <label for="contractExp" class="col-md-4 control-label">Contract Expiration:</label>

		            <div class="col-md-8 form-control-static">
		                <p>{{ $student->sddextr->CONEXP }}</p>
		            </div>
		        </div> --}}

		        <div class="form-group">
		            <label for="course" class="col-md-4 control-label">Last UN Language Course:</label>

		            <div class="col-md-8 form-control-static">
		                <p>
		                    @if(empty ($repos_lang))
		                    None
		                    @else
		                    	@if(empty($repos_lang->Te_Code)) {{ $repos_lang->coursesOld->Description }} 
		                    	@else {{ $repos_lang->courses->Description}}
		                    	@endif 
		                    - {{ $repos_lang->terms->Term_Name }} ({{ $repos_lang->terms->Term_Code }})
		                    @endif 
		                </p>
		            </div>
		        </div>
		        {{-- <div class="col-md-4 col-md-offset-4"><a href="{{ route('students.edit', $student->id) }}" class="btn btn-block btn-info btn-md">Edit my CLM Online Profile</a>
		        </div> --}}
		    </form>
		</div>
		</div>
	<h3>@if(Request::input('Term'))Term: {{ Request::input('Term') }} @else Please Choose Term @endif</h3>
   	<div class="row col-sm-12">
		<form method="GET" action="{{ route('manage-user-enrolment-data', $id) }}">
			
			<div class="form-group input-group col-sm-12">
				<h4><strong>Filters:</strong></h4>

				<div class="form-group">
		          <label for="Term" class="col-md-12 control-label">Term Select:</label>
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

			</div> {{-- end filter div --}}


		    <div class="form-group">           
		        <button type="submit" class="btn btn-success">Submit</button>
		    	<a href="{{ route('manage-user-enrolment-data', $id) }}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
		    </div>
		</form>
	</div>
    @if(empty($student_enrolments))

	@else
		@if(count($student_enrolments) == 0)
		@else
		<div class="filtered-table">
			<h4>Regular Enrolment Form(s) Submitted:</h4>
			<table class="table table-bordered table-striped">
			    <thead>
			        <tr>
			            <th>Name</th>
			            <th>Organization</th>
			            <th>Language</th>
			            <th>Course</th>
			            <th>Schedule</th>
			            <th>Manager Approval</th>
			            <th>HR Approval</th>
			            <th>ID Proof</th>
			            <th>Payment Proof</th>
			            <th>Time Stamp</th>
			        </tr>
			    </thead>
			    <tbody>
					@foreach($student_enrolments as $form)
					<tr>
						<td>
						@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif </td>
						<td>{{ $form->DEPT }}</td>
						<td>{{ $form->L }}</td>
						<td>{{ $form->courses->Description }}</td>
						<td>{{ $form->schedule->name }}</td>
						<td>
							@if($form->is_self_pay_form == 1)
							<span id="status" class="label label-info margin-label">
							N/A - Self Payment</span>
							@elseif(is_null($form->approval))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($form->approval == 1)
							<span id="status" class="label label-success margin-label">
							Approved</span>
							@elseif($form->approval == 0)
							<span id="status" class="label label-danger margin-label">
							Disapproved</span>
							@endif
						</td>
						<td>
						@if(is_null($form->is_self_pay_form))
							@if(in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
								<span id="status" class="label label-info margin-label">
								N/A - Non-paying organization</span>
							@else
								@if(is_null($form->approval) && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 0 && (is_null($form->approval_hr) || isset($form->approval_hr)))
								<span id="status" class="label label-danger margin-label">
								N/A - Disapproved by Manager</span>
								@elseif($form->approval == 1 && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 1 && $form->approval_hr == 1)
								<span id="status" class="label label-success margin-label">
								Approved</span>
								@elseif($form->approval == 1 && $form->approval_hr == 0)
								<span id="status" class="label label-danger margin-label">
								Disapproved</span>
								@endif
							@endif
						@else
						<span id="status" class="label label-info margin-label">
						N/A - Self Payment</span>
						@endif
						</td>
						<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank">carte attachment</a> @endif
						</td>
						<td>
						@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank">payment attachment</a> @endif
						</td>
						<td>{{ $form->created_at}}</td>
					</tr>
					@endforeach
			    </tbody>
			</table>
		</div>
		@endif
	@endif

	@if(empty($student_placements))
	
	@else
		@if(count($student_placements) == 0)
		@else
		<div class="filtered-table">
			<h4>Placement Form(s) Submitted:</h4>
			<table class="table table-bordered table-striped">
			    <thead>
			        <tr>
			            <th>Name</th>
			            <th>Organization</th>
			            <th>Language</th>
			            <th>Exam Date</th>
			            <th>Manager Approval</th>
			            <th>HR Approval</th>
			            <th>ID Proof</th>
			            <th>Payment Proof</th>
			            <th>Time Stamp</th>
			        </tr>
			    </thead>
			    <tbody>
					@foreach($student_placements as $form)
					<tr>
						<td>
						@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif </td>
						<td>{{ $form->DEPT }}</td>
						<td>{{ $form->L }}</td>
						<td>{{ $form->placementSchedule->date_of_plexam }}</td>
						<td>
							@if($form->is_self_pay_form == 1)
							<span id="status" class="label label-info margin-label">
							N/A - Self Payment</span>
							@elseif(is_null($form->approval))
							<span id="status" class="label label-warning margin-label">
							Pending Approval</span>
							@elseif($form->approval == 1)
							<span id="status" class="label label-success margin-label">
							Approved</span>
							@elseif($form->approval == 0)
							<span id="status" class="label label-danger margin-label">
							Disapproved</span>
							@endif
						</td>
						<td>
							@if(is_null($form->is_self_pay_form))
								@if(in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
								<span id="status" class="label label-info margin-label">
								N/A - Non-paying organization</span>
							@else
								@if(is_null($form->approval) && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 0 && (is_null($form->approval_hr) || isset($form->approval_hr)))
								<span id="status" class="label label-danger margin-label">
								N/A - Disapproved by Manager</span>
								@elseif($form->approval == 1 && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 1 && $form->approval_hr == 1)
								<span id="status" class="label label-success margin-label">
								Approved</span>
								@elseif($form->approval == 1 && $form->approval_hr == 0)
								<span id="status" class="label label-danger margin-label">
								Disapproved</span>
								@endif
							@endif
						@else
						<span id="status" class="label label-info margin-label">
						N/A - Self Payment</span>
						@endif
						</td>
						<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank">carte attachment</a> @endif
						</td>
						<td>
						@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank">payment attachment</a> @endif
						</td>
						<td>{{ $form->created_at}}</td>
					</tr>
					@endforeach
			    </tbody>
			</table>
		</div>
		@endif
	@endif

</div>
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "--- Select Here ---",
    });
});
</script>
@stop