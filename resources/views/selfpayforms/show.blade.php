<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
	<div class="form-group">
	    <label class="control-label col-sm-2" for="id">Name:</label>
	    <div class="col-sm-10">
	        <input type="text" class="form-control" id="id_show" value="{{ $selfpay_student->users->name }}" disabled>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label col-sm-2" for="title">Course:</label>
	    <div class="col-sm-10">
	        <input type="name" class="form-control" id="title_show" disabled>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label col-sm-2" for="title">Organization:</label>
	    <div class="col-sm-10">
	        <input type="name" class="form-control" id="title_show" disabled>
	    </div>
	</div>
	<div class="form-group">
	    <label class="control-label col-sm-2" for="content">Content:</label>
	    <div class="col-sm-10">
	        <textarea class="form-control" id="content_show" cols="40" rows="5" disabled></textarea>
	    </div>
	</div>
	<div class="form-group">	
		<label class="control-label col-sm-2" for="content">Schedule:</label>
		@foreach($show_sched_selfpay as $show_sched)
	    <div class="col-sm-12">
			<ul>
	    		<li>{{ $show_sched->schedule->name }}</li>
			</ul>
		</div>
		@endforeach
	</div>
	<div class="col-sm-12">
		<button class="show-modal btn btn-danger"><span class="glyphicon glyphicon-eye-open"></span>  Disapprove</button>
		<button class="show-modal btn btn-success"><span class="glyphicon glyphicon-eye-open"></span>  Approve</button>	
	</div>
</div>
</div>