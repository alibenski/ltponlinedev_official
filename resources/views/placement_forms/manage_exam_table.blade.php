<link href="{{ asset('textillate/assets/animate.css') }}" rel="stylesheet">
<div class="row">
	<div class="col-sm-12"
	@if ($placement_forms->first())	
		@if ($placement_forms->first()->L == 'A')
			style="background-color: #ff7243;color: #fff;" 
		@elseif($placement_forms->first()->L == 'C')
			style="background-color: #B22222;color: #fff;"
		@elseif($placement_forms->first()->L == 'E')
			style="background-color: #0079c1;color: #fff;"
		@elseif($placement_forms->first()->L == 'F')
			style="background-color: #338d11;color: #fff;"
		@elseif($placement_forms->first()->L == 'R')
			style="background-color: #6347b2;color: #fff;"
		@elseif($placement_forms->first()->L == 'S')
			style="background-color: #bf8124;color: #fff;"
		@endif
		>
		
		<h3 class="tlt"><strong>{{ date('Y', strtotime($term->Term_Begin)) }}  {{ $term->Comments }} TERM - {{ strtoupper(trans($placement_forms->first()->languages->name)) }} PLACEMENT TEST </strong></h3>
	@endif
	</div>
</div>

<div class="filtered-table">
	<table class="table table-bordered table-striped manage-exam-table">
	    <thead>
	        <tr>
	            <th>Information</th>
	            <th>Language</th>
	            <th>Comments Placement Exam</th>	            
	            <th>Comments Course Preference</th>	            
	            <th>Day Preferrence</th>
	            <th>Time Preferrence</th>
	            <th>Exam Date/ Format</th>
	            <th>Time</th>
	            <th>Room</th>
	            <th>Teacher</th>
	            <th>Result</th>
	            <th>Teacher Comments</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($placement_forms as $form)
			<tr>
				<td>
					<p>@if(empty($form->users)) None @else <strong> {{ $form->users->name }} </strong>  @endif </p>
					<p>Email: @if(empty($form->users)) None @else {{ $form->users->email }} @endif </p>
					<p>Contact #: @if(empty($form->users)) None @else {{ $form->users->sddextr->PHONE }} @endif </p>
					<p>Org: @if(empty($form->DEPT)) None @else {{ $form->DEPT }} @endif </p>
				</td>
				<td>{{ $form->languages->name }}</td>
				<td>{{ $form->std_comments }}</td>
				<td>{{ $form->course_preference_comment }}</td>
				<td>{{ $form->dayInput }}</td>
				<td>{{ $form->timeInput }}</td>
				<td>
				@if ($form->placementSchedule->is_online == 1) Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} 
				@else {{ $form->placementSchedule->date_of_plexam }} 
				@endif
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endforeach
	    </tbody>
	</table>
</div>

<script src="{{ asset('textillate/assets/jquery.fittext.js') }}"></script>
<script src="{{ asset('textillate/assets/jquery.lettering.js') }}"></script>
<script src="{{ asset('textillate/jquery.textillate.js') }}"></script>

<script>
	$(document).ready(function() {
		$('.tlt').textillate({ in: { effect: 'fadeInDown', shuffle: false, delay: 20, } 

		});
	});
</script>