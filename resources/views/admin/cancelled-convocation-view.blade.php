@extends('admin.no_sidebar_admin')

@section('content')
<div class="row">
	<div class="col-sm-12">
		
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="filtered-table table-responsive">
			<table class="table table-bordered table-striped">
			    <thead>
			        <tr>
			        	<th>#</th>
			            <th>Name</th>
			            <th>Term</th>
			            <th>Course</th>
			            <th>Organization</th>
			            <th>Date/Time Cancelled</th>
			        </tr>
			    </thead>
			    <tbody>
					@foreach($cancelled_convocations as $element)
					<tr id="tr_{{$element->id}}" @if($element->deleted_at > $element->terms->Cancel_Date_Limit) style="background-color: #eed5d2;" @endif>
						<td>
                        	<div class="counter"></div>
                      	</td>
						<td>
							@if(empty($element->users->name)) None @else {{$element->users->name }} @endif	
						</td>
						<td>
							{{ $element->terms->Comments }} {{ date('Y', strtotime($element->terms->Term_Begin)) }} [{{$element->Term}}]
						</td>
						<td>
							{{ $element->DEPT }}
						</td>
						<td>
							<a href="{{ route('preview-classrooms', ['Code' => $element->Code]) }}" target="_blank" class="small-box-footer" title="Go to the class list">{{$element->courses->Description }} <i class="fa fa-external-link-square"></i></a>
						</td>
						<td>
							@if(empty($element->deleted_at)) None @else {{$element->deleted_at }} @endif
						</td>
					</tr>
					@endforeach
			    </tbody>
			</table>
		</div>	
	</div>
</div>


@stop

@section('java_script')
<script>
$(document).ready(function () {
	var counter = 0;
	$('.counter').each(function() {
	    counter++;
	    $(this).attr('id', counter);
	    $('#'+counter).html(counter);
	    // console.log(counter)
	});
});
</script>
<script type="text/javascript">
   // setTimeout(function(){
   //     location.reload();
   // },3000);
</script>
@stop