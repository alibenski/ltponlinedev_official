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
						            <label for="title" class="col-md-4 control-label">Profile:</label>

						            <div class="col-md-8 form-control-static">
						                <p>
						                	@if(empty( $student->profile )) Update Needed 
		                                    @else
		                                        @if( $student->profile == "STF") Staff Member @endif
		                                        @if( $student->profile == "INT") Intern @endif
		                                        @if( $student->profile == "CON") Consultant @endif
		                                        @if( $student->profile == "WAE") When Actually Employed @endif
		                                        @if( $student->profile == "JPO") JPO @endif
		                                        @if( $student->profile == "MSU") Staff of Permanent Mission @endif
		                                        @if( $student->profile == "SPOUSE") Spouse of Staff from UN or Mission @endif
		                                        @if( $student->profile == "RET") Retired UN Staff Member @endif
		                                        @if( $student->profile == "SERV") Staff of Service Organizations in the Palais @endif
		                                        @if( $student->profile == "NGO") Staff of UN-accredited NGO's @endif
		                                        @if( $student->profile == "PRESS") Staff of UN Press Corps @endif 
		                                    @endif
						                </p>
						            </div>
						        </div>

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

						        @include('users.partials.show-user-organization-field')

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
						
					<div class="form-group">
						<div class="panel panel-default">
							<div class="panel-body">
								<label class="control-label">New User Attachment 1: </label>
								@if(empty($student->newUserInt->filesId->path)) <strong class="badge">None</strong> @else <a href="{{ Storage::url($student->newUserInt->filesId->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif

							</div>
						</div>
					</div>

					<div class="form-group ">
						<div class="panel panel-default">
							<div class="panel-body">
								<label class="control-label">New User Attachment 2: </label>
								@if(empty($student->newUserInt->filesId2->path)) <strong class="badge">None</strong> @else <a href="{{ Storage::url($student->newUserInt->filesId2->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
								
							</div>
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