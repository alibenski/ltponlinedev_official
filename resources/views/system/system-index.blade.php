@extends('admin.admin')

@section('content')

<h2>System Operations</h2>
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box box-body">
				<a href="{{route('send-broadcast-enrolment-is-open')}}"><i class="fa fa-arrow-right"></i> Send Broadcat Email that Enrolment is Open</a>
			</div>
		</div>
		
	</div>
</div>


@stop