@extends('layouts.adminLTE3.index')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
	<!-- Tempus Dominus Styles -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css" crossorigin="anonymous">
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
								<div class="card-body bg-light">
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
														<input type="radio" name="decision" value="1" class="form-check-input decision validateMe" disabled>                 
														<label type="text" class="form-check-label">Year (disabled)</label>
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
			</div>
		</div>

		<form id="sendEmailForm" class="invisible my-5 my-5">
			<div class="card card-default">
				<div class="card-header bg-secondary">
					<h5 class="text-bold">Send generated report to focal points with these parameters:</h5>
				</div>
				<div class="card-body bg-light">
					<div class="col-sm-6">
						<div class="form-group">
							<div class="col-md-12">
								<div class="form-group">
									<label for="">Deadline Date: <span class="text-danger">(required)</span></label>
									<div class="input-group date" id="datetimepicker4" data-target-input="nearest">
										<input type="text" id="contract-date" name="contract_date" class="form-control datetimepicker-input" data-target="#datetimepicker4" />

										<div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
											<div class="input-group-text"><i class="fa fa-calendar"></i></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="col-sm-12">
							Term: <input id="selectedTerm" value="" readonly/>
							Year: <input id="selectedYear" value="" readonly/>
							Organization: <input id="selectedOrg" value="" readonly/>
						</div>
					</div>

					<div class="col-sm-6 mt-3">
						<div class="col-sm-12">
							
							<button type="button" class="btn btn-outline-secondary send-email-btn"><i class="fas fa-paper-plane"></i> Send Email </button>
							
						</div>
					</div>

				</div>
			</div>
		</form>

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
						<th>Price USD</th>
						<th>Duration</th>
						<th>Organization</th>
						<th>Index No.</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>RESULT</th>
						<th>Days Present</th>
						<th>Days Excused</th>
						<th>Days Absent</th>
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
						<th>Index No.</th>
						<th>Last Name</th>
						<th>First Name</th>
						<th>RESULT</th>
						<th>Days Present</th>
						<th>Days Excused</th>
						<th>Days Absent</th>
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
<script type="text/javascript" src="{{ asset('js/reportByOrg.js') }}"></script>
<!-- Popperjs -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<!-- Tempus Dominus JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js" crossorigin="anonymous"></script>

<script>
	const picker = new tempusDominus
    .TempusDominus(document.getElementById('datetimepicker4'),
	{
		display: {
			icons: {
				type: 'icons',
				time: 'fas fa-clock',
				date: 'fas fa-calendar',
				up: 'fas fa-arrow-up',
				down: 'fas fa-arrow-down',
				previous: 'fas fa-chevron-left',
				next: 'fas fa-chevron-right',
				today: 'fas fa-calendar-check',
				clear: 'fas fa-trash',
				close: 'fas fa-xmark'
			},
			components: {
				clock: false,
			}
		},
		localization: {
			format: 'dd MMMM yyyy',
		},
	});
</script>

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
		}	else {
			console.log('no, form not valid')	    	
		}
	});

	$('input.decision').on('click', function(event) {
		let decision = event.target.value;
		if (decision === '1') {
			$('.filter-by-year').removeClass('invisible');
			$('.filter-by-term').addClass('invisible');
			$('select[name="year"]').addClass('validateMe');
			$('select[name="term"]').removeClass('validateMe');
			$('select[name="term"]').prop('selectedIndex',-1);
			$('select[name="term"]').select2({placeholder: "Select Term"});
		}
		if (decision === '0') {
			$('.filter-by-term').removeClass('invisible');
			$('.filter-by-year').addClass('invisible');
			$('select[name="term"]').addClass('validateMe');
			$('select[name="year"]').removeClass('validateMe');
			$('select[name="year"]').prop('selectedIndex',-1);
			$('select[name="year"]').select2({placeholder: "Select Year"});
		}

	});

	$("select").on('change', function() {
		$('#sampol').DataTable().clear().draw();
		$(".reports-section").addClass('invisible');
		$("form#sendEmailForm").addClass('invisible');
		$(".overlay").removeAttr('style');
	});

	$('button.send-email-btn').on('click', function(e) {
		e.preventDefault();

			const deadline = $('input[name="contract_date"]').val();
			const year = $('select[name="year"]').children("option:selected").val();
	    	const Term = $('select[name="term"]').children("option:selected").val();
	    	const DEPT = $('select[name="organization"]').children("option:selected").val();
			
			$.ajax({
				url: "{{ route('send-email-report-by-org') }}",
				type: 'GET',
				dataType: 'json',
				data: {year: year, Term: Term, DEPT: DEPT, deadline: deadline},
			})
			.then(function(data) {
				console.log(data)
				if (data["data"] == 0) {
					alert("No email sent because the organization has no focal point.");
				}  
				if (data["data"] == 1) {
					alert("Email sent to focal point(s).");
				}
				
				$(".overlay").removeAttr('style');
			})
			.fail(function(data) {
				console.log(data);
				alert("Something went wrong!");
				})
	});

	function showFieldValues() {
			const year = $('select[name="year"]').children("option:selected").val();
	    	const Term = $('select[name="term"]').children("option:selected").val();
	    	const DEPT = $('select[name="organization"]').children("option:selected").val();

			$('input#selectedTerm').val(Term);
			$('input#selectedOrg').val(DEPT);
			$('input#selectedYear').val(year);
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
					$("#sendEmailForm").removeClass('invisible');
				})
				.fail(function(data) {
					console.log(data);
				})

			);
			$.when.apply($.ajax(), promises).then(function() {
		        $(".overlay").fadeOut(600);
		    });

			$.getScript("/js/reportByOrg.js");		
	}

});
</script>

@stop