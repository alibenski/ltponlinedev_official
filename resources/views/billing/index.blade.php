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

<h2 class="text-center"><i class="fa fa-money"></i> Billing Section <i class="fa fa-money"></i></h2>
<h4 class="text-center">Management of Billing Information for Organizations</h4>

@include('admin.partials._termSessionMsg')

{{-- <div class="row">
    <div class="col-sm-12">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Filters:</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    <div class="box-body">
    <form method="GET" action="{{ route('billing-index',['DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term')]) }}">
		
	    <div class="form-group">           
	      <label for="organization" class="col-md-12 control-label"> Organization:</label>
	      <div class="form-group col-sm-12">
	        <div class="dropdown">
	          <select id="input" name="DEPT" class="col-md-10 form-control select2-basic-single" style="width: 100%;">
	            @if(!empty($org))
	              @foreach($org as $value)
	                <option></option>
	                <option value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{ $value['Org Full Name'] }}</option>
	              @endforeach
	            @endif
	          </select>
	        </div>
	      </div>
	    </div>

		<div class="form-group">           
                <button type="submit" class="btn btn-success filter-submit-btn">Submit</button>
                <button type="button" class="btn btn-success filter-submit-btn">Submit</button>
        <!-- submit button included admin.partials._filterIndex view -->
        	<a href="{{ route('billing-index') }}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
        	<input type="hidden" name="_token" value="{{ Session::token() }}">
        </div>

    </form>
	</div>
</div> --}}

<div class="billing-section">
	<div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
	<input type="hidden" name="_token" value="{{ Session::token() }}">
	<table id="sampol" class="table table-striped no-wrap" width="100%">
		<thead>
			<tr>
				<th>Operation</th>
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				<th>Price USD</th>
				<th>Duration</th>
				<th>Organization</th>
				<th>Index No.</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email</th>
				<th>RESULT</th>
				<th>Cancel Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Operation</th>
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				<th>Price USD</th>
				<th>Duration</th>
				<th>Organization</th>
				<th>Index No.</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email</th>
				<th>RESULT</th>
				<th>Cancel Date</th>
			</tr>
		</tfoot>
	</table>
</div>		



@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script>
{{-- <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

<script>
$(document).ready(function() {
	$('.select2-basic-single').select2({
    	placeholder: "Select Filter",
    });

	var promises = [];
	var token = $("input[name='_token']").val();

	promises.push(
	$.ajax({
		url: '{{ route('ajax-billing-table') }}',
		type: 'GET',
		dataType: 'json',
		data: {_token:token},
	})
	.then(function(data) {
		console.log(data)
		assignToEventsColumns(data);
		// console.log(data.data)
		// var data = jQuery.parseJSON(data.data);
		// console.log(data)
	})
	.fail(function() {
		console.log(data);
	}));
	
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
			        {
		                "data": null,
		                "className": "record_id",
		                "defaultContent": '<button class="btn btn-sm btn-danger btn-exclude">Exclude</button>'
		            },
	        		{ "data": "Term" }, 
	        		{ "data": "languages.name" }, 
	        		{ "data": "courses.Description" }, 
	        		{ "data": "courseschedules.prices.price_usd" }, 
	        		{ "data": "courseschedules.courseduration.duration_name_en" }, 
	        		{ "data": "DEPT" },  
	        		{ "data": "users.indexno" }, 
	        		{ "data": "users.nameLast" }, 
	        		{ "data": "users.nameFirst" }, 
	        		{ "data": "users.email" }, 
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

	$.when.apply($.ajax(), promises).then(function() {
        $(".preloader2").fadeOut(600);
    }); 


	$('#sampol').on('click', 'button.btn-exclude', function() {
	    var id = $(this).closest('td.record_id').attr('id');
	    var tableRow = ($(this).closest('tr'));

		var r = confirm("You are about to exclude this from the billing report. Are you sure?");
		if (r == true) {
	    	$.ajax({
	    		url: "{{ route('ajax-exclude-from-billing') }}",
	    		type: 'PUT',
	    		data: {id: id, _token:token},
	    	})
	    	.done(function(data) {
	    		console.log(data);
		   		tableRow.fadeOut('slow', function() {
		   			$(this).remove();
		   		});
				
	    	})
	    	.fail(function() {
	    		console.log("error");
	    	})
	    	.always(function() {
	    		console.log("complete");
	    	}); 	
		}
	});
});
</script>

@stop