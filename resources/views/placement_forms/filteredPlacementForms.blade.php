@extends('admin.no_sidebar_admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop

@section('content')

<div class="alert alert-warning col-sm-12">
	<h4 class="text-center"><strong>Placement Test Forms Not Assigned to Language Course</strong></h4>
</div>

@include('admin.partials._termSessionMsg')

<div class="form-group col-sm-12">
	<form method="GET" action="{{ route('placement-form-filtered',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'is_self_pay_form' => Request::input('is_self_pay_form'), 'Term' => Session::get('Term')]) }}">
		
		@include('admin.partials._filterIndexNoTeCode')

        <!-- submit button included admin.partials._filterIndex view -->
        	<a href="{{ route('placement-form-filtered',[ 'Term' => Session::get('Term')]) }}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
        </div>

    </form>

    {{-- <form method="GET" action="{{ route('placement-form-filtered') }}">
    	<input name="Term" type="hidden" value="{{  Session::get('Term') }}">
    	<input name="L" type="hidden" value="{{ Request::input('L') }}">
    	<input name="DEPT" type="hidden" value="{{ Request::input('DEPT') }}">
        
            <label for="search" class="control-label">Search by name/email:</label>         
        <div class="input-group">  
            <input type="text" name="search" class="form-control" placeholder="Enter name here">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-info button-prevent-multi-submit"><i class="glyphicon glyphicon-search"></i> Search</button>
                <button type="submit" class="btn btn-danger button-prevent-multi-submit"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
            </div>
        </div>
    </form>  --}}   
</div>
<div class="col-sm-4 col-xs-12 pull-right">
	@if(Session::has('Term'))
	<div class="info-box">
		<span class="info-box-icon bg-orange"><i class="fa fa-list"></i></span>
		<div class="info-box-content">
			<p>Currently Viewing:</p> 
				@if(Session::has('Term'))Term Code: {{ Session::get('Term') }}@else @endif 	
				@if(Request::has('L')) / 
					<strong> 	
					@if(Request::input('L') == 'A') Arabic
					@elseif(Request::input('L') == 'C') Chinese
					@elseif(Request::input('L') == 'E') English
					@elseif(Request::input('L') == 'F') French
					@elseif(Request::input('L') == 'R') Russian
					@elseif(Request::input('L') == 'S') Spanish
					@endif
					</strong>
				@else / Viewing All Languages
				@endif 
				@if(Request::has('DEPT')) / {{ Request::input('DEPT') }} @else @endif 
			<p>
				Total count: {{ $count }} not assigned to a course
			</p>
		</div>
	</div>
</div>
	@else 
	@endif

@if(is_null($placement_forms))

@else
{{ $placement_forms->links() }}
<div class="table-responsive col-sm-12 filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	        	<th>Operation</th>
	            <th>Name</th>
	            {{-- <th>Convoked?</th> --}}
	            {{-- <th>Assigned Course?</th> --}}
	            <th>Language</th>
	            <th>HR Approval</th>
	            <th>Payment Status</th>
	            <th>Organization</th>
	            {{-- <th>Student Cancelled?</th> --}}
	            <th>Exam Date</th>
	            {{-- <th>Manager Approval</th> --}}
	            {{-- <th>ID Proof</th>
	            <th>Payment Proof</th> --}}
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($placement_forms as $form)
			<tr>
				<td>
					{{-- <button class="show-modal btn btn-warning" data-index="{{$form->INDEXID}}" data-tecode="{{$form->Te_Code}}" data-term="{{$form->Term}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button> --}}
                    {{-- <a href="{{ route('placement-form.edit', [$form->id]) }}" target="_blank" class="btn btn-warning" style="margin: 1px;"><span class="glyphicon glyphicon-envelope"></span> Convoke</a>  --}}
                    <a href="{{ route('placement-form-assign', [$form->id]) }}" target="_blank" class="btn btn-success" style="margin: 1px;"><span class="glyphicon glyphicon-edit"></span> Assign Course</a> 
                </td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				{{-- <td>
				@if(is_null($form->convoked)) - @else 
					@if($form->convoked == 1) Yes
					@elseif($form->convoked == 0) No 
					@endif
				@endif
				</td> --}}
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
				{{-- <td>
				@if(empty($form->assigned_to_course)) - @else 
					@if($form->assigned_to_course == 1) Yes 
					@else No 
					@endif
				@endif
				</td> --}}
				<td>
				@if(empty($form->DEPT)) None @else {{ $form->DEPT }}  @endif
				</td>
				{{-- <td>
					@if( is_null($form->cancelled_by_student))
					@else <span id="status" class="label label-danger margin-label">YES</span>
					@endif
				</td> --}}
				<td>@if ($form->placementSchedule->is_online == 1) Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} @else {{ $form->placementSchedule->date_of_plexam }} @endif</td>
				{{-- <td>
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
				</td> --}}
				{{-- <td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
				</td>
				<td>
				@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
				</td> --}}
				<td>{{ $form->created_at}}</td>
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
    placeholder: "Select Here",
    });
});
</script>
<script language="javascript">
// setTimeout(function(){
//    window.location.reload(1);
// }, 3000);
</script>
<script language="javascript">
	window.setInterval(function(){
    if(localStorage["update"] == "1"){
        localStorage["update"] = "0";
        window.location.reload();
    }
}, 500);
</script>
@stop