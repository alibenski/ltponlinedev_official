<div class="row">
	@foreach ($enrolment_details as $element)
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border bg-aqua">
            	<h4>Enrolment Form # {{ $element->eform_submit_count}}</h4>
            </div>
            <div class="box-body">
            	<div class="col-sm-6">
	                <ul>
						<li>Name: <strong>{{ $element->users->name }}</strong></li> 
		                <li>Language: <strong>{{ $element->languages->name }}</strong></li> 
		                <li>Course: <strong>{{ $element->courses->Description }}</strong></li>
						<li>Schedule(s) Chosen:</li>
			                <ol>
							@foreach ($enrolment_schedules as $val)
				                @if ($val->eform_submit_count == $element->eform_submit_count)
					                	<li><strong>{{ $val->schedule->name }}</strong></li>
				                @endif
							@endforeach    
			                </ol>

	                </ul>
				</div>

				<div class="col-sm-6">
		        <form id="form-{{ $element->eform_submit_count }}" method="POST" action="" class="col-sm-12">
	                	{{ csrf_field() }}
	                <input name="L" type="hidden" value="{{ $element->L }}">
	                <input name="eform_submit_count" type="hidden" value="{{ $element->eform_submit_count }}">
	                <input name="Term" type="hidden" value="{{ $element->Term }}">

					<div class="form-group">
	                	<label>Course</label>


	                        <select id="{{$element->eform_submit_count}}" class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%; " name="Te_Code">
	                            <option value="">--- Select Course ---</option>
	                        </select>


	                </div>

	                <div class="form-group">
	                	<label>Schedule</label>

	                        <select id="schedule-{{$element->eform_submit_count}}" class="col-sm-12 form-control schedule_select_no select2-basic-single" style="width: 100%; " name="schedule_id">
	                            <option value="">--- Select Here ---</option>
	                        </select>

	                </div>
					
	                <div class="form-group">
						<label class="control-label">Comments: </label>

						<textarea name="" class="form-control" maxlength="3500" placeholder="Important information that the Language Secretariat needs to know"></textarea>
						
					</div>
	                
	                <div class="form-group">
	                	<button id="{{$element->eform_submit_count}}" type="button" class="modal-save-btn btn btn-success btn-space pull-right">Save</button>
		                
		                <input type="hidden" name="_token" value="{{ Session::token() }}">
		                {{ method_field('PUT') }}
	                </div>
		        </form>
		    	</div>
            </div>
        </div>
    </div>
	@endforeach
</div>
<script>
$('.modal-save-btn').on('click', function() {
	$('#modalshow').modal('hide');

});
$('#modalshow').on('hidden.bs.modal', function () {
  	alert('refresh');
})
</script>
<script type="text/javascript">
$(document).ready(function() {
	var L = $("input[name='L']").val();
	var term = $("input[name='Term']").val();
	var token = $("input[name='_token']").val();

	$.ajax({
	  url: "{{ route('select-ajax') }}", 
	  method: 'POST',
	  data: {L:L, term_id:term, _token:token},
	  success: function(data, status) {
	    $("select[name='Te_Code']").html('');
	    $("select[name='Te_Code']").html(data.options);
	  }
	});	

	$("select[name='Te_Code']").on('change',function(){
		var course_id = $(this).val();
		var eform_submit_count = $(this).attr('id');

		$.ajax({
		  url: "{{ route('select-ajax2') }}", 
		  method: 'POST',
		  data: {course_id:course_id, term_id:term, _token:token},
		  success: function(data) {
		    $("select#schedule-"+eform_submit_count+"[name='schedule_id']").html('');
		    $("select#schedule-"+eform_submit_count+"[name='schedule_id']").html(data.options);
		  }
		});
	});
});
</script>