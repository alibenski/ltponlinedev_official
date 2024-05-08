@extends('layouts.adminLTE3.index')

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

<h2 class="text-center"><i class="fa fa-building"></i> Admin View: Report by Organization <i class="fa fa-building"></i></h2>
		<div class="card card-default">
			<div class="card-body">
			<form id="reportForm" method="get" action="" class="col-sm-12">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-secondary">
								<p class="text-justify"><i class="fa fa-info-circle"></i> Information about the data below: <br />
								<ul>
									<li>Includes students who cancelled late after deadline</li>
									<li>Late cancellations can be filtered with Cancelled Not Billed column</li>
									<li>Late cancellations can be filtered with Excluded From Billing column</li>
								</ul>
								</p>
							</div>
						</div>
					</div>

                    <section id="filter">
                        <div class="card card-default">
                            <div class="card-header with-border">
                                <h4><strong>View Students by Organization</strong></h4>
                            </div>
                            <div class="card-body ">
                                <form id="reportForm" method="get" action="" class="col-sm-6">
                                <div class="row">
                                        <div class="form-group col-sm-12">
                                        <label for="organization" class="col-sm-12 control-label">Organization Select: (required)</label>
                                        <div class="form-group col-sm-12">
                                            <div class="dropdown">
                                            <select id="organization" name="organization" class="col-sm-12 form-control select2-basic-single" style="width: 100%;" required="required" autocomplete="off">
                                                @foreach($orgs as $org)
                                                    <option value=""></option>
                                                    <option value="{{$org['Org name']}}">{{$org['Org name']}} - {{ $org['Org Full Name'] }}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="decision" class="control-label col-sm-12">Year or Term? (required)</label>

                                            <div class="form-check form-check-inline col-sm-5 mx-2">
                                                    <input type="radio" name="decision" value="1" class="form-check-input decision validateMe">                 
                                                    <label type="text" class="form-check-label">Year</label>
                                            </div>

                                            <div class="form-check form-check-inline col-sm-5 mx-2">
                                                    <input type="radio" name="decision" value="0" class="form-check-input decision validateMe">                 
                                                    <label type="text" class="form-check-label">Term</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-5 filter-by-year form-group invisible">
                                        <label for="year" class="col-sm-12 control-label">Year Select:</label>
                                        <div class="form-group col-sm-12">
                                            <div class="dropdown">
                                            <select id="year" name="year" class="col-sm-8 form-control select2-basic-single" style="width: 100%;" autocomplete="off">
                                                @foreach($years as $value)
                                                    <option></option>
                                                    <option value="{{$value}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="col-sm-5 filter-by-term form-group invisible">
                                        <label for="term" class="col-sm-12 control-label">Term Select:</label>
                                        <div class="form-group col-sm-12">
                                            <div class="dropdown">
                                            <select id="term" name="term" class="col-sm-8 form-control select2-basic-single" style="width: 100%;" autocomplete="off">
                                                @foreach($terms as $term)
                                                    <option></option>
                                                    <option value="{{$term->Term_Code}}">{{$term->Term_Code}} - {{$term->Comments}} - {{$term->Term_Name}}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        </div>
                                </div> {{-- end filter div --}}
                            </div>
                        </div>
                    </section>
				</div>

				<div class="row col-sm-12">
					<input type="submit" class="btn btn-success submit-filter" value="Submit">		
				</div>
			</form>

			<form id="sendEmailForm" class="invisible">
				<input id="selectedTerm" value="" />
				<input id="selectedOrg" value="" />
				<div class="row col-sm-12">
					
					<button type="button" class="btn btn-outline-secondary send-email-btn"><i class="fas fa-paper-plane"></i> Send Email </button>

				</div>
			</form>
			</div>
		</div>

<div class="reports-section invisible">
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

@stop

@section('java_script')

<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>

<script>
$(document).ready(function() {
	$('.select2-basic-single').select2({
    	placeholder: "Select Filter",
    });

	var form = $("#reportForm");

	form.validate({
		rules: {
			'decision': {
				required: true
			},
		},
		messages: {
			'decision': "Please select year or term.",
		},
		errorPlacement: function (error, element) {
			console.log(element)
			error.insertBefore(element.offsetParent("div.input-group"));
		},
		errorElement: 'div'
			});

	$.validator.addClassRules("validateMe", {
			required: true
	});

	$('input.submit-filter').on('click', function(e) {
		e.preventDefault();
		if (form.valid()) {
			showFieldValues();
			getAllStudents();
			$(".reports-section").removeClass('invisible');
			$("#sendEmailForm").removeClass('invisible');
		}	else {
			console.log('no')	    	
		}
	});

	$('input.decision').on('click', function(event) {
		let decision = event.target.value;
		if (decision === '1') {
			$('.filter-by-year').removeClass('invisible');
			$('.filter-by-term').addClass('invisible');
			$('select[name="year"]').addClass('validateMe');
			$('select[name="term"]').prop('selectedIndex',-1);
			$('select[name="term"]').select2({placeholder: "Select Term"});
		}
		if (decision === '0') {
			$('.filter-by-term').removeClass('invisible');
			$('.filter-by-year').addClass('invisible');
			$('select[name="term"]').addClass('validateMe');
			$('select[name="year"]').prop('selectedIndex',-1);
			$('select[name="year"]').select2({placeholder: "Select Year"});
		}

	});

	$("select").on('change', function() {
		$('#sampol').DataTable().clear().draw();
		$(".reports-section").addClass('invisible');
		$(".overlay").removeAttr('style');
	});

	$('button.send-email-btn').on('click', function(e) {
		e.preventDefault();

			const year = $('select[name="year"]').children("option:selected").val();
	    	const Term = $('select[name="term"]').children("option:selected").val();
	    	const DEPT = $('select[name="organization"]').children("option:selected").val();
			
			$.ajax({
				url: "{{ route('send-email-report-by-org') }}",
				type: 'GET',
				dataType: 'json',
				data: {year: year, Term: Term, DEPT: DEPT},
			})
			.then(function(data) {
				console.log(data)
				
				$(".overlay").removeAttr('style');
			})
			.fail(function(data) {
				console.log(data);
				})
	});

	function showFieldValues() {
			const year = $('select[name="year"]').children("option:selected").val();
	    	const Term = $('select[name="term"]').children("option:selected").val();
	    	const DEPT = $('select[name="organization"]').children("option:selected").val();

			$('input#selectedTerm').val(Term);
			$('input#selectedOrg').val(DEPT);
	}

	function getAllStudents() {
		
			const year = $('select[name="year"]').children("option:selected").val();
	    	const Term = $('select[name="term"]').children("option:selected").val();
	    	const DEPT = $('select[name="organization"]').children("option:selected").val();
			
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