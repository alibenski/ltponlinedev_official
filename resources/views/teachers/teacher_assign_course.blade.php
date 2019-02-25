<style>
#ribbon{
  background:#fa6f57;
  height: 30px;
  width:auto;
  display: inline-block;
  margin:15px auto;
  position: relative;
  color:#FFF;
  line-height: 30px;
  padding:0px 20px;
}

#ribbon:after{
  content:"";
  height:0;
  width: 0;
  top:0px;
  right:-30px;
  position: absolute;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-bottom: 30px solid #fa6f57;
}

#ribbon:before{
  content:"";
  height:0;
  width: 0;
  top:0px;
  right:-30px;
  position: absolute;
  border-style: solid;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  border-top: 30px solid #fa6f57;
}
</style>

<div class="row">
	@foreach ($enrolment_details as $element)
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border bg-aqua">
            	<h4>Enrolment Form # {{ $element->eform_submit_count}}</h4>
            </div>
            <div class="box-body">
            	<div class="col-sm-6">
            		
            		@if (count($arr2) > 0) 
		            <div id="ribbon">
					  Enrolment form modified by : @if ($element->modifyUser)
					  	{{ $element->modifyUser->name }} on {{ $element->updated_at }}
					  @endif
					</div>
            		@endif

					<p>Name: <strong>{{ $element->users->name }}</strong></p> 
	                <p>Language: <strong>{{ $element->languages->name }}</strong></p> 
	                <p>Course: <strong>{{ $element->courses->Description }}</strong></p>
					<p>Schedule(s) Chosen:</p>
		                <ol>
						@foreach ($enrolment_schedules as $val)
			                @if ($val->eform_submit_count == $element->eform_submit_count)
				                <li><strong>{{ $val->schedule->name }}</strong></li>
			                @endif
						@endforeach    
		                </ol>
					<p>Flexible: 
						@if ( $element->flexibleBtn == 1)
						<strong>Yes</strong>
						@else
						<strong>No</strong>
						@endif
					</p>

	                <div id="{{$element->eform_submit_count}}" class="form-group schedule-count btn-accept hidden">
	                	{{-- <a href="{{ route('nothing-to-modify', [$button->INDEXID, $button->Term, $button->Te_Code, $button->form_counter]) }}" class="btn btn-success">Accept Enrolment</a> --}}
	                	<a href="" class="btn btn-warning"><span><i class="fa fa-thumbs-up"></i></span> Accept </a>		                	 	
	                </div>
				</div>

				<div class="col-sm-6">
			        <form id="form-{{ $element->eform_submit_count }}" method="POST" action="" class="col-sm-12">
		                	{{ csrf_field() }}
		                <input name="INDEXID" type="hidden" value="{{ $element->INDEXID }}">
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

							<textarea id="textarea-{{$element->eform_submit_count}}" name="" class="form-control" maxlength="3500" placeholder="Important information that the Language Secretariat needs to know"></textarea>
							
						</div>
		                
		                <div class="form-group">

		                	<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-save-btn btn btn-success btn-space pull-right">Save</button>
			                
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
	var eform_submit_count = $(this).attr('id');
	var qry_tecode = $(this).attr('data-tecode');
	var qry_indexid = $(this).attr('data-indexid');
	var qry_term = $(this).attr('data-term');
	var token = $("input[name='_token']").val();
	var Te_Code = $("select#"+eform_submit_count+"[name='Te_Code'].course_select_no").val();
	var schedule_id = $("select#schedule-"+eform_submit_count+"[name='schedule_id']").val();

	$.ajax({
		url: '{{ route('teacher-save-assigned-course') }}',
		type: 'PUT',
		data: {Te_Code:Te_Code, schedule_id:schedule_id, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
	})
	.done(function(data) {
		console.log(data);
		if (data == 0) {
			alert('Hmm... Nothing to change, nothing to update...');
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
	// $('#modalshow').modal('hide');
	
});
$('#modalshow').on('hidden.bs.modal', function () {
  	// console.log('refresh')
})
</script>

<script type="text/javascript">
$(document).ready(function() {
	var INDEXID = $("input[name='INDEXID']").val();
	var L = $("input[name='L']").val();
	var term = $("input[name='Term']").val();
	var token = $("input[name='_token']").val();

	$('.schedule-count').each(function(index, val) {
		var eform_submit_count = $(this).attr('id');

		console.log('eform_submit_count '+eform_submit_count)
		$.ajax({
			url: '{{ route('teacher-check-schedule-count') }}',
			type: 'GET',
			data: {eform_submit_count:eform_submit_count, INDEXID:INDEXID, L:L, term_id:term, _token:token},
		})
		.done(function(data) {
			console.log(data)
			if (data == 1) {
				$('div#'+eform_submit_count+'.btn-accept').removeClass('hidden');
			}
			
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});


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