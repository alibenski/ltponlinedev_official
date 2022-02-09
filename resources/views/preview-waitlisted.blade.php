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
	        	<a href="{{route('preview-waitlisted')}}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
	        </div>

	    </form>
	</div>
	</div>
	</div>
</div>
@if(Session::has('Term'))
<div class="row">
	<div class="col-sm-12">
		<h3>Total Number of Waitlisted Students: <span class="label label-primary">{{ count($convocation_waitlist) }}</span></h3>
		<button style="margin-bottom: 10px" class="btn btn-info delete_all"><i class="fa fa-envelope"></i> Email Waitlist</button>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="filtered-table table-responsive">
			<table class="table table-bordered table-striped">
			    <thead>
			        <tr>
						<th>#</th>
						<th><input type="checkbox" id="master"></th>
			            <th>Name</th>
			            <th>Course Waitlisted</th>
			            <th>Email</th>
			            <th>Contact #</th>
			        </tr>
			    </thead>
			    <tbody>
					@foreach($convocation_waitlist as $element)
					<tr id="tr_{{$element->id}}">
						<td>
							<div class="counter"></div>
						</td>
						<td>
							<input type="checkbox" class="sub_chk" data-id="{{ $element->id }}">
                  			<input type="hidden" name="_token" value="{{ Session::token() }}">
						</td>
						<td>
						@if(empty($element->users->name)) None @else {{$element->users->name }} @endif	
						</td>
						<td><a href="{{ route('preview-classrooms', $element->Code) }}" target="_blank" class="small-box-footer" title="Go to the class list">{{$element->courses->Description }}  <i class="fa fa-external-link-square"></i></a></td>
						<td>@if(empty($element->users->email)) None @else {{$element->users->email }} @endif</td>
						<td>@if(empty($element->users->sddextr->PHONE)) None @else {{$element->users->sddextr->PHONE }} @endif</td>
					</tr>
					@endforeach
			    </tbody>
			</table>
		</div>	
	</div>
</div>
@else
@endif

<div id="modalshowform" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Email Waitlisted Students</h4>
            </div>
            <div class="modal-email-waitlist-student">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close Window</button>
            </div>
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

	$('#master').on('click', function(e) {
       if($(this).is(':checked',true))  
       {
          $(".sub_chk").prop('checked', true);  
       } else {  
          $(".sub_chk").prop('checked',false);  
       }  
    });

	$('.delete_all').on('click', function(e) {

          let allVals = [];  
          $(".sub_chk:checked").each(function() {  
              allVals.push($(this).attr('data-id'));
          });  

          let join_selected_values = allVals.join(",");

          let token = $("input[name='_token']").val();
          
          if(allVals.length <=0)  
          {  
              alert("Please select at least 1 student.");  
			  
          }  else {  
              $('#modalshowform').modal({backdrop: 'static', keyboard: false});
              $.get("{{ route('waitlist-modal-form') }}", {'ids':join_selected_values,  '_token':token}, function(data) {
				$(".modal-email-waitlist-student").html("");
                $(".modal-email-waitlist-student").html(data);
              });
          }
    });

	$('#modalshowform').on('hide.bs.modal', function () {
		window.location.reload();
	});	

});
</script>
@stop