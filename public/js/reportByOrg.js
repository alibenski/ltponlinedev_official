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
                // { "data": "classrooms.teachers.Tch_Name",
                // 		"defaultContent": "" }, 
                { "data": "courseschedules.prices.price_usd" }, 
                { "data": "courseschedules.courseduration.duration_name_en" }, 
                { "data": "DEPT" },  
                // { "data": "is_self_pay_form" },  
                // { "data": "organizations.MOU" },  
                // { "data": "organizations.sales_order" },  
                // { "data": function ( row, type, val, meta ) {
                // 		if (row.hasOwnProperty('enrolments')) {
                // 			if (row.enrolments.length > 0) {
                // 				return row.enrolments[0].profile;
                // 			}
                // 		} else if (row.hasOwnProperty('placements')) {
                // 			if (row.placements.length > 0) {
                // 				return row.placements[0].profile;
                // 			}
                // 		}
                // 	return "No Profile Set";
                // 	}
                // },
                { "data": "users.indexno" }, 
                { "data": "users.nameLast" }, 
                { "data": "users.nameFirst" }, 
                // { "data": function ( row, type, val, meta ) {
                // 		if (row.users.sddextr.SEX == 'M' || row.users.sddextr.SEX == 'm') {
                // 				return "Male";
                // 		} else if (row.users.sddextr.SEX == 'F' || row.users.sddextr.SEX == 'f') {
                // 				return "Female";
                // 		} else if (row.users.sddextr.SEX == 'O' || row.users.sddextr.SEX == 'o') {
                // 				return "Other";
                // 			}
                // 		return row.users.sddextr.SEX;
                // 	}
                
                // }, 
                { "data": "Result", "className": "result" },
                // { "data": "cancelled_but_not_billed" },
                // { "data": "exclude_from_billing" },
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