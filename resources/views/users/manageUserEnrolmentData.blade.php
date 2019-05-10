@extends('admin.admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop


@section('content')
@include('admin.partials._userAdminNav')
<div class="row col-sm-12">
	<a href="{{ route('users.index') }}" class="btn btn-danger btn-space"><span class="glyphicon glyphicon-arrow-left"></span> Back to User Admin</a>
	<button type="button" class="show-modal btn btn-info btn-space" data-toggle="modal"><span class="glyphicon glyphicon-user"></span>  View Student Profile</button>
	<button type="button" class="show-modal-history btn btn-primary btn-space" data-toggle="modal"><span class="glyphicon glyphicon-time"></span>  View History</button>
	<button type="button" class="show-modal-placement-history btn bg-orange btn-space" data-toggle="modal"><span class="glyphicon glyphicon-list-alt"></span> Placement Tests & Results</button>
	<a href="{{ route('enrol-student-to-course-form', $id) }}" class="btn btn-success btn-space"><span class="glyphicon glyphicon-pencil"></span>  Create Enrolment Form </a>
	<a href="{{ route('enrol-student-to-placement-form', $id) }}" class="btn btn-warning btn-space"><span class="glyphicon glyphicon-pencil"></span>  Create Placement Form</a>
	
	<h3>Viewing: <strong>{{ $student->name }}</strong> [{{ $student->indexno }}]</h3>

	<h3>@if(Request::input('Term'))Term: {{ Request::input('Term') }} - {{ $term_info->Comments }} {{ date('Y', strtotime($term_info->Term_Begin )) }}@else Please Choose Term @endif</h3>
   	<div class="row">
   		<div class="col-sm-12">
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
			        <button type="submit" class="btn btn-success">View Forms</button>
			    	<a href="{{ route('manage-user-enrolment-data', $id) }}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
			    </div>
			</form>
   		</div>
	</div>

	@if(!Request::has('Term'))

	@else

		@if (count($student_convoked) > 0)
		      
		  <div class="row">
		      <div class="col-sm-12">
		          <div class="panel panel-success">
		              <div class="panel-heading"><strong>Convocation Details</strong></div>

		              <div class="panel-body">
		                <p>
		                  @foreach ($student_convoked as $element)
		                  <h3><strong>@if(!empty($element->courses->Description)){{ $element->courses->Description }}@endif</strong> <a href="{{ route('view-classrooms-per-section', ['Code' => $element->CodeClass]) }}" target="_blank"><i class="fa fa-external-link"></i></a></h3>
		                  
		                  <p>Schedule: <strong>@if(!empty($element->schedules->name)){{$element->schedules->name}}@endif</strong></p>  

		                    @if(!empty($element->classrooms->Te_Mon_Room))
		                    <p>Monday Room: <strong>{{ $element->classrooms->roomsMon->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Tue_Room))
		                    <p>Tuesday Room: <strong>{{ $element->classrooms->roomsTue->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Wed_Room))
		                    <p>Wednesday Room: <strong>{{ $element->classrooms->roomsWed->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Thu_Room))
		                    <p>Thursday Room: <strong>{{ $element->classrooms->roomsThu->Rl_Room }}</strong></p>
		                    @endif
		                    @if(!empty($element->classrooms->Te_Fri_Room))
		                    <p>Friday Room: <strong>{{ $element->classrooms->roomsFri->Rl_Room }}</strong></p>
		                    @endif

		                  <p>
		                    @if($element->classrooms->Tch_ID == 'TBD')
		                    <h4><span class="label label-danger"> Waitlisted</span></h4> 
		                    @elseif(empty($element->classrooms->Tch_ID))
		                    <h4><span class="label label-danger"> Waitlisted</span></h4> 
		                    @else 
		                    Teacher: <strong>{{ $element->classrooms->teachers->Tch_Name }} </strong>
		                    @endif
		                  </p>
		                  <br> 
		                  	@if($element->classrooms->Tch_ID == 'TBD')
		                  	@elseif(empty($element->classrooms->Tch_ID))
		                    @else
		                    <form method="POST" action="{{ route('cancel-convocation', [$element->CodeIndexIDClass]) }}" class="form-prevent-multi-submit">
		                        <input type="submit" value="@if($element->deleted_at) Cancelled @else Cancel Enrolment @endif" class="btn btn-danger btn-space button-prevent-multi-submit" @if($element->deleted_at) disabled="" @else @endif>
		                        {{-- name="deleteTerm" attribute for LimitCancelPeriod middleware --}}
		                        <input type="hidden" name="deleteTerm" value="{{ $element->Term }}">
		                        <input type="hidden" name="_token" value="{{ Session::token() }}">
		                       {{ method_field('DELETE') }}
		                    </form>
		                    @endif
		                  @endforeach
		                </p>
		              </div>
		      </div>
		  </div>
		@else
		@endif 
		{{-- EOF convoked info --}}

			@if(count($student_enrolments) == 0)
				<div class="row">
					<div class="col-sm-5 center-block">
						<div class="alert alert-info alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-ban"></i> Alert Message!</h4>
							No <u>Regular Enrolment form</u>(s) submitted.
						</div>
					</div>
				</div>
			@else
			@endif

			@if(count($student_placements) == 0)
				<div class="row">
					<div class="col-sm-5 center-block">
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-ban"></i> Alert Message!</h4>
							No <u>Placement Test Request form</u>(s) submitted.
						</div>
					</div>
				</div>
			@else
			@endif

		    @if(empty($student_enrolments))

			@else
				@if(count($student_enrolments) == 0)
				
				@else
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive filtered-table">
							<h4><strong>Regular Enrolment Form(s) Submitted:</strong></h4>
							<table class="table table-bordered table-striped">
							    <thead>
							        <tr>
							            <th>Operation</th>
							            <th>Validated/Assigned Course?</th>
							            <th>Name</th>
							            <th>Course</th>
							            <th>Schedule</th>
							            <th>Organization</th>
							            <th>Student Cancelled?</th>
							            <th>HR Approval</th>
							            <th>Payment Status</th>
							            <th>ID Proof</th>
							            <th>Payment Proof</th>
							            <th>Comment</th>
							            <th>Time Stamp</th>
							            <th>Cancel Time Stamp</th>
							        </tr>
							    </thead>
							    <tbody>
									@foreach($student_enrolments as $key=>$form)
									<tr @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
										<td>
												@if($form->deleted_at)
												@else
													<button type="button" class="btn btn-primary btn-space assign-course" data-toggle="modal"><i class="fa fa-upload"></i> Assign Course</button> 
													@if ($batch_implemented > 0)
														@if ($form->updated_by_admin == 1)
															<button id="insert-{{ $form->INDEXID }}-{{ $form->Te_Code }}-{{ $form->Term }}" type="button" class="btn btn-default btn-space insert-to-class" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-language="{{ $form->L }}" data-tecode="{{ $form->Te_Code }}"><i class="fa fa-plus-circle"></i> Insert to Class</button>
														@endif
													@endif

												@endif

												
												@if (is_null($form->deleted_at))
												<button type="button" class="btn btn-danger btn-space course-delete" data-toggle="modal"><i class="fa fa-remove"></i> Reject/Cancel Enrolment</button>
												@else
												<button type="button" class="btn btn-danger btn-space course-delete-tooltip" title="{{$form->admin_eform_cancel_comment}}" disabled=""><i class="fa fa-info-circle"></i> Cancelled</button>
													@if ($form->admin_plform_cancel_comment)
														<p><small>Admin Comment: "{{$form->admin_plform_cancel_comment}}"</small></p>
													@endif
												@endif
												
											<div id="modalDeleteEnrolment-{{ $form->INDEXID }}-{{ $form->Te_Code }}-{{ $form->Term }}" class="modal fade" role="dialog">
											    <div class="modal-dialog">
											        <div class="modal-content">

											            <div class="modal-header bg-danger">
											                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
											                <h4 class="modal-title">Admin Course Cancellation</h4>
											            </div>
											            <div class="modal-body-course-delete">
											            	<div class="col-sm-12">	
												            	<form method="POST" action="{{ route('enrolment.destroy', [$form->INDEXID, $form->Te_Code, $form->Term, $form->form_counter]) }}">

																	<p>Index # {{ $form->INDEXID }} : {{ $form->users->name }}</p>
																	<p>Language: {{ $form->languages->name }}</p>
																	<p>Course : {{ $form->courses->Description }}</p>
												            		<div class="form-group">
																		<label class="control-label">Admin Comments: </label>

																		<textarea id="course-delete-textarea-{{$form->eform_submit_count}}" name="admin_eform_cancel_comment" class="form-control course-delete-by-admin" maxlength="3500" placeholder="Place important information about the cancellation of this form..."></textarea>
																		
																	</div>

												                    <input type="submit" @if (is_null($form->deleted_at))
												                      value="Reject/Cancel Enrolment"
												                    @else
												                      value="Cancelled"
												                    @endif  class="btn btn-danger btn-space" 
												                    @if (is_null($form->deleted_at))
												                    @else
												                      disabled="" 
												                    @endif>
												                    <input type="hidden" name="deleteTerm" value="{{ $form->Term }}">
												                    <input type="hidden" name="_token" value="{{ Session::token() }}">
												                    {{ method_field('DELETE') }}
												                </form>
											            	</div>
											            </div>
											            <div class="modal-footer modal-background">
											              
											            </div>
											        
											        </div>
											    </div>
											</div>
											
										</td>
										<td>
											@if($form->updated_by_admin == 1)
			                                	<span class="label label-success margin-label">Yes by {{ $form->modifyUser->name}}</span>
			                                @else
												<span class="label label-danger margin-label">Not Assigned </span>
			                                @endif
										</td>
										<td>
											@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif 
											<input type="hidden" name="indexid" value="{{$form->INDEXID}}">	
								            <input type="hidden" name="L" value="{{$form->L}}">
											<input type="hidden" name="Te_Code_Input" value="{{$form->Te_Code}}">
										</td>
										<td>{{ $form->courses->Description }}</td>
										<td>
											<a id="modbtn" class="btn btn-default btn-space" data-toggle="modal" href="#modalshow" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-approval="{{ $form->approval }}" data-formx="{{ $form->form_counter }}" data-mtitle="{{ $form->courses->EDescription }}"><span><i class=""></i></span> View </a>
										</td>
										<td>{{ $form->DEPT }}</td>
										<td>
											@if( is_null($form->cancelled_by_student))
											@else <span id="status" class="label label-danger margin-label">YES</span>
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
											N/A - Self-Payment</span>
											@endif											
										</td>
										<td>
											@if(is_null($form->is_self_pay_form))
						                    <span id="status" class="label label-info margin-label">N/A</span>
						                    @else
						                      @if($form->selfpay_approval === 1)
						                      <span id="status" class="label label-success margin-label">Approved</span>
						                      @elseif($form->selfpay_approval === 2)
						                      <span id="status" class="label label-warning margin-label">Pending Valid Document</span>
						                      @elseif($form->selfpay_approval === 0)
						                      <span id="status" class="label label-danger margin-label">Disapproved</span>
						                      @else 
						                      <span id="status" class="label label-info margin-label">Waiting for Admin</span>
						                      @endif
						                    @endif
										</td>
										<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
										</td>
										<td>
										@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
										</td>
										<td>
											@if ($form->std_comments)
												<button type="button" class="show-std-comments btn btn-primary btn-space" data-toggle="modal"> View </button>
											@endif
											<input type="hidden" name="eform_submit_count" value="{{$form->eform_submit_count}}">
											<input type="hidden" name="term" value="{{$form->Term}}">
											<input type="hidden" name="indexno" value="{{$form->INDEXID}}">
											<input type="hidden" name="tecode" value="{{$form->Te_Code}}">
										</td>
										<td>{{ $form->created_at}}</td>
										<td>{{ $form->deleted_at}}</td>
									</tr>
									@endforeach
							    </tbody>
							</table>
						</div>
					</div>
				</div>
				@endif
			@endif

			@if(empty($student_placements))
			
			@else
				@if(count($student_placements) == 0)

				@else
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive filtered-table">
							<h4><strong>Placement Form(s) Submitted:</strong></h4>
							<table class="table table-bordered table-striped">
							    <thead>
							        <tr>
							            <th>Operation</th>
							            <th>Validated/Assigned Course?</th>
							            <th>Name</th>
							            <th>Language</th>
							            <th>HR Approval</th>
							            <th>Payment Status</th>
							            <th>Student Cancelled?</th>
							            <th>Organization</th>
							            <th>Exam Date</th>
							            <th>ID Proof</th>
							            <th>Payment Proof</th>
							            <th>Time Stamp</th>
							            <th>Cancel Time Stamp</th>
							        </tr>
							    </thead>
							    <tbody>
									@foreach($student_placements as $form)
									<tr @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
										<td>
											@if($form->deleted_at)
											@else
												<a class="btn btn-info btn-space" data-toggle="modal" href="#modalshowplacementinfo" data-mid ="{{ $form->id }}" data-mtitle="Placement Form Info"><span><i class="fa fa-eye"></i></span> View Info</a>
												@if ($batch_implemented > 0)
													@if (!is_null($form->CodeIndexID) && $form->updated_by_admin == 1)
													<button type="button" class="btn btn-default btn-space insert-to-class" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-language="{{ $form->L }}" data-tecode="{{ $form->Te_Code }}"><i class="fa fa-plus-circle"></i> Insert to Class</button>
													@endif
												@endif
											@endif
											

											@if (is_null($form->deleted_at))
											<button type="button" class="btn btn-danger btn-space placement-delete" data-toggle="modal"><i class="fa fa-remove"></i> Reject/Cancel Placement Test</button>
											@else
											<button type="button" class="btn btn-danger btn-space course-delete-tooltip" title="{{$form->admin_plform_cancel_comment}}" disabled=""><i class="fa fa-info-circle"></i> Cancelled</button>
												@if ($form->admin_plform_cancel_comment)
													<p><small>Admin Comment: "{{$form->admin_plform_cancel_comment}}"</small></p>
												@endif
											@endif

											

											<div id="modalDeletePlacement-{{ $form->id }}" class="modal fade" role="dialog">
											    <div class="modal-dialog">
											        <div class="modal-content">

											            <div class="modal-header bg-danger">
											                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
											                <h4 class="modal-title">Admin Placement Cancellation</h4>
											            </div>
											            <div class="modal-body-placement-delete">
											            	<div class="col-sm-12">	
												            	<form method="POST" action="{{ route('placement.destroy', [$form->INDEXID, $form->L, $form->Term, $form->eform_submit_count]) }}">
																	
																	<p>Index # {{ $form->INDEXID }} : {{ $form->users->name }}</p>
																	<p>Placement Form : {{ $form->languages->name }}</p>
												            		<div class="form-group">
																		<label class="control-label">Admin Comments: </label>

																		<textarea id="placement-delete-textarea-{{$form->eform_submit_count}}" name="admin_plform_cancel_comment" class="form-control placement-delete" maxlength="3500" placeholder="Place important information about the cancellation of this form..."></textarea>
																		
																	</div>

												                    <input type="submit" @if (is_null($form->deleted_at))
												                      value="Reject/Cancel Placement Form"
												                    @else
												                      value="Cancelled"
												                    @endif  class="btn btn-danger btn-space" 
												                    @if (is_null($form->deleted_at))
												                    @else
												                      disabled="" 
												                    @endif>
												                    <input type="hidden" name="deleteTerm" value="{{ $form->Term }}">
												                    <input type="hidden" name="_token" value="{{ Session::token() }}">
												                    {{ method_field('DELETE') }}
												                </form>
											            	</div>
											            </div>
											            <div class="modal-footer modal-background">
											              
											            </div>
											        
											        </div>
											    </div>
											</div>
										</td>
										<td>
											@if($form->updated_by_admin == 1)
			                                	<span class="label label-success margin-label">Yes by {{ $form->modifyUser->name}}</span>
			                                @else
												<span class="label label-danger margin-label">Not Assigned </span>
			                                @endif
										</td>
										<td>
										@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif </td>
										<td>{{ $form->L }}</td>
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
											N/A - Self-Payment</span>
											@endif
										</td>
										<td>
											@if(is_null($form->is_self_pay_form))
						                    <span id="status" class="label label-info margin-label">N/A</span>
						                    @else
						                      @if($form->selfpay_approval === 1)
						                      <span id="status" class="label label-success margin-label">Approved</span>
						                      @elseif($form->selfpay_approval === 2)
						                      <span id="status" class="label label-warning margin-label">Pending Valid Document</span>
						                      @elseif($form->selfpay_approval === 0)
						                      <span id="status" class="label label-danger margin-label">Disapproved</span>
						                      @else 
						                      <span id="status" class="label label-info margin-label">Waiting for Admin</span>
						                      @endif
						                    @endif
										</td>
										<td>
											@if( is_null($form->cancelled_by_student))
											@else <span id="status" class="label label-danger margin-label">YES</span>
											@endif
										</td>
										<td>{{ $form->DEPT }}</td>
										<td>
											@if ($form->placementSchedule->is_online == 1) Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} 
											@else {{ $form->placementSchedule->date_of_plexam }} 
											@endif
										</td>
										<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
										</td>
										<td>
										@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
										</td>
										<td>{{ $form->created_at}}</td>
										<td>{{ $form->deleted_at}}</td>
									</tr>
									@endforeach
							    </tbody>
							</table>
						</div>
					</div>
				</div>
				@endif
			@endif
		
	@endif
</div>
<!-- Modal form to show student profile -->
<div id="showModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            		
                	<div class="panel panel-primary">
				        <div class="panel-heading"><strong>Student Profile </strong></div>
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
						                    - {{ $repos_lang->terms->Term_Name }} ({{ $repos_lang->terms->Term_Code }}) (@if($repos_lang->Result == 'P') Passed @elseif($repos_lang->Result == 'F') Failed @elseif($repos_lang->Result == 'I') Incomplete @else -- @endif)
						                    @endif 
						                </p>
						            </div>
						        </div>
						        {{-- <div class="col-md-4 col-md-offset-4"><a href="{{ route('students.edit', $student->id) }}" class="btn btn-block btn-info btn-md">Edit my CLM Online Profile</a>
						        </div> --}}
						    </form>
						</div>
					</div>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal form to show history -->
<div id="showModalHistory" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title-history"></h4>
            </div>
            <div class="modal-body">

	            {{-- <div class="panel panel-info"> --}}
	                {{-- <div class="panel-heading"><strong>Past Language Course Enrolment for {{ $student->name }}
	                </div> --}}
	                <div class="panel-body panel-info">
	                    @if($historical_data->isEmpty())
	                    <div class="alert alert-warning">
	                        <p>There were no historical records found.</p>
	                    </div>
	                    @else
	                    <ul  class="list-group">
	                        @foreach($historical_data as $hist_datum)
	                            <li class="list-group-item"><strong class="text-success">
	                            @if(empty($hist_datum))
	                            <div class="alert alert-warning">
	                                <p>There were no historical records found.</p>
	                            </div>
	                            @else
	                                @if(empty($hist_datum->Te_Code)) {{ $hist_datum->coursesOld->Description }} 
	                                @else {{ $hist_datum->courses->Description }} 
	                                @endif</strong> : {{ $hist_datum->terms->Term_Name }} 

	                                <em>
                                	@if (empty($hist_datum->classrooms))
                                	@else
	                                	@if (is_null($hist_datum->classrooms->Tch_ID))
	                                		Waitlisted
	                                	@elseif($hist_datum->classrooms->Tch_ID == 'TBD')
	                                		Waitlisted
	                                	@else
	                                		* {{ $hist_datum->classrooms->Tch_ID }} *
	                                	@endif
                                	@endif
                                	</em>

	                                (@if($hist_datum->Result == 'P') Passed @elseif($hist_datum->Result == 'F') Failed @elseif($hist_datum->Result == 'I') Incomplete @else -- @endif)</li>
	                            @endif
	                        @endforeach
	                    </ul>
	                    @endif
	                </div>
	            {{-- </div> --}}
                	  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal form to show placement history -->
<div id="showModalPlacementHistory" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title-placement-history"></h4>
            </div>
            <div class="modal-body-placement-history">
	            <div class="panel-body panel-info">
	                @if($placement_records->isEmpty())
	                <div class="alert alert-danger">
	                    <p>There were no placement test records found.</p>
	                </div>
	                @else
	                <ul  class="list-group">
	                    @foreach($placement_records as $placement_record)
	                        <li class="list-group-item"><strong class="text-success">
	                        @if(empty($placement_record))
		                        <div class="alert alert-danger">
		                            <p>There were no placement test records found.</p>
		                        </div>
	                        @else
								{{ $placement_record->terms->Comments }} {{ $placement_record->terms->Term_Name }}</strong> : {{ $placement_record->languages->name }} Placement Test 
								<br><strong>Assessment/Result :</strong> {{ $placement_record->Result }}
								<br><strong>Assigned Course : </strong> @if ($placement_record->Te_Code) {{ $placement_record->courses->Description }} @endif
	                        @endif
	                        </li>
	                    @endforeach
	                </ul>
	                @endif
	            </div>                	  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- modal for enrolments forms --}}
<div id="modalshow" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body-schedule">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
{{-- modal for placement forms --}}
<div id="modalshowplacementinfo" class="modal fade">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body-schedule">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal form to show student comments on regular forms -->
<div id="showStdComments" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title-std-comments"><i class="fa fa-comment fa-2x text-primary"></i> Student Comment</h4>
            </div>
            <div class="modal-body">
				@if(empty($student_enrolments))

				@else
					@if(count($student_enrolments) == 0)
					
					@else
	                <div class="panel-body modal-body-std-comments"></div>
                	@endif
                @endif	  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modalAssignCourse" class="modal fade" role="dialog">
    <div class="modal-dialog-full">
        <div class="modal-content-full">

            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
                <h4 class="modal-title">Admin Assign Course to Student</h4>
            </div>
            <div class="modal-body-content modal-background">
            </div>
            <div class="modal-footer modal-background">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        
        </div>
    </div>
</div> 

@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('button.insert-to-class').click( function() {
      var INDEXID = $(this).attr('data-indexno');
      var L = $(this).attr('data-language');
      var Te_Code = $(this).attr('data-tecode');
      var Term = $(this).attr('data-term');
      var token = $("input[name='_token']").val();

      $.ajax({
        url: '{{ route('insert-record-to-preview') }}',
        type: 'POST',
        data: {INDEXID:INDEXID, L:L, Te_Code:Te_Code, Term:Term,_token: token},
      })
      .done(function(data) {
        console.log("insert record complete");
        console.log(data);
        if (data == 'un-assigned') {
        	alert('The form is not assigned to a course or not validated/confirmed by a language admin.')
        	location.reload(true);
        } else if(data == 'already-inserted'){
        	alert('The form has already been inserted to a class. Please check convocation details on this page.')
        	location.reload(true);
        } else {
        	location.reload(true);
        }

        
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
    });
});
</script>

<script>
$(document).ready(function () {
    $('.assign-course').click( function() {
      var indexid = $(this).closest("tr").find("input[name='indexid']").val();
      var L = $(this).closest("tr").find("input[name='L']").val();
      var Term = $(this).closest("tr").find("input[name='term']").val();
      var Te_Code_Input = $(this).closest("tr").find("input[name='Te_Code_Input']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
        url: '{{ route('admin-manage-user-assign-course-view') }}',
        type: 'GET',
        data: {indexid:indexid, L:L,Te_Code:Te_Code_Input,Term:Term,_token: token},
      })
      .done(function(data) {
        console.log("show assign view : success");
        $('.modal-body-content').html(data)
        $('#modalAssignCourse').modal('show');
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete show assign view");
      });
    });
});
</script>

<script>  
$('#modalAssignCourse').on('click', '.modal-accept-btn',function() {
  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var admin_eform_comment = $("textarea#textarea-"+eform_submit_count+"[name='admin_eform_comment'].course-no-change").val();


  $.ajax({
    url: '{{ route('admin-nothing-to-modify') }}',
    type: 'PUT',
    data: {admin_eform_comment:admin_eform_comment, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
  })
  .done(function(data) {
    console.log(data);
    if (data == 0) {
      alert('Hmm... Nothing to change, nothing to update...');
    }

    var L = $("input[name='L'].modal-input").val();

      $.ajax({
          url: '{{ route('admin-manage-user-assign-course-view') }}',
          type: 'GET',
          data: {indexid:qry_indexid, L:L, Te_Code:qry_tecode, Term:qry_term,_token: token},
        })
        .done(function(data) {
          console.log("no change assign view : success");
          $('.modal-body-content').html(data);
        })
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
    
  });
    
});

$('#modalAssignCourse').on('click', '.modal-save-btn',function() {
  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var Te_Code = $("select#"+eform_submit_count+"[name='Te_Code'].course_select_no").val();
  var schedule_id = $("select#schedule-"+eform_submit_count+"[name='schedule_id']").val();
  var admin_eform_comment = $("textarea#textarea-"+eform_submit_count+"[name='admin_eform_comment'].course-changed").val();

  $(".overlay").fadeIn('fast'); 

  $.ajax({
    url: '{{ route('admin-save-assigned-course') }}',
    type: 'PUT',
    data: {Te_Code:Te_Code, schedule_id:schedule_id, admin_eform_comment:admin_eform_comment, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
  })
  .done(function(data) {
	    console.log(data);
	    if (data == 0) {
	      alert('Hmm... Nothing to change, nothing to update...');
	      location.reload();
	    }
	    var L = $("input[name='L'].modal-input").val();
		console.log(Te_Code)
	    $.ajax({
	      url: '{{ route('admin-manage-user-assign-course-view') }}',
	      type: 'GET',
	      data: {indexid:qry_indexid, L:L, Te_Code:Te_Code,Term:qry_term, _token: token},
	    })
	    .done(function(data) {
	      console.log("refreshing the assign view : success"); 
	      $('.modal-body-content').html(data);    
	    })
	    .always(function() {
	      console.log("complete refresh modal view");
	    });
  })
  .fail(function() {
    	console.log("error");
  })
  .always(function() {
    	console.log("complete save assigned course");
  });
  
});
</script>

<script>
$('#modalAssignCourse').on('hidden.bs.modal', function (event) {

  console.log(event.target)
  // alert( "This will be displayed only once." );
  //    $( this ).off( event );
  
  $(".preloader").fadeIn('fast');
  location.reload();

});
</script>


<script type="text/javascript">
$(document).ready(function() {
    $('.dropdown-toggle').dropdown(); 
    $('.select2-basic-single').select2({
    	placeholder: "--- Select Here ---",
    }); 

    $('.course-delete-tooltip').tooltip();

});
</script>
<script>
// Show a post
$(document).on('click', '.show-modal', function() {
    $('#showModal').modal('show'); 
});

$(document).on('click', '.show-modal-history', function() {
	$('.modal-title-history').text('Past Language Course Enrolment for {{ $student->name }}');
    $('#showModalHistory').modal('show'); 
});

$(document).on('click', '.show-modal-placement-history', function() {
	$('.modal-title-placement-history').text('Placement Tests and Results for {{ $student->name }}');
    $('#showModalPlacementHistory').modal('show'); 
})

$(document).on('click', '.course-delete', function() {
	var INDEXID = $(this).closest("tr").find("input[name='indexid']").val();
	var Te_Code = $(this).closest("tr").find("input[name='Te_Code_Input']").val();
	var Term = $(this).closest("tr").find("input[name='term']").val();
    $('#modalDeleteEnrolment-'+INDEXID+'-'+Te_Code+'-'+Term).modal('show'); 
});

$(document).on('click', '.placement-delete', function() {
	var placement_id = $(this).closest("tr").find("a[data-mid]").attr('data-mid');
	console.log(placement_id) 
    $('#modalDeletePlacement-'+placement_id).modal('show'); 
});

$(document).on('click', '.show-std-comments', function() {
	var indexno = $(this).closest("tr").find("input[name='indexno']").val();
	var tecode = $(this).closest("tr").find("input[name='tecode']").val();
	var eform_submit_count = $(this).closest("tr").find("input[name='eform_submit_count']").val();
	var term = $(this).closest("tr").find("input[name='term']").val();
	var token = $("input[name='_token']").val();
    $('#showStdComments').modal('show'); 

    $.post('{{ route('ajax-std-comments') }}', {'indexno':indexno, 'tecode':tecode, 'term':term,  'eform_submit_count':eform_submit_count, '_token':token}, function(data) {
          $('.modal-body-std-comments').html(data);
      });
});
</script>
<script>  
  $(document).ready(function () {
    $('#modalshow').on('show.bs.modal', function (event) {
      var link = $(event.relatedTarget); // Link that triggered the modal
      var dtitle = link.data('mtitle');
      var dindexno = link.data('indexno');
      var dtecode = link.data('tecode');
      var dterm = link.data('term');
      var dapproval = link.data('approval');
      var dFormCounter = link.data('formx');
      var token = $("input[name='_token']").val();
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('ajax-show-modal') }}', {'indexno':dindexno, 'tecode':dtecode, 'term':dterm, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
          console.log(data);
          $('.modal-body-schedule').html(data)
      });
    });
  });

  $(document).ready(function () {
    $('#modalshowplacementinfo').on('show.bs.modal', function (event) {
      var link = $(event.relatedTarget); // Link that triggered the modal
      console.log(link)
      var did = link.data('mid');
      var dtitle = link.data('mtitle');
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('ajax-show-modal-placement') }}', {'id':did, '_token':token}, function(data) {
          console.log(data);
          $('.modal-body-schedule').html(data)
      });
    });
  });
</script>
@stop