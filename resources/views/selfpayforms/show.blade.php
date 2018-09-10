<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
	@foreach($show_sched_selfpay as $show_sched)
		<li>{{$show_sched->schedule->name}}</li>
	@endforeach
	</div>
	<div class="col-sm-12">
		<button class="show-modal btn btn-danger"><span class="glyphicon glyphicon-eye-open"></span>  Disapprove</button>
		<button class="show-modal btn btn-success"><span class="glyphicon glyphicon-eye-open"></span>  Approve</button>	
	</div>
</div>