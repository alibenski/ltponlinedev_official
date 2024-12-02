/* populate data to dataTables Jquery */

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
        "destroy": true,
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
                { "data": "Term" }, 
                { "data": "languages.name" }, 
                { "data": "courses.Description" }, 
                { "data": "courseschedules.prices.price_usd" }, 
                { "data": "courseschedules.courseduration.duration_name_en" }, 
                { "data": "DEPT" },  
                { "data": "users.profile" }, 
                { "data": "users.indexno" }, 
                { "data": "users.nameLast" }, 
                { "data": "users.nameFirst" }, 
                { "data": "users.email" }, 
                { "data": "Result", "className": "result" },
                { "data": function ( row, type, val, meta ) {
                        if (row.attendances != null) {
                            return row.attendances.availability[0].P;
                        } else {

                            return 'No Attendance Set';
                        }
                    } 
                },
                { "data": function ( row, type, val, meta ) {
                        if (row.attendances != null) {
                            return row.attendances.availability[0].E;
                        } else {

                            return 'No Attendance Set';
                        }
                    } 
                },
                { "data": function ( row, type, val, meta ) {
                        if (row.attendances != null) {
                            return row.attendances.availability[0].A;
                        } else {

                            return 'No Attendance Set';
                        }
                    } 
                },
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