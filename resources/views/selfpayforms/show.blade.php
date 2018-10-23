<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
	@foreach($show_sched_selfpay as $show_sched)
	<div class="form-group">
	    <label class="control-label col-sm-2" for="id">Name:</label>
	    <div class="col-sm-10">
	        <input type="text" class="form-control" id="id_show" value="{{ $show_sched->users->name }}" disabled>
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
	    <div class="col-sm-10 form-control">
			<p>{{$show_sched->schedule->name}}</p>
		</div>
	</div>
	@endforeach
	<div class="col-sm-12">
		<button class="show-modal btn btn-danger"><span class="glyphicon glyphicon-eye-open"></span>  Disapprove</button>
		<button class="show-modal btn btn-success"><span class="glyphicon glyphicon-eye-open"></span>  Approve</button>	
	</div>
</div>
</div>