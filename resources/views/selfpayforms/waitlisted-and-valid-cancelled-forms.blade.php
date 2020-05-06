@extends('admin.no_sidebar_admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
    </style>
@stop

@section('content')

<h2 class="text-center" style="text-align: center; font-family: Roboto">Payment-Based Forms </h2>
<h2 class="text-center" style="text-align: center; font-family: Roboto">Potential Reimbursement/Carry-over</h2>

<div class="row">
	<div class="col-md-4">
	@include('admin.partials._termSessionMsg')
	</div>
    
    <div class="col-md-4">
        <div class="alert alert-info">
            <h4><i class="icon fa fa-info-circle fa-2x"></i>For Your Information</h4>
            <p>
                View of self-paying students who may use their payment to the next term or ask for a reimbursement.
            </p>
        </div>
    </div>
    
	<div class="col-md-4">
	@include('admin.partials._dropdownSetSessionTerm')
	</div>
</div>

<input type="hidden" name="_token" value="{{ Session::token() }}">

<div class="row">
    <h3 style="text-align: center; font-family: Roboto">Waitlisted</h3>
    <table id="sampol" class="table display width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</tfoot>
	</table>
</div>

<div class="row">
    <h3 style="text-align: center; font-family: Roboto">Cancelled Imported</h3>
    <table id="sampol1" class="table display width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</tfoot>
	</table>
</div>

<div class="row">
    <h3 style="text-align: center; font-family: Roboto">Cancelled Enrolment</h3>
    <table id="sampol2" class="table display width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</tfoot>
	</table>
</div>

<div class="row">
    <h3 style="text-align: center; font-family: Roboto">Cancelled Placement</h3>
    <table id="sampol3" class="table display width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Term</th>
				<th>Name</th>
				<th>Organization</th>
				<th>Language</th>
				<th>Description</th>
				<th>Cancel Date</th>
			</tr>
		</tfoot>
	</table>
</div>
@stop

@section('java_script')

    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            const token = $("input[name='_token']").val();
            let term = '{{Session::get('Term')}}';
            $('.select2-basic-single').select2({
                placeholder: "Select Term",
            });
            
            if (term) {
                $.ajax({
                    url: '{{ route('waitlisted-and-valid-cancelled-forms') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {_token:token, term:term},
                })
                .then(function(data) {
                    console.log(data)
                    let pashRecordsWaitlisted = data['pashRecordsWaitlisted'];
                    let pashRecordValidCancelled = data['pashRecordValidCancelled'];
                    let regularRecords = data['regularRecords'];
                    let placementRecords = data['placementRecords'];
                    assignToSampol(pashRecordsWaitlisted);
                    assignToSampol1(pashRecordValidCancelled);
                    assignToSampol2(regularRecords);
                    assignToSampol3(placementRecords);
                })
                .fail(function() {
                    console.log(data);
                });
            }

            function assignToSampol(data) {
                let table = $('#sampol').DataTable({
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
                    "aaData": data,
                    "columns": [
                        {
                            "data": "id",
                            // "className": "record_id",
                            // "defaultContent": '<button class="btn btn-sm btn-danger btn-exclude">Action</button>'
                        },
                        { "data": "Term" }, 
                        { "data": "users.name" }, 
                        { "data": "DEPT" }, 
                        { "data": "languages.name" }, 
                        { "data": "courses.Description" },
                        { "data": "deleted_at" }
                    ],
                    // "createdRow": function( row, data, dataIndex ) {
                    //     $(row).find("td.record_id").attr('id', data['id']);

                    //     if ( data['Result'] == 'P') {
                    //     $(row).addClass( 'pass' );
                    //     $(row).find("td.result").text('PASS');
                    //     }

                    //     if ( data['Result'] == 'F') {
                    //     $(row).addClass( 'label-danger' );
                    //     $(row).find("td.result").text('Fail');
                    //     }

                    //     if ( data['Result'] == 'I') {
                    //     $(row).addClass( 'label-warning' );
                    //     $(row).find("td.result").text('Incomplete');
                    //     }

                    //     if ( data['deleted_at'] !== null) {
                    //     $(row).addClass( 'bg-navy' );
                    //     $(row).find("td.result").text('Late Cancellation');
                    //     }
                    // }
                })
            }

            function assignToSampol1(data) {
                let table = $('#sampol1').DataTable({
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
                    "aaData": data,
                    "columns": [
                        {
                            "data": "id",
                            // "className": "record_id",
                            // "defaultContent": '<button class="btn btn-sm btn-danger btn-exclude">Action</button>'
                        },
                        { "data": "Term" }, 
                        { "data": "users.name" }, 
                        { "data": "DEPT" }, 
                        { "data": "languages.name" }, 
                        { "data": "courses.Description" },
                        { "data": "deleted_at" }
                    ],
                    // "createdRow": function( row, data, dataIndex ) {
                    //     $(row).find("td.record_id").attr('id', data['id']);

                    //     if ( data['Result'] == 'P') {
                    //     $(row).addClass( 'pass' );
                    //     $(row).find("td.result").text('PASS');
                    //     }

                    //     if ( data['Result'] == 'F') {
                    //     $(row).addClass( 'label-danger' );
                    //     $(row).find("td.result").text('Fail');
                    //     }

                    //     if ( data['Result'] == 'I') {
                    //     $(row).addClass( 'label-warning' );
                    //     $(row).find("td.result").text('Incomplete');
                    //     }

                    //     if ( data['deleted_at'] !== null) {
                    //     $(row).addClass( 'bg-navy' );
                    //     $(row).find("td.result").text('Late Cancellation');
                    //     }
                    // }
                })
            }

            function assignToSampol2(data) {
                let table = $('#sampol2').DataTable({
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
                    "aaData": data,
                    "columns": [
                        {
                            "data": "id",
                            // "className": "record_id",
                            // "defaultContent": '<button class="btn btn-sm btn-danger btn-exclude">Action</button>'
                        },
                        { "data": "Term" }, 
                        { "data": "users.name" }, 
                        { "data": "DEPT" }, 
                        { "data": "languages.name" }, 
                        { "data": "courses.Description" },
                        { "data": "deleted_at" }
                    ],
                    // "createdRow": function( row, data, dataIndex ) {
                    //     $(row).find("td.record_id").attr('id', data['id']);

                    //     if ( data['Result'] == 'P') {
                    //     $(row).addClass( 'pass' );
                    //     $(row).find("td.result").text('PASS');
                    //     }

                    //     if ( data['Result'] == 'F') {
                    //     $(row).addClass( 'label-danger' );
                    //     $(row).find("td.result").text('Fail');
                    //     }

                    //     if ( data['Result'] == 'I') {
                    //     $(row).addClass( 'label-warning' );
                    //     $(row).find("td.result").text('Incomplete');
                    //     }

                    //     if ( data['deleted_at'] !== null) {
                    //     $(row).addClass( 'bg-navy' );
                    //     $(row).find("td.result").text('Late Cancellation');
                    //     }
                    // }
                })
            }

            function assignToSampol3(data) {
                let table = $('#sampol3').DataTable({
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
                    "aaData": data,
                    "columns": [
                        {
                            "data": "id",
                            // "className": "record_id",
                            // "defaultContent": '<button class="btn btn-sm btn-danger btn-exclude">Action</button>'
                        },
                        { "data": "Term" }, 
                        { "data": "users.name" }, 
                        { "data": "DEPT" }, 
                        { "data": "languages.name" }, 
                        { 
                            "data": "courses.Description",
                            "defaultContent": "Not assigned"
                        },
                        { "data": "deleted_at" }
                    ],
                    // "createdRow": function( row, data, dataIndex ) {
                    //     $(row).find("td.record_id").attr('id', data['id']);

                    //     if ( data['Result'] == 'P') {
                    //     $(row).addClass( 'pass' );
                    //     $(row).find("td.result").text('PASS');
                    //     }

                    //     if ( data['Result'] == 'F') {
                    //     $(row).addClass( 'label-danger' );
                    //     $(row).find("td.result").text('Fail');
                    //     }

                    //     if ( data['Result'] == 'I') {
                    //     $(row).addClass( 'label-warning' );
                    //     $(row).find("td.result").text('Incomplete');
                    //     }

                    //     if ( data['deleted_at'] !== null) {
                    //     $(row).addClass( 'bg-navy' );
                    //     $(row).find("td.result").text('Late Cancellation');
                    //     }
                    // }
                })
            }
        });
    </script>

@stop