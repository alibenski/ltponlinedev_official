<div class="row">
	<div class="col-sm-12">
		<form method="POST" action="{{ route('newuser.update', $new_user_info->id) }}">
	        {{ csrf_field() }}
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
	            <input name="org" type="text" class="form-control" readonly onfocus="this.removeAttribute('readonly');" value="{{ old('org', $new_user_info->org) }}"> 
	        </div>	

	        <div class="form-group">
	            <label class="control-label">DOB: </label>
	            <input name="dob" class="form-control" readonly value="@if(empty($new_user_info->dob)) n/a @else {{ $new_user_info->dob->format('F d, Y') }} @endif"> 
	        </div>	

	        <button class="btn btn-danger btn-space button-prevent-multi-submit"><span class="glyphicon glyphicon-remove"></span>  Disapprove</button>
			<button type="submit" class="btn btn-success btn-space button-prevent-multi-submit"><span class="glyphicon glyphicon-check"></span>  Save and Approve</button>	
	        <input type="hidden" name="_token" value="{{ Session::token() }}">
	        {{ method_field('PUT') }}
	    </form>	
	</div>
</div>