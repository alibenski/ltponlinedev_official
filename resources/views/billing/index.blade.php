@extends('admin.no_sidebar_admin')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')

<h2 class="text-center"><i class="fa fa-money"></i> Billing Section <i class="fa fa-money"></i></h2>

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
                {{-- <button type="submit" class="btn btn-success filter-submit-btn">Submit</button> --}}
                <button type="button" class="btn btn-success filter-submit-btn">Submit</button>
        <!-- submit button included admin.partials._filterIndex view -->
        	<a href="{{ route('billing-index') }}" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
        </div>

    </form>
	</div>
</div>

<div class="billing-section">
	<table id="sampol" class="table table-hover">
		<thead>
			<tr>
				<th>id</th>
				<th>INEDXID</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td></td>
			</tr>
		</tbody>
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

	$('.filter-submit-btn').on('click', function() {
		var DEPT = $("select[name='DEPT']").val();
		console.log(DEPT)
		$.ajax({
			url: "{{ route('ajax-billing-table') }}",
			type: 'GET',
			// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
			data: {DEPT:DEPT},
		})
		.done(function(data) {
			console.log(data)
			// $(".billing-section").html(data);
   //          $(".billing-section").html(data.options);

   			var dataReturned = data;
				$("#sampol").dataTable({
					data: dataReturned,
				    columns: [  
				    			{ data: "id" },        
				    			{ data: "INDEXID" } 
				    		]
				});


		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {

		});
		
	});

});
</script>

@stop