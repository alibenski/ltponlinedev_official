@extends('admin.no_sidebar_admin')

@section('content')

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
	    <form method="GET" action="{{ route('preview-waitlisted',['L' => \Request::input('L'), 'Term' => Session::get('Term')]) }}">
			
			<div class="form-group col-sm-12">
		      <label for="L" class="control-label"> Language:</label>
		      <div class="col-sm-12">
		        @foreach ($languages as $id => $name)
		        <div class="col-sm-4">
		            <div class="input-group"> 
		              <span class="input-group-addon">       
		                <input type="radio" name="L" value="{{ $id }}" >                 
		              </span>
		                <label type="text" class="form-control">{{ $name }}</label>
		            </div>
		        </div>
		        @endforeach 
		      </div>
		    </div>

	        <div class="form-group">           
	            <button type="submit" class="btn btn-success filter-submit-btn">Submit</button>
	        	<a href="{{route('preview-class-status')}}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
	        </div>

	    </form>
	</div>
	</div>
	</div>
</div>
@if(Session::has('Term'))
<div class="row">

</div>

<div class="row">
	<div class="col-sm-12">
		<div class="filtered-table table-responsive">
			<table class="table table-bordered table-striped">
			    <thead>
			        <tr>
			        	<th>#</th>
			            <th>Class</th>
			            <th>Section</th>
			            <th>Teacher</th>
			            <th>Valid</th>
			            <th>Cancelled</th>
			        </tr>
			    </thead>
			    <tbody>
					@foreach($classrooms as $element)
					@if(count($element->pash) > 0) <!-- only show clasrooms with students -->
					<tr id="tr_{{$element->id}}">
						<td>
                        	<div class="counter"></div>
                      	</td>
						<td>
							@if(empty($element->course->Description)) None @else {{$element->course->Description }} - {{$element->scheduler->name}} @endif	
						</td>
						<td>{{$element->sectionNo }}</td>
						<td>@if(empty($element->teachers->Tch_ID)  || $element->Tch_ID == 'TBD') <span class="label label-danger"> None/Waitlist </span>@else {{$element->teachers->Tch_Name }} @endif</td>
						<td><a href="{{ route('preview-classrooms', $element->cs_unique) }}" target="_blank">{{ count($element->pash) }}</a></td>
						<td>
							@foreach($cancelled as $value_cancelled)
								@if($element->Code == $value_cancelled->CodeClass)
									<i class="fa fa-remove"></i>
								@endif
							@endforeach
						</td>
					</tr>
					@endif
					@endforeach
			    </tbody>
			</table>
		</div>	
	</div>
</div>
@else
@endif

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
@stop