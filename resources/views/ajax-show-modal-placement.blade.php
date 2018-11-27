<div class="modal-body">

	<div class="row">
		<form method="POST" action="">
		{{ csrf_field() }}
			<div class="col-sm-6">
				<input name="INDEXID" type="hidden" value="{{ $placement_form->INDEXID }}">
				<input name="L" type="hidden" value="{{ $placement_form->L }}">
				<input name="Term" type="hidden" value="{{ $placement_form->Term }}">
				<div class="form-group">
				    <label class="control-label" for="id_show">Name:</label>
				    <div class="">
				        <input name="nom" type="text" class="form-control"  value="{{ $placement_form->users->name }}" readonly>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="email">Email:</label>
				    <div class="">
				        <input name="email" type="text" class="form-control"  value="{{ $placement_form->users->email }}" readonly>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="contact_num">Contact Number:</label>
				    <div class="">
				        <input name="contact_num" type="text" class="form-control"  value="{{ $placement_form->users->sddextr->PHONE }}" readonly>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="course_show">Language:</label>
				    <div class="">
				        <input type="text" class="form-control" name="course_show" value="{{ $placement_form->languages->name }}" readonly>
				    </div>
				</div>

				@if(is_null($placement_form->is_self_pay_form))
				@else
				<div class="form-group">
					<label class="control-label" for="org_show">ID Proof:</label>
					<td>@if(empty($placement_form->filesId->path)) None @else <a href="{{ Storage::url($placement_form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif </td>	
				</div>
				<div class="form-group">	
					<label class="control-label" for="org_show">Payment Proof:</label>
					<td>@if(empty($placement_form->filesPay->path)) None @else <a href="{{ Storage::url($placement_form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif </td>
				</div>
				@endif	

				<div class="form-group">
				    <label class="control-label" for="profile_show">Profile:</label>
				    <div class="">
				        <input type="text" class="form-control" name="profile_show" value="{{ $placement_form->profile }}" readonly>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="org_show">Organization:</label>
				    <div class="">
				        <input type="text" class="form-control" name="org_show" value="{{ $placement_form->DEPT }}" readonly>
				    </div>
				</div>
				<div class="form-group">	
					<label class="control-label" for="show_sched">Exam Date:</label>
				    <div class="col-sm-12">
						<ul>
				    		<li>@if($placement_form->L == 'F') <strong>ONLINE</strong> from {{ $placement_form->placementSchedule->date_of_plexam }} to {{ $placement_form->placementSchedule->date_of_plexam_end }} @else {{ $placement_form->placementSchedule->date_of_plexam }} @endif</li>
						</ul>
					</div>

				</div>
				<div class="form-group">
				    <label class="control-label" for="flexible_show">Placement Test/ Waitlist Information: </label>
						<div class="panel panel-body">
					    	@if ($waitlists)
					    		@foreach($waitlists as $waitlisted)
					    			@foreach($waitlisted->waitlist as $info_details)
					    			<ul>
					    				<li>Term Code: {{ $info_details->Term }}</li>
					    				<li>Term: {{ $info_details->terms->Comments }}</li>
					    				<li>Remark: {{ $info_details->Comments }}</li>
					    			</ul>
					    			<hr>
					    			@endforeach 
					    		@endforeach 
					    	@else -- 
					    	@endif
						</div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="flexible_show">Is Flexible: @if($placement_form->flexibleBtn == 1)<span class="glyphicon glyphicon-ok text-success"></span> Yes @else <span class="glyphicon glyphicon-remove text-danger"></span> Not flexible @endif</label>
				</div>
			</div> 
			{{-- EOF 1st column --}}

			<div class="col-sm-6">
				<div class="form-group">
				    <label class="control-label" for="student_comment_show">Preferred Days:</label>
				    <div class="">
				        <textarea class="form-control" name="student_comment_show" cols="40" rows="3" readonly placeholder="no comment">{{ $placement_form->dayInput }}</textarea>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="student_comment_show">Preferred Time:</label>
				    <div class="">
				        <textarea class="form-control" name="student_comment_show" cols="40" rows="3" readonly placeholder="no comment">{{ $placement_form->timeInput }}</textarea>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="student_comment_show">Student Comment:</label>
				    <div class="">
				        <textarea class="form-control" name="student_comment_show" cols="40" rows="3" readonly  placeholder="no comment">{{ $placement_form->std_comments }}</textarea>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label" for="admin_comment_show">Course Preference:</label>
				    <div class="">
				        <textarea class="form-control" name="admin_comment_show" cols="40" rows="3" readonly  placeholder="no comment">{{ $placement_form->course_preference_comment }}</textarea>
				    </div>
				</div>
			</div> {{-- EOF 2nd column --}}
		</form>			
	</div>
</div>
