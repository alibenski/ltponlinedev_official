<div class="row">
	<div class="col-sm-12">
		<div id="accordion">
			@foreach($show_classrooms as $classroom)
			<h3>Section # {{ $classroom->sectionNo }}</h3>
			<div>
				<p>Number of Students:</p>
				<p>Teacher: @if($classroom->Tch_ID) <strong>{{ $classroom->teachers->Tch_Name }}</strong> @else <span class="label label-danger">none assigned</span> @endif</p>
				@if(!empty($classroom->Te_Mon_Room))
				<p>Monday Room: <strong>{{ $classroom->roomsMon->Rl_Room }}</strong></p>
				<p>Monday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_BTime)) }}</strong></p>
				<p>Monday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Mon_ETime ))}}</strong></p>
				@endif
				@if(!empty($classroom->Te_Tue_Room))
				<p>Tuesday Room: <strong>{{ $classroom->roomsTue->Rl_Room }}</strong></p>
				<p>Tuesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_BTime)) }}</strong></p>
				<p>Tuesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Tue_ETime)) }}</strong></p>
				@endif
				@if(!empty($classroom->Te_Wed_Room))
				<p>Wednesday Room: <strong>{{ $classroom->roomsWed->Rl_Room }}</strong></p>
				<p>Wednesday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_BTime ))}}</strong></p>
				<p>Wednesday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Wed_ETime)) }}</strong></p>
				@endif
				@if(!empty($classroom->Te_Thu_Room))
				<p>Thursday Room: <strong>{{ $classroom->roomsThu->Rl_Room }}</strong></p>
				<p>Thursday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_BTime)) }}</strong></p>
				<p>Thursday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Thu_ETime ))}}</strong></p>
				@endif
				@if(!empty($classroom->Te_Fri_Room))
				<p>Friday Room: <strong>{{ $classroom->roomsFri->Rl_Room }}</strong></p>
				<p>Friday Begin Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_BTime ))}}</strong></p>
				<p>Friday End Time: <strong>{{ date('h:i a', strtotime($classroom->Te_Fri_ETime)) }}</strong></p>
				@endif
				<a href="{{ route('classrooms.edit', $classroom->id) }}" class="btn btn-default editSection">Edit</a>
			</div>
			@endforeach
		</div>
	</div>
</div>