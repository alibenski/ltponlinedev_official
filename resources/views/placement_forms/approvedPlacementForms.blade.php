Approved: {{ count($placement_forms) }}
<br>
<div class="filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>First Name</th>
	            <th>Last Name</th>
	            <th>Email</th>
	            <th>Organization</th>
	            <th>Language</th>
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
				<td>{{ $form->overall_approval }}</td>
				<td>{{ $form->created_at }}</td>

			</tr>
			@endforeach
	    </tbody>
	</table>
</div>
