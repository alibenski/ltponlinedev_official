<a class="btn {{$buttonclass}}" data-toggle="modal" href='#modal-id'>{{ $buttonlabel }}</a>
<div class="modal fade" id="modal-id">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span><h5 class="modal-title">{{ $title }}</h5></span>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				{{ $body }}
			</div>
			<div class="modal-footer">
				{{ $buttonoperation }}
			</div>
		</div>
	</div>
</div>