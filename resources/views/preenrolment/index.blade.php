@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="alert alert-info col-sm-12">
	<h4 class="text-center"><strong>Regular Enrolment Forms</strong></h4>
</div>

@include('admin.partials._termSessionMsg')

<div class="row">
    <div class="col-sm-12">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Filters:</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    <div class="box-body">
    <form method="GET" action="{{ route('preenrolment.index',['L' => \Request::input('L'), 'Te_Code' => \Request::input('Te_Code'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term')]) }}">
		
		@include('admin.partials._filterIndex')

        <!-- submit button included admin.partials._filterIndex view -->
        	<a href="/admin/preenrolment" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
        </div>

    </form>
</div>
	<div class="box-footer">
        <div class="form-group">    
            <div class="input-group-btn">
		        <a href="{{ route('preenrolment.index', ['L' => \Request::input('L'), 'Te_Code' => \Request::input('Te_Code'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
		        <a href="{{ route('preenrolment.index', ['L' => \Request::input('L'), 'Te_Code' => \Request::input('Te_Code'), 'DEPT' => Request::input('DEPT'),'Term' => Session::get('Term'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
			</div>
        </div>
    </div>
    </div>
    </div>
</div>

@if(empty(Request::all())) 
@else

	@if(is_null($enrolment_forms))

	@else
	{{ $enrolment_forms->links() }}
	<div class="filtered-table table-responsive">
		<table class="table table-bordered table-striped">
		    <thead>
		        <tr>
		            <th>Name</th>
		            <th>Term</th>
		            <th>Organization</th>
		            <th>Course</th>
		            <th>Schedule</th>
		            <th>Student Cancelled?</th>
		            <th>Manager Approval</th>
		            <th>HR Approval</th>
		            <th>ID Proof</th>
		            <th>Payment Proof</th>
		            <th>Time Stamp</th>
		        </tr>
		    </thead>
		    <tbody>
				@foreach($enrolment_forms as $form)
				<tr>
					<td>
					@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
					</td>
					<td>{{ $form->Term }}</td>
					<td>{{ $form->DEPT }}</td>
					<td>{{ $form->courses->Description }}</td>
					<td>{{ $form->schedule->name }}</td>
					<td>
						@if( is_null($form->cancelled_by_student))
						@else <span id="status" class="label label-danger margin-label">YES</span>
						@endif
					</td>
					<td>
						@if($form->is_self_pay_form == 1)
						<span id="status" class="label label-info margin-label">
						N/A - Self-Payment</span>
						@elseif(is_null($form->approval))
						<span id="status" class="label label-warning margin-label">
						Pending Approval</span>
						@elseif($form->approval == 1)
						<span id="status" class="label label-success margin-label">
						Approved</span>
						@elseif($form->approval == 0)
						<span id="status" class="label label-danger margin-label">
						Disapproved</span>
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
					<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
					</td>
					<td>
					@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
					</td>
					<td>{{ $form->created_at}}</td>
				</tr>
				@endforeach
		    </tbody>
		</table>
		{{ $enrolment_forms->links() }}
	</div>
	@endif

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