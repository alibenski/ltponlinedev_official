@extends('admin.no_sidebar_admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<h2 class="text-center"><i class="fa fa-money"></i> Billing Section <i class="fa fa-money"></i></h2>

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
	                <option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
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
	<table id="sampol" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				<th>Price USD</th>
				<th>Duration</th>
				<th>Organization</th>
				<th>Name</th>
				<th>RESULT</th>
				<th>Cancel Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Term</th>
				<th>Language</th>
				<th>Description</th>
				<th>Price USD</th>
				<th>Duration</th>
				<th>Organization</th>
				<th>Name</th>
				<th>RESULT</th>
				<th>Cancel Date</th>
			</tr>
		</tfoot>
	</table>
</div>



@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function() {
	$('.select2-basic-single').select2({
    	placeholder: "Select Filter",
    });

	var token = $("input[name='_token']").val();
	$.ajax({
		url: '{{ route('ajax-billing-table') }}',
		type: 'GET',
		dataType: 'json',
		data: {_token:token},
	})
	.done(function(data) {
		console.log(data)
		assignToEventsColumns(data);
		// console.log(data.data)
		// var data = jQuery.parseJSON(data.data);
		// console.log(data)
	})
	.fail(function() {
		console.log(data);
	});
	
	function assignToEventsColumns(data) {
		$('#sampol thead tr').clone(true).appendTo( '#sampol thead' );
	    $('#sampol thead tr:eq(1) th').each( function (i) {
	        var title = $(this).text();
		        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
		 
		        $( 'input', this ).on( 'keyup change', function () {
		            if ( table.api().column(i).search() !== this.value ) {
		                table
		                	.api()
		                    .column(i)
		                    .search( this.value )
		                    .draw();
		            }
		        } );
		    } );

	    var table = $('#sampol').dataTable({
	    	// "deferRender": true,
	    	"orderCellsTop": true,
	    	"fixedHeader": true,
	    	"pagingType": "full_numbers",
	        "bAutoWidth": false,
	        "aaData": data.data,
	        "columns": [
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