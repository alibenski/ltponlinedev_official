<div class="row">
	@foreach ($enrolment_details as $element)
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border bg-aqua">
            	<h4>Enrolment Form # {{ $element->eform_submit_count}}</h4>
            </div>
            <div class="box-body">
            	<div class="col-sm-6">

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
	
					@if ($element->modifyUser)
		            <div class="callout callout-warning">
						Last update by:  {{ $element->modifyUser->name }} on {{ $element->updatedOn }} 
					</div>
					
					<div class="well">
						<p><strong>Change Logs (Student Originally Chose):</strong></p>
						@foreach ($modified_forms as $e)
							@foreach ($e as $v)
								@if ($v->eform_submit_count == $element->eform_submit_count)
									Form # {{ $v->eform_submit_count }}: 
									{{ $v->courses->Description }} 
									{{ $v->schedule->name }} <br>
								@endif
								
							@endforeach
						@endforeach	
					</div>

					@endif
				</div>

				<div class="col-sm-6">
			        <form id="form-{{ $element->eform_submit_count }}" method="POST" action="" class="col-sm-12">
		                	{{ csrf_field() }}
		                <input name="INDEXID" type="hidden" value="{{ $element->INDEXID }}">
		                <input name="L" type="hidden" value="{{ $element->L }}">
		                <input name="eform_submit_count" type="hidden" value="{{ $element->eform_submit_count }}">
		                <input name="Term" type="hidden" value="{{ $element->Term }}">

						<div class="form-group">
		                	<label>Course:</label>
		                	<small class="text-danger">Leave blank if no change is needed</small>


		                        <select id="{{$element->eform_submit_count}}" class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%; " name="Te_Code">
		                            <option value="">--- Select Course ---</option>
		                        </select>


		                </div>

		                <div class="form-group">
		                	<label>Schedule:</label>

		                        <select id="schedule-{{$element->eform_submit_count}}" class="col-sm-12 form-control schedule_select_no select2-basic-single" style="width: 100%; " name="schedule_id">
		                            <option value="">--- Select Here ---</option>
		                        </select>

		                </div>
						
		                <div class="form-group">
							<label class="control-label">Comments: </label>

							<textarea id="textarea-{{$element->eform_submit_count}}" name="" class="form-control" maxlength="3500" placeholder="Important information that the Language Secretariat needs to know e.g. 2nd prefered course to take, etc."></textarea>
							
						</div>

		                <div class="form-group">
							<span id="{{$element->eform_submit_count}}" class="schedule-count btn-accept hidden">
			                	<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-accept-btn btn btn-primary btn-space"><span><i class="fa fa-thumbs-up"></i></span> No Change </button>		                	 	
			                </span>
		                

		                	<button id="{{$element->eform_submit_count}}" data-indexid="{{$element->INDEXID}}" data-tecode="{{$element->Te_Code}}" data-term="{{$element->Term}}" type="button" class="modal-save-btn btn btn-success btn-space pull-right"><span><i class="fa fa-exchange"></i></span> Assign Course</button>
			                
			                <input type="hidden" name="_token" value="{{ Session::token() }}">
			                {{ method_field('PUT') }}
		                </div>
			        </form>
		    	</div>
            </div>
        	<div class="overlay">
        		<i class="fa fa-refresh fa-spin"></i>
        	</div>
        </div>
    </div>
	@endforeach
</div>

<script>	
$('.modal-accept-btn').on('click', function() {
	var eform_submit_count = $(this).attr('id');
	var qry_tecode = $(this).attr('data-tecode');
	var qry_indexid = $(this).attr('data-indexid');
	var qry_term = $(this).attr('data-term');
	var token = $("input[name='_token']").val();


	$.ajax({
		url: '{{ route('teacher-nothing-to-modify') }}',
		type: 'PUT',
		data: {eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
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
		var L = $("input[name='L']").val();

		  $.ajax({
	        url: '{{ route('teacher-assign-course-view') }}',
	        type: 'GET',
	        data: {indexid:qry_indexid, L:L,_token: token},
	      })
	      .done(function(data) {
	        console.log("show assign view : success");
	        $('.modal-body-content').html(data)
	      })
	});
		
});

$('.modal-save-btn').on('click', function() {
	var eform_submit_count = $(this).attr('id');
	var qry_tecode = $(this).attr('data-tecode');
	var qry_indexid = $(this).attr('data-indexid');
	var qry_term = $(this).attr('data-term');
	var token = $("input[name='_token']").val();
	var Te_Code = $("select#"+eform_submit_count+"[name='Te_Code'].course_select_no").val();
	var schedule_id = $("select#schedule-"+eform_submit_count+"[name='schedule_id']").val();

	$(".overlay").fadeIn('fast'); 

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
		var L = $("input[name='L']").val();

		$.ajax({
			url: '{{ route('teacher-assign-course-view') }}',
			type: 'GET',
			data: {indexid:qry_indexid, L:L,_token: token},
		})
		.done(function(data) {
			console.log("show assign view : success");     
			// $('.modal-body-content').html(data);        
		})

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
		

});
</script>

<script type="text/javascript">
$(document).ready(function() {
	var INDEXID = $("input[name='INDEXID']").val();
	var L = $("input[name='L']").val();
	var term = $("input[name='Term']").val();
	var token = $("input[name='_token']").val();

	var promises = [];
	$('.schedule-count').each(function(index, val) {
		var eform_submit_count = $(this).attr('id');

		console.log('eform_submit_count '+eform_submit_count)
		promises.push($.ajax({
			url: '{{ route('teacher-check-schedule-count') }}',
			type: 'GET',
			data: {eform_submit_count:eform_submit_count, INDEXID:INDEXID, L:L, term_id:term, _token:token},
		})
		.done(function(data) {
			console.log(data)
			if (data == 1) {
				$('span#'+eform_submit_count+'.btn-accept').removeClass('hidden');
			}
			
		})
		.fail(function() {
			console.log("error");
			alert("Ooops! An error occured. Click OK to reload.");
            window.location.reload();
		})
		.always(function() {
			console.log("complete check schedule count for button");
		}));
	});

	$.when.apply($('.schedule-count'), promises).then(function() {
        $(".overlay").fadeOut(600);
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

<script>
$('#modalshow').on('hidden.bs.modal', function (event) {

	console.log(event.target)
	// alert( "This will be displayed only once." );
 //  	$( this ).off( event );
	
	$(".preloader2").fadeIn('fast');
	var Code = $("button[id='enterResultsBtn'].btn-success").val();
	var token = $("input[name='_token']").val();

	$("button[id='enterResultsBtn'][value='"+Code+"']").addClass('btn-success');
	$("button[id='enterResultsBtn'][value='"+Code+"']").removeClass('btn-default');
	$("button").not("button[id='enterResultsBtn'][value='"+Code+"']").addClass('btn-default');
	$("button").not("button[id='enterResultsBtn'][value='"+Code+"']").removeClass('btn-success');

	$.ajax({
	  url: "{{ route('teacher-enter-results') }}", 
	  method: 'POST',
	  data: {Code:Code, _token:token},
	})
	.done(function(data) {

		$(".students-here").html(data);
		$(".students-here").html(data.options);
		console.log("inserted student table");
	})
	.fail(function(data) {
		console.log("error");
		alert("An error occured. Click OK to reload.");
		window.location.reload();
	})
	.always(function(data) {
		console.log("complete close modal");
	});
});
</script>