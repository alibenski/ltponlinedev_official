@extends('shared_template')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
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
                <th>Assigned Course / Schedule</th>
                <th>Priority Status</th>
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
                <th>Assigned Course / Schedule</th>
                <th>Priority Status</th>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
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
		// console.log(data.data)
		// var data = jQuery.parseJSON(data.data);
		// console.log(data)
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
	    	// "deferRender": true,
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
	        "columns": [
			        {
		                "data": null,
		                "className": "record_id updated_by_admin",
		                "defaultContent": ""
		            },
					{ 	"data": null,
						"className": "modifyUser",
		                "defaultContent": "" }, 
					{ 	"data": null,
						"className": "assigned_course",
		                "defaultContent": "" },
					{ 	"data": null,
						"className": "priority_status",
		                "defaultContent": "" },
	        		{ "data": "INDEXID", "className": "index_id" }, 
	        		{ "data": "users.name" }, 
	        		{ "data": "users.email" }, 
	        		{ "data": "users.sddextr.PHONE" }, 
	        		{ "data": "courses.Description"}, 
	        		{ "data": null,
						"defaultContent": 'wishlist schedule'
					}, 
	        		{ "data": null, "className": "dayInput", "defaultContent": "" }, 
	        		{ "data": null, "className": "timeInput", "defaultContent": "" }, 
	        		{ "data": null, "className": "deliveryMode", "defaultContent": "" }, 
	        		{ "data": null, "className": "flexibleDay", "defaultContent": "NOT FLEXIBLE" }, 
	        		{ "data": null, "className": "flexibleTime", "defaultContent": "NOT FLEXIBLE" }, 
	        		{ "data": null, "className": "flexibleFormat", "defaultContent": "NOT FLEXIBLE" }, 
	        		{ "data": "DEPT" },  
	        		{ "data": "cancelled_by_student", "className": "cancelled_by_student" }, 
	        		{ "data": "is_self_pay_form", "className": "is_self_pay_form_1" }, 
	        		{ "data": "is_self_pay_form", "className": "is_self_pay_form_2" }, 
	        		{ "data": null, "className": "student_comment", "defaultContent": ""}, 
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

						if ( "updated_by_admin" in data === true ) {
							if (data['updated_by_admin'] === null) {
								$(row).find("td.updated_by_admin").text("Not Assigned");
							} 
							if (data['updated_by_admin'] === 1) {
								$(row).find("td.updated_by_admin").text("Yes");
								$(row).find("td.modifyUser").text(data['modify_user']['name']);
								$(row).find("td.assigned_course").html("<p>"+data['courses']['Description']+"</p><p>"+data['schedule']['name']+"</p>");
							} 
							if (data['updated_by_admin'] === 0) {
								$(row).find("td.updated_by_admin").text("Verified and Not Assigned");
								$(row).find("td.modifyUser").text(data['modify_user']['name']);
							}
					    }

					    if ( "dayInput" in data === true ) {
					      $(row).find("td.dayInput").text(data['dayInput']);
					    }
					    if ( "timeInput" in data === true ) {
					      $(row).find("td.timeInput").text(data['timeInput']);
					    }
					    if ( "deliveryMode" in data === true ) {
							if (data['deliveryMode'] === 0) {
								$(row).find("td.deliveryMode").text("in-person");
							}
							if (data['deliveryMode']  === 1) {
								$(row).find("td.deliveryMode").text("online");
							}
							if (data['deliveryMode']  === 2) {
								$(row).find("td.deliveryMode").text("both in-person and online");
							} 
					    }
					    if ( "flexibleDay" in data === true ) {
							if (data['flexibleDay']  === 1) {
								$(row).find("td.flexibleDay").text("YES");
							}
					    }
					    if ( "flexibleTime" in data === true ) {
					      if (data['flexibleTime']  === 1) {
								$(row).find("td.flexibleTime").text("YES");
						    }
					    }
					    if ( "flexibleFormat" in data === true ) {
					      if (data['flexibleFormat']  === 1) {
								$(row).find("td.flexibleFormat").text("YES");
							}	
					    }
						if ( "is_self_pay_form" in data === true ) {
							if (data['is_self_pay_form']  === 1) {
								$(row).find("td.is_self_pay_form_1").text("N/A - Self-Payment");
							}

							if (data['is_self_pay_form']  === null) {
								if ( $.inArray(data['DEPT'],['UNOG', 'JIU','DDA','OIOS','DPKO']) != -1) {
									$(row).find("td.is_self_pay_form_1").text("N/A - Non-paying organization");
									
								} else {
									if(data['approval'] == null && data['approval_hr'] == null){ 
										$(row).find("td.is_self_pay_form_1").text("Pending Approval");
									}
									
									if(data['approval'] == 0 && data['approval_hr'] == null || data['approval_hr'] !== null) {
										$(row).find("td.is_self_pay_form_1").text("N/A - Disapproved by Manager");
									}
									
									if(data['approval'] == 1 && data['approval_hr'] == null){
										$(row).find("td.is_self_pay_form_1").text("Pending Approval");
									}

									if(data['approval'] == 1 && data['approval_hr'] == 1){
										$(row).find("td.is_self_pay_form_1").text("Approved");
									}

									if(data['approval'] == 1 && data['approval_hr'] == 0){
										$(row).find("td.is_self_pay_form_1").text("Disapproved");
									}
									
								}
							}
					    }
						if ( "is_self_pay_form" in data === true ) {
							if (data['is_self_pay_form']  != null) {
								if (data['selfpay_approval']  === 1) {
									$(row).find("td.is_self_pay_form_2").text("Approved");
								}
								if (data['selfpay_approval']  === 2) {
									$(row).find("td.is_self_pay_form_2").text("Pending Valid Document");
								}
								if (data['selfpay_approval']  === 0) {
									$(row).find("td.is_self_pay_form_2").text("Disapproved");
								} 
								if (data['selfpay_approval']  === null) {
									$(row).find("td.is_self_pay_form_2").text("Waiting for Admin");
								}
							}
					    }
						if ( "placement_schedule_id" in data === true) {
							$(row).find("td.priority_status").text("placement form");

							$(row).find("td.student_comment").html("<p>"+data['std_comments']+"</p><p>"+data['course_preference_comment']+"</p>");
						} else {
							$(row).find("td.student_comment").html("<p>"+data['std_comments']+"</p>");
						}
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
			priorityStatus(data);
		})
    }); 

});
</script>
@stop