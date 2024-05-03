@extends('main_no_nav2')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
    	table { table-layout:fixed; }
		th, td { word-wrap:break-word; overflow:hidden; text-overflow: ellipsis; }
		.error { color: red; }
    </style>
@stop

@section('content')

<div class="reports-section">
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
						<th>Teacher</th>
						{{-- <th>Price USD</th> --}}
						<th>Duration</th>
						<th>Organization</th>
						<th>SelfPayment</th>
						<th>MOU</th>
						<th>Sales Orders</th>
						<th>Profile</th>
						<th>Index No.</th>
						<th>Name</th>
						<th>Gender</th>
						<th>RESULT</th>
						<th>Cancelled Not Billed</th>
						<th>Excluded From Billing</th>
						<th>Cancel Date</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Term</th>
						<th>Language</th>
						<th>Description</th>
						<th>Teacher</th>
						{{-- <th>Price USD</th> --}}
						<th>Duration</th>
						<th>Organization</th>
						<th>SelfPayment</th>
						<th>MOU</th>
						<th>Sales Orders</th>
						<th>Profile</th>
						<th>Index No.</th>
						<th>Name</th>
						<th>Gender</th>
						<th>RESULT</th>
						<th>Cancelled Not Billed</th>
						<th>Excluded From Billing</th>
						<th>Cancel Date</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<input value="{{ $org }}" name="DEPT" />
<input value="{{ $term }}" name="Term" />
@stop

@section('scripts_code')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<script>
$(document).ready(function() {

    getAllStudents();
	    
	function getAllStudents() {
            
			const year = $('select[name="year"]').children("option:selected").val();
	    	const Term = $('input[name="Term"]').val();
	    	const DEPT = $('input[name="DEPT"]').val();
			
			let promises = [];	

			promises.push(
				$.ajax({
					url: "{{ route('report-by-org-admin') }}",
					type: 'GET',
					dataType: 'json',
					data: {year: year, Term: Term, DEPT: DEPT},
				})
				.then(function(data) {
					console.log(data)
					// console.log(data.data['0'])
					// getSumOfPrices(data);
					assignToEventsColumns(data);
					// console.log(data.data)
					// var data = jQuery.parseJSON(data.data);
					// console.log(data)
					$(".overlay").removeAttr('style');
				})
				.fail(function(data) {
					console.log(data);
				})

			);
			$.when.apply($.ajax(), promises).then(function() {
		        $(".overlay").fadeOut(600);
		    });

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
							{ "data": "classrooms.teachers.Tch_Name",
		        					"defaultContent": "" }, 
							// { "data": "courseschedules.prices.price_usd" }, 
							{ "data": "courseschedules.courseduration.duration_name_en" }, 
							{ "data": "DEPT" },  
							{ "data": "is_self_pay_form" },  
							{ "data": "organizations.MOU" },  
							{ "data": "organizations.sales_order" },  
							{ "data": function ( row, type, val, meta ) {
									if (row.hasOwnProperty('enrolments')) {
										if (row.enrolments.length > 0) {
											return row.enrolments[0].profile;
										}
									} else if (row.hasOwnProperty('placements')) {
										if (row.placements.length > 0) {
											return row.placements[0].profile;
										}
									}
								return "No Profile Set";
								}
							},
							{ "data": "users.indexno" }, 
							{ "data": "users.name" }, 
							{ "data": function ( row, type, val, meta ) {
									if (row.users.sddextr.SEX == 'M' || row.users.sddextr.SEX == 'm') {
											return "Male";
									} else if (row.users.sddextr.SEX == 'F' || row.users.sddextr.SEX == 'f') {
											return "Female";
									} else if (row.users.sddextr.SEX == 'O' || row.users.sddextr.SEX == 'o') {
											return "Other";
										}
									return row.users.sddextr.SEX;
								}
							
							}, 
							{ "data": "Result", "className": "result" },
							{ "data": "cancelled_but_not_billed" },
							{ "data": "exclude_from_billing" },
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
	}

});
</script>

@stop