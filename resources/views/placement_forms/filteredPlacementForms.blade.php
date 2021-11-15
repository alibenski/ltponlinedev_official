@extends('shared_template')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
	<style>
	div.resizable {
		resize: both;
		overflow: auto;
		width: 180px;
		height: 180px;
		margin: 0px;
		padding: 2px;
		border: 1px solid black;
		display:block;

		}
	</style>
@stop

@section('content')

<div class="alert alert-warning col-sm-12">
	<h4 class="text-center"><strong><i class="fa fa-gear"></i> Manage Non-Assigned Placement Test Forms</strong></h4>
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
				@if(Request::filled('L')) / 
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
				@if(Request::filled('DEPT')) / {{ Request::input('DEPT') }} @else @endif 
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
<div class="row col-sm-12">
	<a href="{{ route('placement-form-approved-view',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term'), 'is_self_pay_form' => \Request::input('is_self_pay_form') ]) }}" target="_blank" class="btn btn-info"><i class="fa fa-download"></i> Extract Displayed Placement Forms 
		@if (Request::filled('L'))
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
</div>
{{ $placement_forms->links() }}
<div class="table-responsive col-sm-12 filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	        	<th>Operation</th>
	            <th>Validated/Assigned Course?</th>
	            <th>Course</th>
	            <th>Name</th>
	            {{-- <th>Convoked?</th> --}}
	            <th>Language</th>
	            <th>Preferred Days</th>
	            <th>Preferred Time</th>
	            <th>Preferred Course Comment</th>
	            <th>Student Comment</th>
	            <th>Flexbile?</th>
	            <th>Waitlisted</th>
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
					<input name="formID" class="form-id" type="hidden" value="{{ $form->id }}">
					{{-- <button class="show-modal btn btn-warning" data-index="{{$form->INDEXID}}" data-tecode="{{$form->Te_Code}}" data-term="{{$form->Term}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button> --}}
                    {{-- <a href="{{ route('placement-form.edit', [$form->id]) }}" target="_blank" class="btn btn-warning" style="margin: 1px;"><span class="glyphicon glyphicon-envelope"></span> Convoke</a>  --}}
                    <a href="{{ route('placement-form-assign', [$form->id]) }}" target="_blank" class="btn btn-success" style="margin: 1px;"><span class="glyphicon glyphicon-edit"></span> Assign Course</a> 
                </td>
                <td>
                	@if(empty($form->updated_by_admin)) <span class="label label-danger margin-label">Not Assigned </span>
					@else
					  @if ($form->modified_by)
					    <span class="label label-success margin-label">Yes by @if($form->modifyUser->name) {{$form->modifyUser->name }}  @else   User ID {{ $form->modified_by }} @endif </span>
					  @endif
					@endif
                </td>
                <td>
                	@if(empty($form->updated_by_admin)) <span class="label label-danger margin-label">Not Assigned </span>
					@else
					  @if ($form->modified_by)
					    <span> {{$form->courses->Description}} </span>
					  @endif
					@endif
					
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
				<td>{{ $form->dayInput }}</td>
				<td>{{ $form->timeInput }}</td>
				<td><div class="resizable">{{ $form->course_preference_comment }}</div></td>
				<td>{{ $form->std_comments }}</td>
				<td>@if($form->flexibleBtn == 1)<span class="glyphicon glyphicon-ok text-success"></span> Yes @else <span class="glyphicon glyphicon-remove text-danger"></span> Not flexible @endif</td>
				<td><div class="waitlist-cell"></div></td>
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

	var indexArray = [];
    
    /* look for all checkboxes that have name of 'schedule_id' attached to it and check if it was checked */
    $('input[name="formID"]').each(function() {
      indexArray.push($(this).val());
    });

	$.ajax({
        url: "{{ route('ajax-check-if-waitlisted') }}", 
        method: 'GET',
		data: {indexArray:indexArray},
        success: function(data, status) {
            console.log(data)
			$.each(data, function(x, y) {
					$("input[name='formID']").each(function() {
						if ($(this).val() == y.id) {
							if (y.waitlist == 1) {
								$(this).closest('tr').find('.waitlist-cell').html("Yes in Term "+y.details[0].terms.Term_Code+" for "+y.details[0].courses.Description);
							}
						}
					});
				});
        
        }
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