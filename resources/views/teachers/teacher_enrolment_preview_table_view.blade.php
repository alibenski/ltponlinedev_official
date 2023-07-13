@extends('shared_template')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/datatables.min.css" rel="stylesheet"/>
@stop

@section('content')
<div class="preloader2"><h3 class="text-center"><strong>Please wait... Fetching data from the database...</strong></h3></div>
<div class="filtered-table table-responsive">
	<input type="hidden" name="_token" value="{{ Session::token() }}">
	<input type="hidden" name="Te_Code" value={{ $Te_Code }}>
    <table id="sampol" class="table table-striped table-bordered no-wrap" width="100%">
        <thead>
            <tr>
                <th>Validated/Assigned Course?</th>
                <th>Validated/Assigned by</th>
                <th>Assigned Course</th>
                <th>Assigned Schedule</th>
                <th>Re-Enrolment?</th>
                <th>Placement Form?</th>
                <th>Not in a class?</th>
                <th>Waitlisted?</th>
                <th>Within 2 terms?</th>
                <th>Index Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
                <th>Course</th>
                <th>Wishlist Schedule</th>
                <th>Availability Day(s)</th>
                <th>Availability Time(s)</th>
                <th>Availability Delivery Mode(s)</th>
                <th>Flexible Day?</th>
                <th>Flexible Time?</th>
                <th>Flexible Format?</th>
                <th>Organization</th>
                <th>Student Cancelled?</th>
                <th>HR Approval</th>
                <th>Payment Status</th>
                <th>Student Comment</th>
                <th>Admin Regular Form Comment (from Assign Course)</th>
                <th>Admin Placement Form Comment (from Assign Course)</th>
                <th>Time Stamp</th>
                <th>Cancel Date/Time Stamp</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Validated/Assigned Course?</th>
                <th>Validated/Assigned by</th>
                <th>Assigned Course</th>
                <th>Assigned Schedule</th>
                <th>Re-Enrolment?</th>
                <th>Placement Form?</th>
                <th>Not in a class?</th>
                <th>Waitlisted?</th>
                <th>Within 2 terms?</th>
                <th>Index Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
                <th>Course</th>
                <th>Wishlist Schedule</th>
                <th>Availability Day(s)</th>
                <th>Availability Time(s)</th>
                <th>Availability Delivery Mode(s)</th>
                <th>Flexible Day?</th>
                <th>Flexible Time?</th>
                <th>Flexible Format?</th>
                <th>Organization</th>
                <th>Student Cancelled?</th>
                <th>HR Approval</th>
                <th>Payment Status</th>
                <th>Student Comment</th>
                <th>Admin Regular Form Comment (from Assign Course)</th>
                <th>Admin Placement Form Comment (from Assign Course)</th>
                <th>Time Stamp</th>
                <th>Cancel Date/Time Stamp</th>
            </tr>
        </tfoot>
    </table>
</div>
@stop

@section('java_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<script>
$(document).ready(function() {
	var promises = [];
	var Te_Code = $("input[name='Te_Code']").val();
	var token = $("input[name='_token']").val();

	promises.push(
	$.ajax({
		url: '{{ route('teacher-enrolment-preview-table') }}',
		type: 'GET',
		dataType: 'json',
		data: {Te_Code:Te_Code, _token:token},
	})
	.then(function(data) {
		console.log(data)
		assignToEventsColumns(data);
	})
	.fail(function(data) {
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
	    	"deferRender": false,
	    	"dom": 'B<"clear">lfrtip',
	    	"buttons": [
					'copy', 'csv', 'excel', 'pdf'
				],
			"pageLength": 200,
	    	"scrollX": true,
	    	"responsive": false,
	    	"orderCellsTop": true,
	    	"fixedHeader": false,
	    	"pagingType": "full_numbers",
	        "bAutoWidth": false,
	        "aaData": data.data,
			"order": [[28, 'asc']],
	        "columns": [
			        {
		                "data": "assign_status",
		                "className": "record_id updated_by_admin",
		            },
					{ 	
						"data": "assigned_by",
						"className": "modifyUser",
					}, 
					{ 	
						"data": "assigned_course",
						"className": "assigned_course",
		                
					},
					{ 	
						"data": "assigned_schedule",
						"className": "assigned_schedule",
		                
					},
					{ 	
						"data": "re_enrolment",
						"className": "re_enrolment",
					},
					{ 	
						"data": "placement_form",
						"className": "placement_form",
					},
					{ 	
						"data": "not_in_a_class",
						"className": "not_in_a_class",
					},
					{ 	
						"data": "waitlisted",
						"className": "waitlisted",
					},
					{ 	
						"data": "within_2_terms",
						"className": "within_2_terms",
					},
	        		{ 
						"data": "INDEXID", 
						"className": "index_id" 
					}, 
	        		{ "data": "name" }, 
	        		{ "data": "email" }, 
	        		{ 
						"data": "PHONE" ,
						"className": "PHONE",
					}, 
	        		{ "data": "courses_Description"}, 
	        		{ 
						"data": null,
						"defaultContent": 'wishlist schedule'
					}, 
	        		{ 
						"data": "dayInput", 
						"className": "dayInput", 
					}, 
	        		{ 
						"data": "timeInput", 
						"className": "timeInput", 
					}, 
	        		{ 
						"data": "deliveryMode", 
						"className": "deliveryMode", 
					}, 
	        		{ 
						"data": "flexibleDay", 
						"className": "flexibleDay",  
					}, 
	        		{ 
						"data": "flexibleTime", 
						"className": "flexibleTime",  
					}, 
	        		{ 
						"data": "flexibleFormat", 
						"className": "flexibleFormat",  
					}, 
	        		{ "data": "DEPT" },  
	        		{ 
						"data": "cancelled_by_student", 
						"className": "cancelled_by_student" }, 
	        		{ 
						"data": "hr_approval", 
						"className": "hr_approval" 
					}, 
	        		{ 
						"data": "payment_status", 
						"className": "payment_status" 
					}, 
	        		{ 
						"data": "student_comment", 
						"className": "student_comment", 
					}, 
	        		{ "data": "admin_eform_comment" },
	        		{ "data": "admin_plform_comment" },
	        		{ "data": "created_at" },
	        		{ "data": "deleted_at" },
				        ],
			"createdRow": function( row, data, dataIndex ) {
						$(row).find("td.record_id").attr('id', data['id']);
						$(row).find("td.index_id").append("<input type='hidden' name='indexno' value='"+data['INDEXID']+"'/ >");
						$(row).find("td.priority_status").append("<div class='student-priority-status-here-"+data['INDEXID']+"'></div>");
						$(row).find("td.priority_status").append("<div class='student-waitlisted-here-"+data['INDEXID']+"'></div>");
						$(row).find("td.priority_status").append("<div class='student-within-two-terms-here-"+data['INDEXID']+"'></div>");
			}
	    })
	}

	function priorityStatus(data) {
		console.log(data.ps[0]);
		$.each(data.ps[0], function(x, y) {
			$("input[name='indexno']").each(function() {
				if ($(this).val() == y) {
					$('div.student-priority-status-here-'+y).text('Re-enrolment');
				}
			});
		});
		// console.log(data[1]);
		$.each(data.ps[1], function(x, y) {
			$("input[name='indexno']").each(function() {
				if ($(this).val() == y) {
					$('div.student-priority-status-here-'+y).text('Not in a class');
				}
			});
		});
		// console.log(data[2]);
		$.each(data.ps[2], function(x, y) {
			$("input[name='indexno']").each(function() {
				if ($(this).val() == y) {
					$('div.student-waitlisted-here-'+y).text('Waitlisted');
				}
			});
		});
		// console.log(data[3]);
		$.each(data.ps[3], function(x, y) {
			$("input[name='indexno']").each(function() {
				if ($(this).val() == y) {
					$('div.student-within-two-terms-here-'+y).text('Within 2 Terms');
				}
			});
		});
		// console.log(data[4]);
		// $.each(data[4], function(x, y) {
		// 	$("input[name='indexno']").each(function() {
		// 		if ($(this).val() == x) {
		// 			$('div.student-count-schedule-'+x).html('<p><span class="label label-default"> '+y+' schedule(s) originally chosen</span></p>');
		// 		}
		// 	});
		// });

		// $.each(data[5], function(x, y) {
		// 	$("input[name='indexno']").each(function() {
		// 		if ($(this).val() == x) {
		// 			$('div.student-count-schedule-'+x).html('<p><span class="label label-default"> '+y+' schedule(s) originally chosen</span></p>');
		// 		}
		// 	});
		// });
	}

	$.when.apply($.ajax(), promises).then(function() {
        $(".preloader2").fadeOut(600);
    }).then(function() {
        $.ajax({
			url: '{{ route('teacher-enrolment-preview-table') }}',
			type: 'GET',
			dataType: 'json',
			data: {Te_Code:Te_Code, _token:token},
		}).then(function(data) {
			// priorityStatus(data);
		})
    }); 

});
</script>
@stop