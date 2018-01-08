<a class="btn {{$buttonclass}}" data-toggle="modal" href='#modal-id-{{$course}}'>{{ $buttonlabel }}</a>
<div class="modal fade" id="modal-id-{{$course}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{{ $title }}</h4>
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