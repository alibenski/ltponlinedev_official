@extends('admin.admin')

@section('content')
<div class="row col-sm-12">
	<div class="input-group col-sm-8">
        <form method="GET" action="{{ route('placement-form.index') }}">
		<h4><strong>Filters:</strong></h4>
            <div class="input-group">           
                <div class="input-group-btn">
			        <button name="L" type="submit" class="btn btn-default" value="A">Arabic</button>
			        <button name="L" type="submit" class="btn btn-default" value="C">Chinese</button>
			        <button name="L" type="submit" class="btn btn-default" value="E">English</button>
			        <button name="L" type="submit" class="btn btn-default" value="F">French</button>
			        <button name="L" type="submit" class="btn btn-default" value="R">Russian</button>
			        <button name="L" type="submit" class="btn btn-default" value="S">Spanish</button>
			        <button name="DEPT" type="submit" class="btn btn-default" value="UNOG">UNOG</button>
                	<a href="/admin/placement-form/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
                </div>
                <div class="input-group-btn pull-right">
			        <a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
			        <a href="{{ route('placement-form.index', ['L' => \Request::input('L'), 'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
                </div>

            </div>
        </form>    
    </div>
</div>

{{ $placement_forms->links() }}
<div class="filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Organization</th>
	            <th>Language</th>
	            <th>Schedule</th>
	            <th>Manager Approval</th>
	            <th>HR Approval</th>
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($placement_forms as $form)
			<tr>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>
				@if(empty($form->DEPT)) None @else <a href="{{ route('placement-form.index') }}?DEPT={{$form->DEPT}}"> {{ $form->DEPT }} </a> @endif
				</td>
				<td>{{ $form->L }}</td>
				<td>@if ($form->L === "F") Online from {{ $form->placementSchedule->date_of_plexam }} to {{ $form->placementSchedule->date_of_plexam_end }} @else {{ $form->placementSchedule->date_of_plexam }} @endif</td>
				<td>@if ($form->approval === 1) approved @elseif ($form->approval === 0) disapproved @else pending @endif</td>
				<td>@if ($form->approval_hr === 1) approved @elseif ($form->approval_hr === 0) disapproved @else pending @endif</td>
				<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank">carte attachment</a> @endif
				</td>
				<td>
				@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank">payment attachment</a> @endif
				</td>
				<td>{{ $form->created_at}}</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
	{{ $placement_forms->links() }}
</div>
@stop

@section('java_script')
<script>

</script>
@stop