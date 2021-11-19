@extends('shared_template')

@section('customcss')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
  <style>
    .close {
        color: #fff; 
        opacity: 1;
    }
  </style>
@stop

@section('content')

<div class="row">
  <div class="col-sm-12">
    <h3 class="text-center"><strong>{{ strtoupper('Manage All Unassigned Enrolment Forms') }}</strong></h3>
  </div>
</div>

@include('admin.partials._termSessionMsg')

<div class="preloader">
  <h4 class="text-center"><strong>Please wait... Fetching data from the database... </strong></h4>
</div>
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
	    <form method="GET" action="{{ route('query-orphan-forms-to-assign',['L' => \Request::input('L'), 'Term' => Session::get('Term')]) }}">
			<input type="hidden" name="_token" value="{{ Session::token() }}">
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
        	<a href="{{route('query-orphan-forms-to-assign')}}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
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
<input type="hidden" name="term" value="{{ Session::get('Term') }}">
@if (!empty($arr3)) 

<div class="row">
	<div class="col-sm-8">
    <h3>Total @if(Request::filled('L'))
          <input type="hidden" name="selectedLanguage" value="{{ Request::input('L') }}">
          <strong>  
          @if(Request::input('L') == 'A') <span>Arabic</span>
          @elseif(Request::input('L') == 'C') <span>Chinese</span>
          @elseif(Request::input('L') == 'E') <span>English</span>
          @elseif(Request::input('L') == 'F') <span>French</span>
          @elseif(Request::input('L') == 'R') <span>Russian</span>
          @elseif(Request::input('L') == 'S') <span>Spanish</span>
          @endif
          </strong>
        @endif Enrolment Forms: <span class="label label-default">{{ $assigned_forms_count }} / {{count($arr3)}}</span> </h3>
	</div>
  <div class="alert alert-warning col-sm-4 pull-right">
    <h4><i class="icon fa fa-info-circle "></i>Important Note</h4>
    <p>You are viewing all <strong><u>unassigned</u></strong> regular enrolment forms which have been fully approved and validated by HR Focal Points and the Language Secretariat. This view includes students who are in a class and who are <strong><u>not</u></strong> in a class this term.</p>
  </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="filtered-table table-responsive">
			<table id="sampol" class="table display">
			    <thead>
			        <tr>
			        	<th></th>
                <th>Action</th>
			        	<th>Validated/Assigned Course?</th>
		            <th>Name</th>
                <th>Current Class(es)</th>
                <th>Current Teacher(s)</th>
		            <th>Enrolled to Course</th>
		            <th>Language</th>
		            <th>Email</th>
		            <th>Contact #</th>
			        </tr>
			    </thead>
          <tfoot>
			        <tr>
			        	<th></th>
                <th>Action</th>
			        	<th>Validated/Assigned Course?</th>
		            <th>Name</th>
                <th>Current Class(es)</th>
                <th>Current Teacher(s)</th>
		            <th>Enrolled to Course</th>
		            <th>Language</th>
		            <th>Email</th>
		            <th>Contact #</th>
			        </tr>
			    </tfoot>
      </table>
		</div>	
	</div>
</div>

@endif
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
              <button type="button" class="btn btn-default" data-dismiss="modal">Close this window</button>
            </div>
        
        </div>
    </div>
</div>  
</div>

@stop

@section('java_script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script>
  $(document).ready(function() {
    var eform_submit_count = [];
    var token = $("input[name='_token']").val();
    var term = $("input[name='term']").val();
    var L = $("input[name='selectedLanguage']").val();
    var promises = [];

    console.log(term,L)

    $('.dropdown-toggle').dropdown();
    if (L == undefined) {
      $(".preloader").fadeOut(800);
    }
    $("button.filter-submit-btn").click(function() {
      $(".preloader").fadeIn('fast');
    });

    if (term && L) {

      promises.push(
      $.ajax({
        url: '{{ route('ajax-preview-get-student-current-class') }}',
        type: 'POST',
        data: {L:L,term:term,_token:token},
      })
      .then(function(data) {
        console.log(data)
        console.time('jquery');
        assignToEventsColumns(data)
        console.timeEnd('jquery');
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      }));

      function assignToEventsColumns(data) {
          var table = $('#sampol').DataTable({
            "dom": 'B<"clear">lfrtip',
            "buttons": [
                  'copy', 'csv', 'excel', 'pdf'
              ],
            "scrollX": true,
            "responsive": false,
            "orderCellsTop": true,
            "fixedHeader": false,
            "paging": false,
            "oLanguage": {
              "sSearch": "Search Student:"
              },
            "bAutoWidth": false,
            "order": [[ 2, "asc" ], [ 4, "asc" ]],
            "aaData": data,
            "columns": [
                {
                  "data": null,
                  "defaultContent": ''
                },
                {
                  "data": null,
                  "className": "assign-course",
                  "defaultContent": '<button type="button" class="btn btn-primary btn-sm btn-space assign-course" data-toggle="modal"><i class="fa fa-upload"></i> Assign Course</button>'
                },
                { 
                  "data": "updated_by_admin",
                  "className": "updated-by-admin"
                }, 
                { 
                  "data": "users.name",
                  "className": "self-paying record_id",
                  render: function (dataField) { return '<h4 class="user-name"><strong>'+dataField+'</strong></h4>'; }
                }, 
                // { "data": "pash.courses.Description",
                //   "defaultContent": ''
                // }, 
                { "data": "pash_many",
                  "defaultContent": '',
                  "render": function (data) {
                    var val = [];
                    $.each(data, function (i,v) {
                      let css = "bg-success";
                      if (i % 2 == 0) {
                        css = "text-primary";
                      }
                      val.push('<div class='+css+' style="padding:5px"><strong>'+v['courses']['Description']+'</strong></div><br>');
                    })
                    return val.join("");
                  }
                }, 
                { "data": "pash_many",
                  "defaultContent": '',
                  "render": function (data) {
                    var val = [];
                    $.each(data, function (i,v) {
                      let css = "bg-success";
                      if (i % 2 == 0) {
                        css = "text-primary";
                      }
                      val.push('<div class='+css+' style="padding:5px">'+v['classrooms']['teachers']['Tch_Name']+'</div><br>');
                      
                    })
                    return val.join("");
                  }
                }, 
                { "data": "courses.Description" }, 
                { "data": "languages.name" }, 
                { "data": "users.email" }, 
                { "data": "users.sddextr.PHONE" }
                  ],
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
                } ],
            "createdRow": function( row, data, dataIndex ) {
                  $(row).find("td.record_id").attr('id', dataIndex);

                  $(row).find("td.record_id").append('<input type="hidden" name="eform_submit_count" value="'+data['eform_submit_count']+'">                <input type="hidden" name="indexid" value="'+data['INDEXID']+'">	                <input type="hidden" name="L" value="'+data['L']+'">                <input type="hidden" name="term" value="'+data['Term']+'">  							<input type="hidden" name="Te_Code_Input" value="'+data['Te_Code']+'">              ');

                  if ( data['updated_by_admin'] != 1) {
                    $(row).find("td.updated-by-admin").html('<span class="label label-danger margin-label">Not Assigned</span>');
                  }

                  if ( data['updated_by_admin'] === 0) {
                    $(row).find("td.updated-by-admin").html('<span class="label label-warning margin-label">Verified and Not Assigned by '+data['modify_user']['name']+'</span>');
                  }

                  if ( data['updated_by_admin'] === 1) {
                    $(row).find("td.updated-by-admin").html('<span class="label label-success margin-label">Yes by '+data['modify_user']['name']+'</span>');
                  }

                  if ( data['selfpay_approval'] == 1) {
                    $(row).find("td.self-paying").append(' <span class="label label-success margin-label"><i class="fa fa-usd" title="Self-paying Student"></i>Self-paying Student</span>');
                  }

                  // if (data['pash'] != null) {
                  //   $(row).find("td.record_id").append(' <p><small><b>Current Class:</b></small></p> <p><span id="xcourse" class="label label-info margin-label" data-course="'+data['pash']['courses']['Description']+'">'+data['pash']['courses']['Description']+'</span></p><p><span class="label label-info margin-label">'+data['pash']['classrooms']['teachers']['Tch_Name']+'</span></p>');
                  // }

                }
          });

          table.on( 'order.dt search.dt', function () {
              table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                  cell.innerHTML = i+1;
              } );
          } ).draw();

      }

      $.when.apply($.ajax(), promises).then(function() {
          $(".preloader").fadeOut(800);
      });
    }


  });
</script>
<script>
  $(document).ready(function () {
      $('#sampol').on('click', 'button.assign-course', function() {
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

  $('#modalshow').on('click', '.modal-not-assign-btn',function() {
    var eform_submit_count = $(this).attr('id');
    var qry_tecode = $(this).attr('data-tecode');
    var qry_indexid = $(this).attr('data-indexid');
    var qry_term = $(this).attr('data-term');
    var token = $("input[name='_token']").val();
    var admin_eform_comment = $("textarea#textarea-"+eform_submit_count+"[name='admin_eform_comment'].course-no-change").val();

    $.ajax({
      url: '{{ route('admin-verify-and-not-assign') }}',
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