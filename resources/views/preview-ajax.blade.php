<div class="table-responsive filtered-table">
	<h4><strong>Students</strong></h4>
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Self-paying?</th>
	            <th>Flexible?</th>
	            <th>Schedules</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($form_info as $form_in)
			@foreach($form_in as $form)
			<tr>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif </td>
				<td>
					@if($form->is_self_pay_form == 1)<span class="label label-success margin-label">Yes</span>
					@else - 
					@endif
				</td>
				<td>
					@if($form->flexibleBtn == 1)
                    	<span class="label label-success margin-label">Yes</span>
                    @else
						-
                    @endif
				</td>
				<td>
					<a id="modbtn" class="btn btn-info btn-space" data-toggle="modal" href="#modalshow" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-approval="{{ $form->approval }}" data-formx="{{ $form->form_counter }}" data-mtitle="{{ $form->courses->EDescription }}"><span><i class="fa fa-eye"></i></span> Schedule Info</a>
				</td>
			</tr>
			@endforeach
			@endforeach
	    </tbody>
	</table>
</div>
<div id="modalshow" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body-schedule">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
<script>
  $(document).ready(function () {
    $('#modalshow').on('show.bs.modal', function (event) {
      var link = $(event.relatedTarget); // Link that triggered the modal
      var dtitle = link.data('mtitle');
      var dindexno = link.data('indexno');
      var dtecode = link.data('tecode');
      var dterm = link.data('term');
      var dapproval = link.data('approval');
      var dFormCounter = link.data('formx');
      var token = $("input[name='_token']").val();
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('ajax-show-modal') }}', {'indexno':dindexno, 'tecode':dtecode, 'term':dterm, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
          console.log(data);
          $('.modal-body-schedule').html(data)
      });
    });
  });
</script>