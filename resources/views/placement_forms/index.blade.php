@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
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
			<tr>
				<td>
					<button class="show-modal btn btn-warning" data-index="{{$form->INDEXID}}" data-tecode="{{$form->Te_Code}}" data-term="{{$form->Term}}" disabled><span class="glyphicon glyphicon-eye-open" ></span> Show</button>
                    {{-- <a href="{{ route('placement-form.edit', [$form->id]) }}" class="btn btn-warning"><span class="glyphicon glyphicon-eye-open"></span> Show</a>  --}}
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
@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
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