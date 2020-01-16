@extends('admin.no_sidebar_admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
    </style>
@stop

@section('content')

<section id="filter">
	<div class="row">
		<div class="form-group">
	      <label for="organization" class="col-md-12 control-label">Organization Select:</label>
	      <div class="form-group col-sm-12">
	        <div class="dropdown">
	          <select id="organization" name="organization" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required" autocomplete="off">
	            @foreach($orgs as $org)
	                <option></option>
	                <option value="{{$org->OrgCode}}">{{$org['Org Name']}} - {{ $org['Org Full Name'] }}</option>
	            @endforeach
	          </select>
	        </div>
	      </div>
	    </div>

		<div class="col-md-12">
			<div class="form-group col-md-4">
				<div class="input-group"> 
			      <span class="input-group-addon">       
			        <input type="radio" name="decision" value="1" class="decision">                 
			      </span>
			        <label type="text" class="form-control">
			        	Year
			      </label>

			      <span class="input-group-addon">       
			        <input type="radio" name="decision" value="0" class="decision">                 
			      </span>
			        <label type="text" class="form-control">
			        	Term
			      </label>
			    </div>
			</div>
		</div>

		<div class="filter-by-year form-group hidden">
	      <label for="year" class="col-md-12 control-label">Year Select:</label>
	      <div class="form-group col-sm-12">
	        <div class="dropdown">
	          <select id="year" name="year" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required" autocomplete="off">
	            @foreach($years as $value)
	                <option></option>
	                <option value="{{$value}}">{{$value}}</option>
	            @endforeach
	          </select>
	        </div>
	      </div>
	    </div>

	    <div class="filter-by-term form-group hidden">
	      <label for="term" class="col-md-12 control-label">Term Select:</label>
	      <div class="form-group col-sm-12">
	        <div class="dropdown">
	          <select id="term" name="term" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required" autocomplete="off">
	            @foreach($terms as $term)
	                <option></option>
	                <option value="{{$term->Term_Code}}">{{$term->Term_Code}} - {{$term->Comments}} - {{$term->Term_Name}}</option>
	            @endforeach
	          </select>
	        </div>
	      </div>
	    </div>

		<div class="form-group">
	      <label for="language" class="col-md-12 control-label">Language Select:</label>
	      <div class="form-group col-sm-12">
	        <div class="dropdown">
	          <select id="language" name="language" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required" autocomplete="off">
	            @foreach($languages as $id => $name)
	                <option></option>
	                <option value="{{ $id }}">{{$name}}</option>
	            @endforeach
	          </select>
	        </div>
	      </div>
	    </div>
	</div> {{-- end filter div --}}
	<div class="row">
		<button type="button" class="btn btn-success submit-filter">Submit</button>
	</div>
</section>

<div class="reports-section">
	{{-- <div class="preloader2"><p><strong>Please wait... Fetching data from the database...</strong></p></div> --}}
	<table id="sampol" class="table table-striped no-wrap" width="100%">
		<thead>
			<tr>
				{{-- <th>Operation</th> --}}
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				{{-- <th>Price USD</th> --}}
				{{-- <th>Duration</th> --}}
				<th>Organization</th>
				<th>Name</th>
				{{-- <th>RESULT</th> --}}
				{{-- <th>Cancel Date</th> --}}
			</tr>
		</thead>
		<tfoot>
			<tr>
				{{-- <th>Operation</th> --}}
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				{{-- <th>Price USD</th> --}}
				{{-- <th>Duration</th> --}}
				<th>Organization</th>
				<th>Name</th>
				{{-- <th>RESULT</th> --}}
				{{-- <th>Cancel Date</th> --}}
			</tr>
		</tfoot>
	</table>
</div>


@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script><script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script>
	$(document).ready(function() {
		$('.select2-basic-single').select2({
			placeholder: "Select Filter",
	    });

	    $('input.decision').on('click', function(event) {
	    	let decision = event.target.value;
	    	if (decision === '1') {
	    		$('.filter-by-year').removeClass('hidden');
	    		$('.filter-by-term').addClass('hidden');
	    		$('select[name="term"]').prop('selectedIndex',-1);
	    	}
	    	if (decision === '0') {
	    		$('.filter-by-term').removeClass('hidden');
	    		$('.filter-by-year').addClass('hidden');
	    		$('select[name="year"]').prop('selectedIndex',-1);
	    	}

	    });

	    $('button.submit-filter').on('click', function() {
	    	const organizationId = $('select[name="organization"]').children("option:selected").val();
	    	const yearId = $('select[name="year"]').children("option:selected").val();
	    	const termId = $('select[name="term"]').children("option:selected").val();
	    	const languageId = $('select[name="language"]').children("option:selected").val();
	    	
	    	$.ajax({
	    		url: 'get-reports-table',
	    		type: 'GET',
	    		data: {organizationId: organizationId, yearId, termId, languageId},
	    	})
	    	.done(function(data) {
	    		console.log(data);
	    	})
	    	.fail(function() {
	    		console.log("error");
	    	})
	    	.always(function() {
	    		console.log("complete");
	    	});
	    	
	    });

	    function assignToEventsColumns(data) {
		$('#sampol thead tr').clone(true).appendTo( '#sampol thead' );
		    $('#sampol thead tr:eq(1) th').each( function (i) {
		        var title = $(this).text();
			        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
			 
			        $( 'input', this ).on( 'keyup change', function () {
			            if ( table.column(i).search() !== this.value ) {
			                table
			                    .column(i)
			                    .search( this.value )
			                    .draw();
			            }
			        } );
			    } );

		    var table = $('#sampol').DataTable({
		    	// "deferRender": true,
		    	"dom": 'B<"clear">lfrtip',
		    	"buttons": [
				        'copy', 'csv', 'excel', 'pdf'
				    ],
		    	"scrollX": true,
		    	"responsive": false,
		    	"orderCellsTop": true,
		    	"fixedHeader": true,
		    	"pagingType": "full_numbers",
		        "bAutoWidth": false,
		        "aaData": data.data,
		        "columns": [
				        // {
			         //        "data": null,
			         //        "className": "record_id",
			         //        "defaultContent": '<button class="btn btn-sm btn-danger btn-exclude">Exclude</button>'
			         //    },
		        		{ "data": "Term" }, 
		        		{ "data": "languages.name" }, 
		        		{ "data": "courses.Description" }, 
		        		{ "data": "courseschedules.prices.price_usd" }, 
		        		{ "data": "courseschedules.courseduration.duration_name_en" }, 
		        		{ "data": "DEPT" },  
		        		{ "data": "users.name" }, 
		        		{ "data": "Result", "className": "result" },
		        		{ "data": "deleted_at" }
					        ],
				"createdRow": function( row, data, dataIndex ) {
							$(row).find("td.record_id").attr('id', data['id']);

						    if ( data['Result'] == 'P') {
						      $(row).addClass( 'pass' );
						      $(row).find("td.result").text('PASS');
						    }

						    if ( data['Result'] == 'F') {
						      $(row).addClass( 'label-danger' );
						      $(row).find("td.result").text('Fail');
						    }

						    if ( data['Result'] == 'I') {
						      $(row).addClass( 'label-warning' );
						      $(row).find("td.result").text('Incomplete');
						    }

						    if ( data['deleted_at'] !== null) {
						      $(row).addClass( 'bg-navy' );
						      $(row).find("td.result").text('Late Cancellation');
						    }

					    }
		    })
		}
	});
</script>

@stop