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
		<h3 class="tlt text-center"><strong>{{ date('Y', strtotime($term->Term_Begin)) }}  {{ $term->Comments }} TERM - {{ strtoupper(trans($placement_forms->first()->languages->name)) }} PLACEMENT TEST </strong></h3>
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
			<tr id="">
				<td>
					<p>@if(empty($form->users)) None @else <strong> {{ $form->users->name }} </strong>  @endif </p>
					<p>Email: @if(empty($form->users)) None @else {{ $form->users->email }} @endif </p>
					<p>Contact #: @if(empty($form->users)) None @else {{ $form->users->sddextr->PHONE }} @endif </p>
					<p>Org: @if(empty($form->DEPT)) None @else {{ $form->DEPT }} @endif </p>
					<p>{{$form->id}}</p>
				</td>
				<td>{{ $form->languages->name }}</td>
				<td>{{ $form->std_comments }}</td>
				<td>{{ $form->course_preference_comment }}</td>
				<td>{{ $form->dayInput }}</td>
				<td>{{ $form->timeInput }}</td>
				<td>
				@if ($form->placementSchedule->is_online == 1) <span class="label label-success">Online</span> from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} 
				@else {{ $form->placementSchedule->date_of_plexam }} 
				@endif
				</td>
				<td>
				@if ($form->placementSchedule->is_online != 1)
					<input class="timepicker" name="timeInput" placeholder="choose time here">
				@endif
				</td>
				<td>
				@if ($form->placementSchedule->is_online != 1)
					<select class="select-room" name="room">
                            <option value="">choose room here</option>
                        @foreach ($rooms as $id => $name)
                            <option value="{{ $id }}"> {{ $name }}</option>
                        @endforeach
                    </select>
                @endif
				</td>
				<td>
				@if ($form->placementSchedule->is_online != 1)
					<select id="Tch_ID_select_{{ $form->id }}" class="select-teacher" name="Tch_ID">
                        <option value="">--- Select Teacher ---</option>
                        @foreach ($teachers as $valueTeacher)
                            <option value="{{$valueTeacher->Tch_ID}}">{{$valueTeacher->Tch_Name}} </option>
                        @endforeach
                    </select>
                @endif
				</td>
				<td>
					<textarea name="restultInput" id="" cols="30" rows="5"></textarea>
				</td>
				<td>
					<textarea name="teacherComment" id="" cols="30" rows="5"></textarea>
				</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
</div>
