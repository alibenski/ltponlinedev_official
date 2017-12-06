<a class="btn btn-primary" data-toggle="modal" href='#modal-id'>Submit via Modal</a>
<div class="modal fade" id="modal-id">
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
				  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
	              <button type="submit" class="btn btn-success button-prevent-multi-submit">Submit Decision</button>
	              <input type="hidden" name="_token" value="{{ Session::token() }}">
	              {{ method_field('PUT') }}
			</div>
		</div>
	</div>
</div>