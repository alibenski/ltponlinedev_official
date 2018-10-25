<div class="row">
	<div class="col-sm-12">
		<form method="POST" action="{{ route('newuser.update', $new_user_info->id) }}">
	        {{ csrf_field() }}
	        <div class="form-group">
	            <label class="control-label text-danger">Possible duplicates (Please review before approving): {{ count($possible_dupes) }}</label>
		        @foreach($possible_dupes as $dupe)
		        <ul>
					<li>{{ $dupe->name }} : {{ $dupe->email }}</li>
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
	            <label class="control-label">Title: </label>
	            <input name="title" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('title', $new_user_info->title) }}">
	        </div>

	        <div class="form-group">
	            <label class="control-label">First Name: </label>
	            <input name="nameFirst" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('nameFirst', $new_user_info->nameFirst) }}">
	        </div>
	
			<div class="form-group">
	            <label class="control-label">Last Name: </label>
	            <input name="nameLast" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('nameLast', $new_user_info->nameLast) }}">
	        </div>

	        <div class="form-group">
	            <label class="control-label">Email: </label>
	            <input name="email" type="email" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('email', $new_user_info->email) }}"> 
	        </div>		        
	        
	        <div class="form-group">
	            <label class="control-label">Organization: </label>
	            <div class="dropdown">
                      <select id="org" name="org" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
                        @if(!empty($org))
                          @foreach($org as $value)
                          	@if (old('org', $new_user_info->org) == $value['Org Name'])
						    	<option value="{{ $value['Org Name'] }}" selected>{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
							@else
						    	<option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
							@endif
                          @endforeach
                        @endif
                      </select>
                    </div> 
	        </div>	

	        <div class="form-group">
	            <label class="control-label">DOB: </label>
	            <input name="dobstring" class="form-control" readonly value="@if(empty($new_user_info->dob)) @else {{ $new_user_info->dob->format('F d, Y') }} @endif"> 
	            <input name="dob" type="hidden" class="form-control" readonly value="@if(empty($new_user_info->dob)) @else {{ $new_user_info->dob }} @endif"> 
	        </div>	
			
			<div class="form-group">
	            <label class="control-label">Gender: </label>
	            <input name="gender" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('gender', $new_user_info->gender) }}">
	        </div>

	        <div class="form-group">
	            <label class="control-label">Contact #: </label>
	            <input name="contact_num" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('contact_num', $new_user_info->contact_num) }}">
	        </div>
			
			<div class="form-group">
	            <label class="control-label">Attachment: </label>
	            @if(empty($new_user_info->filesId->path)) <strong>None</strong> @else <a href="{{ Storage::url($new_user_info->filesId->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
	        </div>

	        <button type="button" data-dismiss="modal" class="btn btn-danger btn-space button-prevent-multi-submit"><span class="glyphicon glyphicon-remove"></span>  Disapprove</button>
			<button type="submit" class="btn btn-success btn-space button-prevent-multi-submit"><span class="glyphicon glyphicon-check"></span>  Save and Approve</button>	
	        <input type="hidden" name="_token" value="{{ Session::token() }}">
	        {{ method_field('PUT') }}
	    </form>	
	</div>
</div>
<script>
	$('.select2-basic-single').select2({
	dropdownParent: $('#showModal')
	});
	$("#generateExtIndex").on('click', function(event) {
		var ExtIndex = $("#ExtIndex").val(); 
		$('input[name="indexno"]').val(ExtIndex);
	});
</script>