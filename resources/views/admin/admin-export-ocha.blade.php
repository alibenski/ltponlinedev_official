@extends('admin.no_sidebar_admin')
@section('tabtitle')
    OCHA Data Extract
@stop
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
    <div class="row">
        <h2 class="text-center"> OCHA Export </h2>
        <div class="billing-section">
            <div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <table id="sampol" class="table table-striped no-wrap" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Index Number</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Email</th>
                        <th>Term</th>
                        <th>Language</th>
                        <th>Description</th>
                        <th>Price USD</th>
                        <th>Duration</th>
                        <th>Organization</th>
                        <th>RESULT</th>
                        <th>Cancel Date</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Index Number</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Email</th>
                        <th>Term</th>
                        <th>Language</th>
                        <th>Description</th>
                        <th>Price USD</th>
                        <th>Duration</th>
                        <th>Organization</th>
                        <th>RESULT</th>
                        <th>Cancel Date</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="row">
        <h2 class="text-center"> OCHA Export 2018</h2>
        <div class="billing-section-2">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <table id="sampol-2" class="table table-striped no-wrap" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Index Number</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Email</th>
                        <th>Term</th>
                        <th>Language</th>
                        <th>Description</th>
                        <th>Price USD</th>
                        <th>Duration</th>
                        <th>Organization</th>
                        <th>RESULT</th>
                        <th>Cancel Date</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Index Number</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Email</th>
                        <th>Term</th>
                        <th>Language</th>
                        <th>Description</th>
                        <th>Price USD</th>
                        <th>Duration</th>
                        <th>Organization</th>
                        <th>RESULT</th>
                        <th>Cancel Date</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop

@section('java_script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            var promises = [];
            var token = $("input[name='_token']").val();

            promises.push(
            $.ajax({
                url: "{{ route('admin-extract-data') }}",
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
                    "order": [[ 0, "asc" ]],
                    "columns": [
                            { "data": "id"},
                            { "data": "users.indexno"},
                            { "data": "users.nameLast"},
                            { "data": "users.nameFirst"},
                            { "data": "users.email"},
                            { "data": null,
                            render: function(data, type, row, meta) {
                                return data.terms.Term_Name + ' (' + data.Term + ')';
                            }},
                            { "data": "languages.name" }, 
                            { "data": "courses.Description" }, 
                            { "data": "courseschedules.prices.price_usd" }, 
                            { "data": "courseschedules.courseduration.duration_name_en" }, 
                            { "data": "DEPT" },  
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

            $.ajax({
                url: "{{ route('admin-extract-data-2018') }}",
                type: 'GET',
                dataType: 'json',
                data: {_token:token},
            })
            .then(function(data) {
                console.log(data)
                assignToEventsColumns2(data);
                // console.log(data.data)
                // var data = jQuery.parseJSON(data.data);
                // console.log(data)
            })
            
            function assignToEventsColumns2(data) {
                var table = $('#sampol-2').DataTable({
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
                    "order": [[ 0, "asc" ]],
                    "columns": [
                            { "data": "id"},
                            { "data": "INDEXID"},
                            { "data": "LASTNAME"},
                            { "data": "FIRSTNAME"},
                            { "data": "EMAIL"},
                            { "data": null,
                            render: function(data, type, row, meta) {
                                return data.terms.Term_Name + ' (' + data.Term + ')';
                            }},
                            { "data": "languages.name" }, 
                            { "data": "courses_old.Description" }, 
                            { "data": null,
                            render: function(data, type, row, meta) {
                                return 'N/A';
                            } }, 
                            { "data": null,
                            render: function(data, type, row, meta) {
                                return 'N/A';
                            } }, 
                            { "data": "DEPT" },  
                            { "data": "Result", "className": "result" },
                            { "data": null,
                            render: function(data, type, row, meta) {
                                return 'N/A';
                            } }
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