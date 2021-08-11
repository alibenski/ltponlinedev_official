<div class="row">
	<div class="col-sm-12">
		<form method="POST" action="{{ route('newuser.update', $new_user_info->id) }}" class="form-prevent-multi-submit" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{$new_user_info->id}}">
	        {{ csrf_field() }}
	        <div class="form-group">
	            <label class="control-label text-danger">Possible duplicates (Please review before approving): {{ count($possible_dupes) }}</label>
		        @foreach($possible_dupes as $dupe)
		        <ul>
					<li>{{ $dupe->name }} : <strong> {{ $dupe->email }} </strong></li>
		        </ul>
		        @endforeach
	        </div>
	        <div class="form-group">
	            <label class="control-label">Index #: </label>
	            <input name="indexno" type="text" class="form-control"  value="{{ old('indexno', $auto_index) }}">
	            <p class="small-text text-danger">This field is system generated. The admin needs to verify in Umoja if the person is a UN staff member and change the index no. field accordingly. Click on the Generate button below if the index no. is incorrect.</p>
	            <button type="button" id="generateExtIndex" class="btn btn-info">Generate EXT index number</button>
	            <input id="ExtIndex" type="hidden" value="{{ $ext_index }}">
	        </div>

			<div class="form-group">
	            <label class="control-label">Profile: {{ old('profile', $new_user_info->profile) }}</label>
	            {{-- <input type="text" class="form-control" disabled value="{{ old('profile', $new_user_info->profile) }}"> --}}
	        </div>
			
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
					@include('ajax-profile-select')
				</div>
			</div>

			<div class="form-group">
	            <label class="control-label">Title: {{ old('title', $new_user_info->title) }}</label>
			</div>

			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
					<div class="col-md-12">
						<div class="dropdown">
							<select class="col-md-12 form-control select2" style="width: 100%;" name="title" autocomplete="off" >
								<option value=""> Change Title Here If Needed </option>
								<option value="Ms.">Ms.</option>
								<option value="Mr.">Mr.</option>
							</select>
						</div>

						@if ($errors->has('title'))
							<span class="help-block">
								<strong>{{ $errors->first('title') }}</strong>
							</span>
						@endif
					</div>
				</div>
	        </div>

	        <div class="form-group">
	            <label class="control-label">First Name: </label>
	            <input name="nameFirst" type="text" class="form-control" value="{{ old('nameFirst', $new_user_info->nameFirst) }}">
	        </div>
	
			<div class="form-group">
	            <label class="control-label">Last Name: </label>
	            <input name="nameLast" type="text" class="form-control" value="{{ old('nameLast', $new_user_info->nameLast) }}">
	        </div>

	        <div class="form-group">
	            <label class="control-label">Email: </label>
	            <input name="email" type="email" class="form-control" value="{{ old('email', $new_user_info->email) }}"> 
	        </div>		        
	        
	        <div class="form-group">
	            <label class="control-label">Organization: </label>
				<br />
				<strong>
				@if ($new_user_info->org == "MSU")
					{{ $new_user_info->org }} - {{ $new_user_info->countryMission->ABBRV_NAME }}
				@endif
				@if ($new_user_info->org == "NGO")
					{{ $new_user_info->org }} - {{ $new_user_info->ngo_name }}
				@endif
				</strong>
	            <div class="dropdown">
                      <select id="org" name="org" class="col-md-8 form-control select2-org-single" style="width: 100%;" required="required">
                        @if(!empty($org))
                          @foreach($org as $value)
                          	@if (old('org', $new_user_info->org) == $value['Org name'])
						    	<option value="{{ $value['Org name'] }}" selected>{{ $value['Org name'] }} - {{ $value['Org Full Name'] }}</option>
							@else
						    	<option value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{ $value['Org Full Name'] }}</option>
							@endif
                          @endforeach
                        @endif
                      </select>
                    </div> 
	        </div>	

			<div id="countrySection"></div>
            <div id="ngoSection"></div>

	        <div class="form-group">
	            <label class="control-label">DOB: <span class="text-danger">field cannot be changed</span></label>
	            <input name="dobstring" class="form-control" readonly value="@if(empty($new_user_info->dob)) @else {{ $new_user_info->dob->format('F d, Y') }} @endif"> 
	            <input name="dob" type="hidden" class="form-control" readonly value="@if(empty($new_user_info->dob)) @else {{ $new_user_info->dob }} @endif"> 
	        </div>	
			
			<div class="form-group">
	            <label class="control-label">Gender: @if ($new_user_info->gender == 'M') Male @elseif($new_user_info->gender == 'F') Female @elseif($new_user_info->gender == 'O') Other @else NULL @endif </label>
	            {{-- <input name="gender" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value=""> --}}
	        </div>

			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
					<div class="col-md-12">
						<div class="dropdown">
							<select class="col-md-12 form-control select2" style="width: 100%;" name="gender" autocomplete="off">
								<option value="">--- Please Select ---</option>
								<option value="F">Female</option>
								<option value="M">Male</option>
								<option value="O">Other</option>
							</select>
						</div>
						
						@if ($errors->has('gender'))
						<span class="help-block">
							<strong>{{ $errors->first('gender') }}</strong>
						</span>
						@endif
					</div>
				</div>
			</div>

	        <div class="form-group">
	            <label class="control-label">Contact #: </label>
	            <input name="contact_num" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('contact_num', $new_user_info->contact_num) }}">
	        </div>
			
			<div class="form-group">
				<div class="panel panel-default">
					<div class="panel-body">
						<label class="control-label">Attachment 1: </label>
						@if(empty($new_user_info->filesId->path)) <strong>None</strong> @else <a href="{{ Storage::url($new_user_info->filesId->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
		
						<div class="form-group">
							<label class="control-label col-sm-12">Attach another file to replace attachment 1: </label>
							<input name="contractfile" type="file" class="col-md-12 form-control-static mb-1">
						</div>
					</div>
				</div>
			</div>

			<div class="form-group ">
				<div class="panel panel-default">
					<div class="panel-body">
						<label class="control-label">Attachment 2: </label>
						@if(empty($new_user_info->filesId2->path)) <strong class="badge">None</strong> @else <a href="{{ Storage::url($new_user_info->filesId2->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
						
						<div class="form-group">
							<label class="control-label col-sm-12">Attach another file to replace attachment 2: </label>
							<input name="contractfile2" type="file" class="col-md-12 form-control-static mb-1">
						</div>
					</div>
				</div>
	        </div>

			@if (count($new_user_info->newUserComments))
			<div class="form-group">
				<label class="control-label">Lastest admin correspondence: </label>
				<br />
				"{{ $new_user_info->newUserComments->last()->comments }}" 
				{{-- from User: {{ $new_user_info->newUserComments->last()->user_id }} --}}
			</div>
			@endif

			<div class="form-group">
				<label class="control-label">Email Text: </label>
				<textarea class="form-control" name="emailText" cols="40" rows="3" placeholder="Email text here"></textarea>
			</div>

			<div class="form-group">
				<div class="alert alert-danger">
					<span><i class="fa fa-info-circle"></i> Please reject if applicant is a duplicate</span>
				</div>
			</div>

	        <button type="submit" name="submit" value="2" class="btn btn-danger btn-space "><span class="glyphicon glyphicon-remove"></span>  Reject</button>
	        <button type="submit" name="submit" value="3" class="btn btn-warning btn-space "><span class="glyphicon glyphicon-time"></span>  Send Email and Set to Pending</button>
			<button type="submit" name="submit" value="1" class="btn btn-success btn-space "><span class="glyphicon glyphicon-check"></span>  Save and Approve</button>

	        <input type="hidden" name="_token" value="{{ Session::token() }}">
	        {{ method_field('PUT') }}
	    </form>	
	</div>
</div>

<script>
	$('.select2').select2({
		placeholder: "Change Here If Needed",
	});

	$('.select2-org-single').select2({
		// dropdownParent: $('#showModal'), // attach the dropdown to the modal itself 
	});
	$("#generateExtIndex").on('click', function(event) {
		var ExtIndex = $("#ExtIndex").val(); 
		$('input[name="indexno"]').val(ExtIndex);
	});
	$('.select-profile-single').select2({
		placeholder: "Change Here If Needed",
	});
	$('.select-profile-single').removeAttr('required');
	$("select[name='org']").on("change", function () {
        let choice = $("select[name='org']").val();
        if (choice == "MSU") {
            getCountry();
        } else {
            $("#countrySection").html("");
        }
    });

    function getCountry() {
        $.ajax({
            url: "{{ route('ajax-select-country') }}", 
            method: 'GET',
            success: function(data, status) {
                console.log(data)
            $("#countrySection").html("");
            $("#countrySection").html(data.options);
            }
        });  
    }

    $("select[name='org']").on("change", function () {
        let choice = $("select[name='org']").val();
        if (choice == "NGO") {
            $("#ngoSection").html("<div class='col-md-12'><div class='form-group row'><label for='ngoName' class='col-md-12 control-label text-danger'>NGO Name:  </label><div class='col-md-12'><input id='ngoName' type='text' class='form-control' name='ngoName' placeholder='Enter NGO agency name' required></div></div></div>");
        } else {
            $("#ngoSection").html("");
        }
    });
</script>