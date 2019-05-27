@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="alert alert-warning col-sm-12">
	<h4 class="text-center"><strong>Placement Test Forms</strong></h4>
</div>

@include('admin.partials._termSessionMsg')

<div class="row">
    <div class="col-sm-12">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">View All Placement Forms:</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    <div class="box-body">
    <form method="GET" action="{{ route('placement-form.index',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term')]) }}">
		
		@include('admin.partials._filterIndex')

        <!-- submit button included admin.partials._filterIndex view -->
        	<a href="/admin/placement-form/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
        	<a href="{{ route('placement-form-filtered',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term')]) }}" target="_blank" class="btn btn-warning"><i class="fa fa-gear"></i> Manage Non-assigned Placement Test Forms</a>
        	
        </div>

    </form>
</div>
	<div class="box-footer">
        <div class="form-group">    
            <div class="input-group-btn">
				<a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Session::get('Term'),'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
		        <a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Session::get('Term'), 'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'), 'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
		    </div>
        </div>
    </div>
    </div>
    </div>
</div>


@if(count($placement_forms) < 1)

@else

<div class="row">
	<div class="col-sm-12">
		@if (Request::has('overall_approval'))
		<a href="{{ route('placement-form-approved-view',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term'), 'is_self_pay_form' => \Request::input('is_self_pay_form') ]) }}" target="_blank" class="btn btn-info"><i class="fa fa-download"></i> Extract Approved Placement Forms Without Cancelled Students 
			@if (Request::has('L'))
				@if (Request::get('L') == 'A') (Arabic)
				@elseif (Request::get('L') == 'C') (Chinese)
				@elseif (Request::get('L') == 'E') (English)
				@elseif (Request::get('L') == 'F') (French)
				@elseif (Request::get('L') == 'R') (Russian)
				@elseif (Request::get('L') == 'S') (Spanish)
				@endif
			@else
			(All Languages)
			@endif
		</a>
		@endif
	</div>
</div>
{{ $placement_forms->links() }}
<div class="table-responsive col-sm-12 filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	        	<th>Operation</th>
	        	<th>Validated/Assigned Course?</th>
	            <th>Name</th>
	            <th>Language</th>
	            <th>HR Approval</th>
	            <th>Payment Status</th>
	            <th>Student Cancelled?</th>
	            <th>Organization</th>
	            <th>Exam Date</th>
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	            <th>Cancel Date/Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($placement_forms as $form)
			<tr @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
				<td>
					@if($form->deleted_at)
					@else
					<a class="btn btn-info btn-space" data-toggle="modal" href="#modalshowplacementinfo" data-mid ="{{ $form->id }}" data-mtitle="Placement Form Info"><span><i class="fa fa-eye"></i></span> View Info</a>
					@endif


@if (is_null($form->deleted_at))
<button type="button" class="btn btn-danger btn-space placement-delete" data-toggle="modal"><i class="fa fa-remove"></i> Reject/Cancel Placement Test</button>
@else
<button type="button" class="btn btn-danger btn-space course-delete-tooltip" title="{{$form->admin_plform_cancel_comment}}" disabled=""><i class="fa fa-info-circle"></i> Cancelled</button>
	@if ($form->admin_plform_cancel_comment)
		<p><small><label>Cancellation Comment:</label> "{{$form->admin_plform_cancel_comment}}" - {{$form->cancelledBy->name}}</small></p>
	@endif
@endif

<div id="modalDeletePlacement-{{ $form->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
                <h4 class="modal-title">Admin Placement Cancellation</h4>
            </div>
            <div class="modal-body-placement-delete">
            	<div class="col-sm-12">	
	            	<form method="POST" action="{{ route('placement.destroy', [$form->INDEXID, $form->L, $form->Term, $form->eform_submit_count]) }}">
	            		
						<p>Index # {{ $form->INDEXID }} : {{ $form->users->name }}</p>
						<p>Placement Form : {{ $form->languages->name }}</p>
	            		<div class="form-group">
							<label class="control-label">Admin Comments: </label>

							<textarea id="placement-delete-textarea-{{$form->eform_submit_count}}" name="admin_plform_cancel_comment" class="form-control placement-delete" maxlength="3500" placeholder="Place important information about the cancellation of this form..."></textarea>
							
						</div>

	                    <input type="submit" @if (is_null($form->deleted_at))
	                      value="Reject/Cancel Placement Form"
	                    @else
	                      value="Cancelled"
	                    @endif  class="btn btn-danger btn-space" 
	                    @if (is_null($form->deleted_at))
	                    @else
	                      disabled="" 
	                    @endif>
	                    <input type="hidden" name="deleteTerm" value="{{ $form->Term }}">
	                    <input type="hidden" name="_token" value="{{ Session::token() }}">
	                    {{ method_field('DELETE') }}
	                </form>
            	</div>
            </div>
            <div class="modal-footer modal-background">
              
            </div>
        
        </div>
    </div>
</div>

                </td>
                <td>
					@if(empty($form->updated_by_admin)) <span class="label label-danger margin-label">Not Assigned </span>
					@else
					  @if ($form->modified_by)
					    <span class="label label-success margin-label">Yes by {{$form->modifyUser->name }} </span>
					  @endif
					@endif
				</td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>{{ $form->L }}</td>
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
				<td>
					@if( is_null($form->cancelled_by_student))
					@else <span id="status" class="label label-danger margin-label">YES</span>
					@endif
				</td>
				<td>
				@if(empty($form->DEPT)) None @else {{ $form->DEPT }}  @endif
				</td>
				<td>
					@if ($form->placementSchedule->is_online == 1) Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} 
					@else {{ $form->placementSchedule->date_of_plexam }} 
					@endif
				</td>

				<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
				</td>
				<td>
				@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
				</td>
				<td>{{ $form->created_at}}</td>
				<td>{{ $form->deleted_at}}</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
	{{ $placement_forms->links() }}
</div>
@endif
{{-- modal for placement forms --}}
<div id="modalshowplacementinfo" class="modal fade">
    <div class="modal-dialog  modal-lg">
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

@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
	var Term = "{{ Session::get('Term') }}";
    var token = $("input[name='_token']").val();
    console.log(Term)
    $.ajax({
    	url: '{{ route('ajax-check-batch-has-ran') }}',
    	type: 'GET',
    	data: {Term:Term,_token: token},
    })
    .done(function(data) {
    	if (!jQuery.isEmptyObject( data )) {
    		$(".course-delete").addClass('hidden');
    		$(".placement-delete").addClass('hidden');
    	}
    })
    .fail(function() {
    	console.log("error");
    })
    .always(function() {
    	console.log("complete check if batch has ran");
    });

});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });

    $('.course-delete-tooltip').tooltip();

	$('#modalshowplacementinfo').on('show.bs.modal', function (event) {
	  var link = $(event.relatedTarget); // Link that triggered the modal
	  console.log(link)
	  var did = link.data('mid');
	  var dtitle = link.data('mtitle');
	  var modal = $(this);
	  modal.find('.modal-title').text(dtitle);

	  var token = $("input[name='_token']").val();      

	  $.post('{{ route('ajax-show-modal-placement') }}', {'id':did, '_token':token}, function(data) {
	      console.log(data);
	      $('.modal-body-schedule').html(data)
	  });
	});

	$(document).on('click', '.placement-delete', function() {
		var placement_id = $(this).closest("tr").find("a[data-mid]").attr('data-mid');
		console.log(placement_id) 
	    $('#modalDeletePlacement-'+placement_id).modal('show'); 
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