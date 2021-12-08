@extends('shared_template')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')
@if(Session::has('Term'))
<div class="alert alert-success col-sm-12">
    <h4><i class="icon fa fa-bullhorn fa-2x"></i>Reminder!</h4>
    <p>
        All <b>Term</b> fields are currently set to: <strong>{{ Session::get('Term') }}</strong>
    </p>
</div>
@endif
<div class="alert alert-info col-sm-12">
	<h4 class="text-center"><strong>Live Preview of Enrolment and Placement Forms</strong></h4>
</div>

@if(empty(Request::all())) 
@else

	@if(is_null($enrolment_forms))

	@else
	<div class="row">
		<div class="col-sm-12">
			<div class="col-sm-4">

				@foreach ($enrolment_forms as $item)
					@if ($loop->first) {{-- only get the first item of the loop --}}
					<div class="">
						<h2>{{ $item->courses->Description }}</h2>
					</div>
					@endif
				@endforeach

				<h3>Total # <span class="label label-info">{{$count}}</span></h3>
			</div>
			@if (Request::filled('Te_Code')||Request::filled('L'))
			<div class="pull-right col-sm-4">
				<div class="box box-default">
					<div class="box-header with-border">
			            <h3 class="box-title">Current Filters:</h3>
			            <div class="box-tools pull-right">
			                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			            </div>
			        </div>
					<div class="box-body">
						
						{{-- <p>Language: {{Request::get('L')}}</p> --}}
						<p>Course Code: {{Request::get('Te_Code')}}</p>
					
					</div>
				</div>
			</div>
			@endif
		
		</div>
	</div>
	{{-- {{ $enrolment_forms->links() }} --}}
	<div class="filtered-table table-responsive">
		<table class="table table-bordered table-striped">
		    <thead>
		        <tr>
		        	<th>Validated/Assigned Course?</th>
		            <th>Name</th>
		            <th>Email</th>
		            <th>Contact No.</th>
		            <th>Course</th>
		            <th>Schedule</th>
		            <th>Flexible?</th>
		            <th>Organization</th>
		            <th>Student Cancelled?</th>
		            <th>HR Approval</th>
		            <th>Payment Status</th>
		            {{-- <th>ID Proof</th>
		            <th>Payment Proof</th> --}}
		            <th>Comment</th>
		            <th>Time Stamp</th>
		            <th>Cancel Date/Time Stamp</th>
		        </tr>
		    </thead>
		    <tbody>
				@foreach($enrolment_forms as $form)
				<tr @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
					<td>
						@if(is_null($form->updated_by_admin)) <span class="label label-danger margin-label">Not Assigned </span>
		                @else
		                  @if ($form->updated_by_admin === 1)
		                    <p><span class="label label-success margin-label">Yes by {{$form->modifyUser->name }} </span></p>
		                    <p><span class="label label-success margin-label">{{ $form->courses->Description }}  </span></p>
		                    <p><span class="label label-success margin-label">{{$form->schedule->name }} </span></p>
						  @elseif($form->updated_by_admin === 0)
						    <p><span class="label label-warning margin-label">Verified and Not Assigned by {{$form->modifyUser->name }} </span></p>
		                  @endif
		                @endif
					</td>
					<td>
						<h4>@if(empty($form->users->name)) None @else {{ $form->users->name }} <small>[{{ $form->INDEXID }}]</small> @endif</h4>
							@if($form->placement_schedule_id)
								<p><span class="label label-warning margin-label">Placement Form</span></p>
							@endif
						
						<div class="student-priority-status-here-{{ $form->INDEXID }}"></div>
						<div class="student-classroom-here-{{ $form->INDEXID }}"></div>
						<div class="student-waitlisted-here-{{ $form->INDEXID }}"></div>
						<div class="student-within-two-terms-here-{{ $form->INDEXID }}"></div>
					</td>
					<td>
					@if(empty($form->users->email)) None @else {{ $form->users->email }} @endif
					</td>
					<td>
					@if(empty($form->users->sddextr->PHONE)) None @else {{ $form->users->sddextr->PHONE }} @endif
					</td>
					<td>{{ $form->courses->Description }}</td>
					<td>
						{{-- <a id="modbtn" class="btn btn-default btn-space" data-toggle="modal" href="#modalshow" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-approval="{{ $form->approval }}" data-formx="{{ $form->form_counter }}" data-mtitle="{{ $form->courses->EDescription }}"> View</a> --}}
						<a id="modbtn" class="btn btn-info btn-space" data-toggle="modal" href="#modalshow" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-formx="{{ $form->form_counter }}" data-mtitle=""><span><i class="fa fa-eye"></i></span> Wishlist Schedule</a>
						<div class="student-count-schedule-{{ $form->INDEXID }}"></div>
					</td>
					<td>
						@if($form->flexibleBtn == 1)
                                    <span class="label label-success margin-label">Yes</span>
                                  @else
                          -
                                  @endif
					</td>
					<td>{{ $form->DEPT }}</td>
					<td>
						@if( is_null($form->cancelled_by_student))
						@else <span id="status" class="label label-danger margin-label">YES</span>
						@endif
					</td>
					<td>
						@if(is_null($form->is_self_pay_form))
							@if(in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
								<span id="status" class="label label-info margin-label">
								N/A - Non-paying organization</span>
							@else
								@if(is_null($form->approval) && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 0 && (is_null($form->approval_hr) || isset($form->approval_hr)))
								<span id="status" class="label label-danger margin-label">
								N/A - Disapproved by Manager</span>
								@elseif($form->approval == 1 && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 1 && $form->approval_hr == 1)
								<span id="status" class="label label-success margin-label">
								Approved</span>
								@elseif($form->approval == 1 && $form->approval_hr == 0)
								<span id="status" class="label label-danger margin-label">
								Disapproved</span>
								@endif
							@endif
						@else
						<span id="status" class="label label-info margin-label">
						N/A - Self-Payment</span>
						@endif
					</td>

					<td>
						@if(is_null($form->is_self_pay_form))
	                    <span id="status" class="label label-info margin-label">N/A</span>
	                    @else
	                      @if($form->selfpay_approval === 1)
	                      <span id="status" class="label label-success margin-label">Approved</span>
	                      @elseif($form->selfpay_approval === 2)
	                      <span id="status" class="label label-warning margin-label">Pending Valid Document</span>
	                      @elseif($form->selfpay_approval === 0)
	                      <span id="status" class="label label-danger margin-label">Disapproved</span>
	                      @else 
	                      <span id="status" class="label label-info margin-label">Waiting for Admin</span>
	                      @endif
	                    @endif
					</td>

					{{-- <td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
					</td>
					<td>
					@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
					</td> --}}
					<td>
						@if($form->placement_schedule_id)
								<button type="button" class="show-placement-comments btn btn-warning btn-space" data-toggle="modal"> View </button>
						@else
							@if ($form->std_comments)
								<button type="button" class="show-std-comments btn btn-primary btn-space" data-toggle="modal"> View </button>
							@endif
						@endif
						<input type="hidden" name="eform_submit_count" value="{{$form->eform_submit_count}}">
						<input type="hidden" name="term" value="{{$form->Term}}">
						<input type="hidden" name="indexno" value="{{$form->INDEXID}}">
						<input type="hidden" name="tecode" value="{{$form->Te_Code}}">
						<input type="hidden" name="L" value="{{$form->courses->language->code}}">
						<input type="hidden" name="formL" value="{{$form->L}}">
						<input type="hidden" name="_token" value="{{ Session::token() }}">
					</td>
					<td>{{ $form->created_at}}</td>
					<td>{{ $form->deleted_at}}</td>
				</tr>
				@endforeach
		    </tbody>
		</table>
		{{-- {{ $enrolment_forms->links() }} --}}
	</div>
	@endif

<!-- modal for enrolments form chosen schedule -->
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

<!-- Modal form to show student comments on regular forms -->
<div id="showStdComments" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title-std-comments"><i class="fa fa-comment fa-2x text-primary"></i> Student Comment</h4>
            </div>
            <div class="modal-body">
				@if(empty($enrolment_forms))

				@else
					@if(count($enrolment_forms) == 0)
					
					@else
	                <div class="panel-body modal-body-std-comments"></div>
                	@endif
                @endif	  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal form to show student comments on placement forms -->
<div id="showPlacementComments" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title-placement-comments"><i class="fa fa-comment fa-2x text-warning"></i> Student Comment</h4>
            </div>
            <div class="modal-body">
				@if(empty($enrolment_forms))

				@else
					@if(count($enrolment_forms) == 0)
					
					@else
	                <div class="panel-body modal-body-placement-comments"></div>
                	@endif
                @endif	  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endif

@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
	var arr = [];
	var eform_submit_count = [];
	var token = $("input[name='_token']").val();
	var term = $("input[name='term']").val();
	var L = $("input[name='L']").val();

	$("input[name='indexno']").each(function() {
		var indexno = $(this).val();
		var each_eform_submit_count = $(this).closest("tr").find("input[name='eform_submit_count']").val();
		arr.push(indexno); //insert values to array per iteration
		eform_submit_count.push(each_eform_submit_count); //insert values to array per iteration
	});
	console.log(eform_submit_count)

	if (term) {

		$.ajax({
			url: '{{ route('ajax-preview-get-student-current-class') }}',
			type: 'POST',
			data: {arr:arr,term:term,_token:token},
		})
		.done(function(data) {
			// console.log(data)
			$.each(data, function(x, y) {
				// console.log(y.INDEXID)
				$("input[name='indexno']").each(function() {
					if ($(this).val() == y.INDEXID) {
						$('div.student-classroom-here-'+y.INDEXID).html('<strong>Current Class:</strong> <p><span class="label label-info margin-label">'+y.course_name+'</span></p><p><span class="label label-info margin-label">'+y.teacher+'</span></p>');
					}
				});
			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});


		$.ajax({
			url: '{{ route('ajax-preview-get-student-priority-status') }}',
			type: 'POST',
			data: {arr:arr,eform_submit_count:eform_submit_count,L:L,term:term,_token:token},
		})
		.done(function(data) {
			// console.log(data[0]);
			$.each(data[0], function(x, y) {
				$("input[name='indexno']").each(function() {
					if ($(this).val() == y) {
						$('div.student-priority-status-here-'+y).html('<p><span class="label label-info margin-label">Re-enrolment</span></p>');
					}
				});
			});
			// console.log(data[1]);
			$.each(data[1], function(x, y) {
				$("input[name='indexno']").each(function() {
					if ($(this).val() == y) {
						$('div.student-priority-status-here-'+y).html('<p><span class="label label-default margin-label">Not in a class</span></p>');
					}
				});
			});
			// console.log(data[2]);
			$.each(data[2], function(x, y) {
				$("input[name='indexno']").each(function() {
					if ($(this).val() == y) {
						$('div.student-waitlisted-here-'+y).html('<p><span class="label label-default margin-label bg-purple">Waitlisted</span></p>');
					}
				});
			});
			// console.log(data[3]);
			$.each(data[3], function(x, y) {
				$("input[name='indexno']").each(function() {
					if ($(this).val() == y) {
						$('div.student-within-two-terms-here-'+y).html('<p><span class="label label-default margin-label bg-maroon">Within 2 Terms</span></p>');
					}
				});
			});
			console.log(data[4]);
			$.each(data[4], function(x, y) {
				$("input[name='indexno']").each(function() {
					if ($(this).val() == x) {
						$('div.student-count-schedule-'+x).html('<p><span class="label label-default"> '+y+' schedule(s) originally chosen</span></p>');
					}
				});
			});

			$.each(data[5], function(x, y) {
				$("input[name='indexno']").each(function() {
					if ($(this).val() == x) {
						$('div.student-count-schedule-'+x).html('<p><span class="label label-default"> '+y+' schedule(s) originally chosen</span></p>');
					}
				});
			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	}
});
</script>

<script type="text/javascript">
$(document).on('click', '.show-std-comments', function() {
	var indexno = $(this).closest("tr").find("input[name='indexno']").val();
	var tecode = $(this).closest("tr").find("input[name='tecode']").val();
	var eform_submit_count = $(this).closest("tr").find("input[name='eform_submit_count']").val();
	var term = $(this).closest("tr").find("input[name='term']").val();
	var token = $("input[name='_token']").val();
    $('#showStdComments').modal('show'); 

    $.post('{{ route('ajax-std-comments') }}', {'indexno':indexno, 'tecode':tecode, 'term':term,  'eform_submit_count':eform_submit_count, '_token':token}, function(data) {
          $('.modal-body-std-comments').html(data);
      });
});

$(document).on('click', '.show-placement-comments', function() {
	var indexno = $(this).closest("tr").find("input[name='indexno']").val();
	var L = $(this).closest("tr").find("input[name='formL']").val();
	var eform_submit_count = $(this).closest("tr").find("input[name='eform_submit_count']").val();
	var term = $(this).closest("tr").find("input[name='term']").val();
	var token = $("input[name='_token']").val();
    $('#showPlacementComments').modal('show'); 

    $.post('{{ route('ajax-placement-comments') }}', {'indexno':indexno, 'L':L, 'term':term,  'eform_submit_count':eform_submit_count, '_token':token}, function(data) {
          $('.modal-body-placement-comments').html(
          	'<label for="">Comment: </label> '+ data[0]+
          	'<br><label for="">Course Preference: </label> '+ data[1]+
          	'<br><label for="">Time Preference: </label> '+ data[2]+
          	'<br><label for="">Day Preference: </label> '+ data[3]
          	);
      });
});

$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });

    // $('#modalshow').on('show.bs.modal', function (event) {
    //   var link = $(event.relatedTarget); // Link that triggered the modal
    //   var dtitle = link.data('mtitle');
    //   var dindexno = link.data('indexno');
    //   var dtecode = link.data('tecode');
    //   var dterm = link.data('term');
    //   var dapproval = link.data('approval');
    //   var dFormCounter = link.data('formx');
    //   var token = $("input[name='_token']").val();
    //   var modal = $(this);
    //   modal.find('.modal-title').text(dtitle);

    //   var token = $("input[name='_token']").val();      

    //   $.post('{{ route('ajax-show-modal') }}', {'indexno':dindexno, 'tecode':dtecode, 'term':dterm, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
    //       console.log(data);
    //       $('.modal-body-schedule').html(data)
    //   });
    // });

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

      $.post('{{ route('ajax-preview-modal') }}', {'indexno':dindexno, 'tecode':dtecode, 'term':dterm, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
          // console.log(data);
          $('.modal-body-schedule').html('');
          $('.modal-body-schedule').html(data);
      });
    });

});
</script>
<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
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
  }); 
</script>
@stop