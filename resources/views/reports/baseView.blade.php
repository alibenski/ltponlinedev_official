@extends('admin.admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
		.error { color: red; }
    </style>
@stop

@section('content')

<section id="filter">
	<div class="box box-default">
		<div class="box-header with-border">
			<h4><strong>View Students by Organization</strong></h4>
		</div>
		<div class="box-body">
			<form id="reportForm" method="get" action="" class="col-sm-6">
			<div class="row">
				<div class="form-group">
			      <label for="organization" class="col-sm-12 control-label">Organization Select: (required)</label>
			      <div class="form-group col-sm-12">
			        <div class="dropdown">
			          <select id="organization" name="organization" class="col-sm-8 form-control select2-basic-single" style="width: 100%;" required="required" autocomplete="off">
			            @foreach($orgs as $org)
			                <option value=""></option>
			                <option value="{{$org['Org name']}}">{{$org['Org name']}} - {{ $org['Org Full Name'] }}</option>
			            @endforeach
			          </select>
			        </div>
			      </div>
			    </div>

				<div class="col-sm-12">
					<label for="decision" class="col-sm-12 control-label">Year or Term? (required)</label>
					<div class="form-group col-sm-8">
						<div class="input-group"> 
					      <span class="input-group-addon">       
					        <input type="radio" name="decision" value="1" class="decision validateMe">                 
					      </span>
					        <label type="text" class="form-control">
					        	Year
					      </label>

					      <span class="input-group-addon">       
					        <input type="radio" name="decision" value="0" class="decision validateMe">                 
					      </span>
					        <label type="text" class="form-control">
					        	Term
					      </label>
					    </div>
					</div>
				</div>

				<div class="filter-by-year form-group hidden">
			      <label for="year" class="col-sm-12 control-label">Year Select:</label>
			      <div class="form-group col-sm-12">
			        <div class="dropdown">
			          <select id="year" name="year" class="col-sm-8 form-control select2-basic-single" style="width: 100%;" autocomplete="off">
			            @foreach($years as $value)
			                <option></option>
			                <option value="{{$value}}">{{$value}}</option>
			            @endforeach
			          </select>
			        </div>
			      </div>
			    </div>

			    <div class="filter-by-term form-group hidden">
			      <label for="term" class="col-sm-12 control-label">Term Select:</label>
			      <div class="form-group col-sm-12">
			        <div class="dropdown">
			          <select id="term" name="term" class="col-sm-8 form-control select2-basic-single" style="width: 100%;" autocomplete="off">
			            @foreach($terms as $term)
			                <option></option>
			                <option value="{{$term->Term_Code}}">{{$term->Term_Code}} - {{$term->Comments}} - {{$term->Term_Name}}</option>
			            @endforeach
			          </select>
			        </div>
			      </div>
			    </div>

				<div class="col-sm-12">
					<label for="decision" class="col-sm-12 control-label">Choose language? (optional)</label>
					<div class="form-group col-sm-8">
						<div class="input-group"> 
					      <span class="input-group-addon">       
					        <input type="checkbox" name="all-languages-toggle" class="all-languages-toggle">   
					      </span>
					        <label type="text" class="form-control">
					        	Yes
					      </label>
					    </div>
					</div>
				</div>

				<div class="filter-by-language form-group hidden">
			      <label for="language" class="col-sm-12 control-label">Language Select:</label>
			      <div class="form-group col-sm-12">
			        <div class="dropdown">
			          <select id="language" name="language" class="col-sm-8 form-control select2-basic-single" style="width: 100%;" autocomplete="off">
			            @foreach($languages as $id => $name)
			                <option></option>
			                <option value="{{ $id }}">{{$name}}</option>
			            @endforeach
			          </select>
			        </div>
			      </div>
			    </div>
			</div> {{-- end filter div --}}
			<div class="row col-sm-12">
				<div class="col-sm-12">
					<input type="submit" class="btn btn-success submit-filter" value="Submit">		
				</div>
			</div>
			</form>
		</div>
	</div>
</section>


<div class="reports-section hidden">
	<div class="box box-success">
		<div class="overlay">
			<i class="fa fa-refresh fa-spin"></i>
		</div>
		<div class="box-body">
			<table id="sampol" class="table table-striped no-wrap" width="100%">
				<thead>
					<tr>
						<th>Term</th>
						<th>Language</th>
						<th>Description</th>
						<th>Description (< 2019)</th>
						<th>Organization</th>
						<th>Name</th>
						<th>Cancelled</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Term</th>
						<th>Language</th>
						<th>Description</th>
						<th>Description (< 2019)</th>
						<th>Organization</th>
						<th>Name</th>
						<th>Cancelled</th>
					</tr>
				</tfoot>
			</table>	
		</div>
	</div>
</div>


@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script>
	$(document).ready(function() {
		$('.select2-basic-single').select2({
			placeholder: "Select Filter",
	    });

		var form = $("#reportForm");

		form.validate({
		    rules: {
				'organization': {
	                required: true
	            },
		  	},
		    messages: {
		    	'organization': "Please select an organization.",
		    },
		    errorPlacement: function (error, element) {
		    	console.log(element)
	            error.insertBefore(element.offsetParent("div.input-group"));
	        },
	        errorElement: 'div'
		      });

		$.validator.addClassRules("validateMe", {
		     required: true
		});

	    $('input.decision').on('click', function(event) {
	    	let decision = event.target.value;
	    	if (decision === '1') {
	    		$('.filter-by-year').removeClass('hidden');
	    		$('.filter-by-term').addClass('hidden');
	    		$('select[name="year"]').addClass('validateMe');
	    		$('select[name="term"]').prop('selectedIndex',-1);
	    		$('select[name="term"]').select2({placeholder: "Select Term"});
	    	}
	    	if (decision === '0') {
	    		$('.filter-by-term').removeClass('hidden');
	    		$('.filter-by-year').addClass('hidden');
	    		$('select[name="term"]').addClass('validateMe');
	    		$('select[name="year"]').prop('selectedIndex',-1);
	    		$('select[name="year"]').select2({placeholder: "Select Year"});
	    	}

	    });

	    $('input.all-languages-toggle').on('click', function() {
	    		$('.filter-by-language').toggleClass('hidden');

	    		if (!$(this).is(':checked')) {
	    			$('select[name="language"]').prop('selectedIndex',-1);
	    			$('select[name="language"]').select2({placeholder: "Select Language"});
	    			console.log('reset')
	    		}
	    });

	    $("select").on('change', function() {
	    	$(".reports-section").addClass('hidden');
	    	$(".overlay").removeAttr('style');
	    });

	    $('input.submit-filter').on('click', function(e) {
	    	e.preventDefault();
	    	if (form.valid()) {
	    		getReportsTable();
	    		$(".reports-section").removeClass('hidden');
	    	}	else {
				console.log('no')	    	
	    	}
	    });

	    function getReportsTable() {
	    	const DEPT = $('select[name="organization"]').children("option:selected").val();
	    	const year = $('select[name="year"]').children("option:selected").val();
	    	const Term = $('select[name="term"]').children("option:selected").val();
	    	const L = $('select[name="language"]').children("option:selected").val();
	    	
	    	let promises = [];

	    	promises.push(
		    	$.ajax({
		    		url: 'get-reports-table',
		    		type: 'GET',
		    		data: {DEPT: DEPT, year: year, Term: Term, L: L},
		    	})
		    	.done(function(data) {
		    		console.log(data)
		    		assignToEventsColumns(data);
		    	})
		    	.fail(function(data) {
		    		console.log(data)
		    	})
		    	.always(function() {
		    		console.log("complete");
		    	}));

	    	$.when.apply($.ajax(), promises).then(function() {
		        $(".overlay").fadeOut(600);
		    }); 
	    }

	    function assignToEventsColumns(data) {
		$('#sampol thead tr').clone(true).appendTo( '#sampol thead' );
		    $('#sampol thead tr:eq(1) th').each( function (i) {
		        var title = $(this).text();
			        $(this).html( '<input type="text" placeholder="Search Column" />' );
			 
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
		    	"destroy": true, // destroy the existing table to apply the new options
		    	"responsive": false,
		    	"orderCellsTop": true,
		    	"fixedHeader": true,
		    	"pagingType": "full_numbers",
		        "bAutoWidth": false,
		        "aaData": data.data,
		        "columns": [
		        		{ "data": "Term" }, 
		        		{ "data": "languages.name" }, 
		        		{ "data": "courses.Description",
		        			"defaultContent": "" }, 
		        		{ "data": "courses_old.Description",
		        			"defaultContent": "" }, 
		        		{ "data": "DEPT" },  
		        		{ "data": "users.name",
		        			"defaultContent": ""  }, 
		        		{ "data": "deleted_at" },
					        ],


		    })
		}
	});
</script>

@stop