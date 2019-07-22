@extends('admin.no_sidebar_admin')

@section('customcss')
	{{-- <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> --}}
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')


@include('admin.partials._termSessionMsg')

<div class="preloader"></div>
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
	    <form method="GET" action="{{ route('query-regular-forms-to-assign',['Orphans' => \Request::input('Orphans'), 'L' => \Request::input('L'), 'Term' => Session::get('Term')]) }}">
			
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
        	<a href="{{route('query-regular-forms-to-assign')}}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
        </div>

	    </form>
	</div>

	@if(!Session::has('Term'))
	<div class="overlay"></div>
	@endif
	
	</div>
	</div>
</div>
@if(Session::has('Term'))
<div class="row">
	<div class="col-sm-12">
		<h3>Total Number of Enrolment Forms <small>(students who are not in a class this term)</small> <span class="label label-primary">{{$count_not_assigned}} out of {{ count($arr3) }}</span></h3>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="filtered-table table-responsive">
			<table id="myTable" class="table table-bordered table-striped">
			    <thead>
			        <tr>
			        	<th>#</th>
                <th>Action</th>
			        	<th>Validated/Assigned Course?</th>
		            <th>Name</th>
		            <th>Course</th>
		            <th>Language</th>
		            <th>Email</th>
		            <th>Contact #</th>
			        </tr>
			    </thead>
			    <tbody>
					@foreach($arr3 as $element)
						<tr id="tr_{{$element->INDEXID}}">
							<td>
              	<div class="counter"></div>
            	</td>
            	<td>
            		<button type="button" class="btn btn-primary btn-sm btn-space assign-course" data-toggle="modal"><i class="fa fa-upload"></i> Assign Course</button>
            		<input type="hidden" name="_token" value="{{ Session::token() }}">
            	</td>
              <td>
                @if(empty($element->updated_by_admin)) <span class="label label-danger margin-label">Not Assigned </span>
                @else
                  @if ($element->modified_by)
                    <span class="label label-success margin-label">Yes by {{$element->modifyUser->name }} </span>
                  @endif
                @endif
              </td>
							<td>
							@if(empty($element->users->name)) None @else {{$element->users->name }} @endif
							<input type="hidden" name="indexid" value="{{$element->INDEXID}}">	
              <input type="hidden" name="L" value="{{$element->L}}">
							<input type="hidden" name="Te_Code_Input" value="{{$element->Te_Code}}">

							</td>
							<td>{{$element->courses->Description }}</td>
							<td>{{$element->languages->name }}</td>
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

<div id="modalshow" class="modal fade" role="dialog">
    <div class="modal-dialog-full">
        <div class="modal-content-full">

            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="text: white;">&times;</button>
                <h4 class="modal-title">Admin Assign Course to Student</h4>
            </div>
            <div class="modal-body-content modal-background">
            </div>
            <div class="modal-footer modal-background">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        
        </div>
    </div>
</div>  
</div>

@stop

@section('java_script')
{{-- <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script>
$(document).ready(function () {
    var counter = 0;
    var promises = [];
    $('.counter').each(function() {
        counter++;
        $(this).attr('id', counter);
        promises.push($('#'+counter).html(counter));
    });   

    $.when.apply($('.counter'), promises).then(function() {
        $(".preloader").fadeOut(600);
    });

    $('.dropdown-toggle').dropdown();
    
    $('#myTable').DataTable({
      "deferRender": true,
    	"paging":   false,
    }); 

    $('.assign-course').click( function() {
      var indexid = $(this).closest("tr").find("input[name='indexid']").val();
      var L = $(this).closest("tr").find("input[name='L']").val();
      var Te_Code_Input = $(this).closest("tr").find("input[name='Te_Code_Input']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
        url: '{{ route('admin-assign-course-view') }}',
        type: 'GET',
        data: {indexid:indexid, L:L,Te_Code:Te_Code_Input,_token: token},
      })
      .done(function(data) {
        console.log("show assign view : success");
        $('.modal-body-content').html(data)
        $('#modalshow').modal('show');
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete show assign view");
      });

    });
});
</script>

<script>  
$('#modalshow').on('click', '.modal-accept-btn',function() {
  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var admin_eform_comment = $("textarea#textarea-"+eform_submit_count+"[name='admin_eform_comment'].course-no-change").val();


  $.ajax({
    url: '{{ route('admin-nothing-to-modify') }}',
    type: 'PUT',
    data: {admin_eform_comment:admin_eform_comment, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
  })
  .done(function(data) {
    console.log(data);
    if (data == 0) {
      alert('Hmm... Nothing to change, nothing to update...');
    }

    var L = $("input[name='L'].modal-input").val();

      $.ajax({
          url: '{{ route('admin-assign-course-view') }}',
          type: 'GET',
          data: {indexid:qry_indexid, L:L, Te_Code:qry_tecode, _token: token},
        })
        .done(function(data) {
          console.log("no change assign view : success");
          $('.modal-body-content').html(data);
        })
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
    
  });
    
});

$('#modalshow').on('click', '.modal-save-btn',function() {
  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var Te_Code = $("select#"+eform_submit_count+"[name='Te_Code'].course_select_no").val();
  var schedule_id = $("select#schedule-"+eform_submit_count+"[name='schedule_id']").val();
  var admin_eform_comment = $("textarea#textarea-"+eform_submit_count+"[name='admin_eform_comment'].course-changed").val();

  $(".overlay").fadeIn('fast'); 

  $.ajax({
    url: '{{ route('admin-save-assigned-course') }}',
    type: 'PUT',
    data: {Te_Code:Te_Code, schedule_id:schedule_id, admin_eform_comment:admin_eform_comment, eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token},
  })
  .done(function(data) {
	    console.log(data);
	    if (data == 0) {
	      alert('Hmm... Nothing to change, nothing to update. Your selected course and schedule have already been assigned to this student.');
        location.reload();
	    }
	    var L = $("input[name='L'].modal-input").val();
      console.log(Te_Code)
	    $.ajax({
	      url: '{{ route('admin-assign-course-view') }}',
	      type: 'GET',
	      data: {indexid:qry_indexid, L:L, Te_Code:Te_Code, _token: token},
	    })
	    .done(function(data) {
	      console.log("refreshing the assign view : success"); 
	      $('.modal-body-content').html(data);    
	    })
	    .always(function() {
	      console.log("complete refresh modal view");
	    });
  })
  .fail(function() {
    	console.log("error");
  })
  .always(function() {
    	console.log("complete save assigned course");
  });
});

$('#modalshow').on('click', 'button.open-course-delete-modal', function() {

  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var method = $("input[name='_method']").val();
  
  $('#modalDeleteEnrolment-'+qry_indexid+'-'+qry_tecode+'-'+qry_term).modal('show');
});

$('#modalshow').on('click', 'button.course-delete', function() {
  var eform_submit_count = $(this).attr('id');
  var qry_tecode = $(this).attr('data-tecode');
  var qry_indexid = $(this).attr('data-indexid');
  var qry_term = $(this).attr('data-term');
  var token = $("input[name='_token']").val();
  var method = $("input[name='_method']").val();
  var admin_eform_cancel_comment = $("textarea#course-delete-textarea-"+eform_submit_count+"[name='admin_eform_cancel_comment'].course-delete-by-admin").val();
  
  var r = confirm("You are about to delete a form. Are you sure?");
  if (r == true) {

    $("button.course-delete").attr('disabled', true);
    $(".overlay").fadeIn('fast'); 

    $.ajax({
      url: '{{ route('delete-no-email') }}',
      type: 'POST',
      data: {
        admin_eform_cancel_comment:admin_eform_cancel_comment, 
        eform_submit_count:eform_submit_count, qry_tecode:qry_tecode, qry_indexid:qry_indexid, qry_term:qry_term, _token:token, _method:method},
    })
    .done(function(data) {
      console.log(data);
      $(".preloader").fadeIn('fast');
      location.reload();
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete delete form");
    });
  }
});

$('#modalshow').on('click', 'button.show-modal-history', function() {
  $('.modal-title-history').text('Past Language Course Enrolment');
  $('#showModalHistory').modal('show');
});
</script>



<script>
$('#modalshow').on('hidden.bs.modal', function (event) {

  console.log(event.target)
  // alert( "This will be displayed only once." );
  //    $( this ).off( event );
  if (event.target.id == 'modalshow') {
    $(".preloader").fadeIn('fast');
    location.reload();
  }

});
</script>
@stop