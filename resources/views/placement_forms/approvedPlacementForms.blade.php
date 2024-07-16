@extends('admin.no_sidebar_admin')
@section('customcss')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content')
Total Count: {{ count($placement_forms) }}
<br>
<div class="filtered-table">
	<table id="sampol" class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>First Name</th>
	            <th>Last Name</th>
	            <th>Email</th>
	            <th>Organization</th>
	            <th>Language</th>
	            <th>Exam Date</th>
	            <th>Availability Day(s)</th>
	            <th>Availability Time(s)</th>
				<th>Availability Delivery Mode(s)</th>
				<th>Flexible Day?</th>
				<th>Flexible Time?</th>
				<th>Flexible Format?</th>
	            <th>Comments Placement Exam</th>	            
	            <th>Comments Course Preference</th>            
	            <th>Assigned to Course?</th>    
	            <th>Course</th>    
				<th>Admin Placement Form Comment (from Assign Course)</th>        
	            <th>Overall Approval</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($placement_forms as $form)
			<tr>
				<td>
				@if(empty($form->users)) None @else {{ $form->users->nameFirst }} @endif
				</td>
				<td>
				@if(empty($form->users)) None @else {{ $form->users->nameLast }} @endif
				</td>
				<td>
				@if(empty($form->users)) None @else {{ $form->users->email }} @endif
				</td>
				<td>
				@if(empty($form->DEPT)) None @else <strong> {{ $form->DEPT }} </strong> @endif
				</td>
				<td>{{ $form->L }}</td>
				<td>
				@if ($form->placementSchedule->is_online == 1) Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} 
				@else {{ $form->placementSchedule->date_of_plexam }} 
				@endif
				</td>
				<td>{{ $form->dayInput }}</td>
				<td>{{ $form->timeInput }}</td>
				<td>
					@if(!is_null($form->deliveryMode))
						@if($form->deliveryMode === 0) in-person
						@elseif($form->deliveryMode ===1) online
						@elseif($form->deliveryMode ===2) both in-person and online
						@endif
					@else
					-
					@endif
				
				</td>
				<td>
						@if(is_null($form->flexibleDay))
							-
						@elseif($form->flexibleDay === 1)
                        	Yes
                        @else
							NOT FLEXIBLE
                        @endif
					</td>
					<td>
						@if(is_null($form->flexibleTime))
							-
						@elseif($form->flexibleTime === 1)
							Yes
                        @else
							NOT FLEXIBLE
                        @endif
					</td>
					<td>
						@if(is_null($form->flexibleFormat))
							-
						@elseif($form->flexibleFormat === 1)
                            Yes
                        @else
							NOT FLEXIBLE
                        @endif
					</td>
				<td>{{ $form->std_comments }}</td>
				<td>{{ $form->course_preference_comment }}</td>
				<td>@if($form->assigned_to_course == 1) Yes @endif</td>
				<td>
					@if(is_null($form->updated_by_admin)) Not Assigned
					@else
					  @if ($form->modified_by)
					  	@if ($form->assigned_to_course === 1)
					    {{$form->courses->Description}}
						@elseif($form->assigned_to_course === 0)
						
						@endif
					  @endif
					@endif
				</td>
				<td>{{ $form->admin_plform_comment }}</td>
				<td>{{ $form->overall_approval }}</td>
				<td>{{ $form->created_at }}</td>
			</tr>
			@endforeach
		</tbody>
		<tfoot>
	        <tr>
	            <th>First Name</th>
	            <th>Last Name</th>
	            <th>Email</th>
	            <th>Organization</th>
	            <th>Language</th>
	            <th>Exam Date</th>
	            <th>Availability Day(s)</th>
	            <th>Availability Time(s)</th>
				<th>Availability Delivery Mode(s)</th>
				<th>Flexible Day?</th>
				<th>Flexible Time?</th>
				<th>Flexible Format?</th>
	            <th>Comments Placement Exam</th>	            
	            <th>Comments Course Preference</th>	
	            <th>Assigned to Course?</th>
				<th>Course</th>   
				<th>Admin Placement Form Comment (from Assign Course)</th>       
	            <th>Overall Approval</th>
	            <th>Time Stamp</th>
	        </tr>
	    </tfoot>
	</table>
</div>
@stop
@section('java_script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script>
	$('#sampol').DataTable({
		"fixedHeader": true,
		"deferRender": true,
    	"dom": 'B<"clear">lfrtip',
    	"buttons": [
		        'copy', 'csv', 'excel', 'pdf'
		    ],
	});
	$(".preloader2").fadeOut(600);
</script>
@stop
