<div class="row">
	<div class="col-sm-12">
		<form method="POST" action="{{ route('newuser.update', $new_user_info->id) }}">
	        {{ csrf_field() }}
	        <div class="form-group">
	            <label class="control-label">Index #: </label>
	            <input name="indexno" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $new_user_info->indexno) }}">
	        </div>
			
			<div class="form-group">
	            <label class="control-label">Title: </label>
	            <input name="title" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $new_user_info->title) }}">
	        </div>

	        <div class="form-group">
	            <label class="control-label">First Name: </label>
	            <input name="nameFirst" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $new_user_info->nameFirst) }}">
	        </div>
	
			<div class="form-group">
	            <label class="control-label">Last Name: </label>
	            <input name="nameLast" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $new_user_info->nameLast) }}">
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
	            <input name="dob" class="form-control" readonly value="@if(empty($new_user_info->dob)) n/a @else {{ $new_user_info->dob->format('F d, Y') }} @endif"> 
	        </div>	
			
			<div class="form-group">
	            <label class="control-label">Gender: </label>
	            <input name="gender" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $new_user_info->gender) }}">
	        </div>

	        <div class="form-group">
	            <label class="control-label">Contact #: </label>
	            <input name="contact_num" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('name', $new_user_info->contact_num) }}">
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
</script>