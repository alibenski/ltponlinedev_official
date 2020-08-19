<a class="cancel-btn btn {{$buttonclass}} {{$classApproval}} {{$deleteSet}}" data-toggle="modal" href='#modal-id-{{$course}}-{{$formCount}}'>{{ $buttonlabel }}</a>

<div class="modal fade" id="modal-id-{{$course}}-{{$formCount}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ $title }}</h5>
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