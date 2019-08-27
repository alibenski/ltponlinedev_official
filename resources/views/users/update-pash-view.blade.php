@extends('admin.admin')
@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop
@section('content')

<div class="preloader2 hidden"></div>
<input type="hidden" name="_token" value="{{ Session::token() }}">

<div>
<h3>Update PASHQTCur INDEX BY BATCH Buttons</h3>
@foreach ($getLastKey as $element)
	<button class="btn btn-default batch-no" data-key="{{ $element }}">{{ $element }}</button>
@endforeach
</div>

<div>
	<h3>Update PASHQTCur Deleted Records</h3>
	<p class="small text-danger">please be sure that you have ran the batches above first</p>
	<button class="btn btn-default update-deleted-pash">UPDATE INDEX</button>
</div>

<div>
	<h3>Update INDEX Enrolment Records</h3>
	<button class="btn btn-default update-enrolment">UPDATE INDEX</button>
</div>

<div>
	<h3>Update INDEX Placement Records</h3>
	<button class="btn btn-default update-placement">UPDATE INDEX</button>
</div>

<div>
	<h3>Update INDEX Modified Forms Records</h3>
	<button class="btn btn-default update-modifiedforms">UPDATE INDEX</button>
</div>

@stop

@section('java_script')
<script>
	var token = $("input[name='_token']").val();

	$("button.batch-no").click(function() {
		var batchNo = $(this).attr('data-key');
		console.log(batchNo)

		$("div.preloader2").removeClass('hidden');

		$.ajax({
			url: '{{ route('update-PASH-IndexID') }}',
			type: 'POST',
			data: {batchNo: batchNo, _token: token},
		})
		.done(function(data) {
			console.log("success");
			if (data == 'batch has been processed. no further changes needed.') {
				alert('batch has been processed. no further changes needed.');
			}
			console.log(data);
			$("div.preloader2").addClass('hidden');
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});

	$("button.update-deleted-pash").click(function() {

		$.ajax({
			url: '{{ route('update-PASH-trashed') }}',
			type: 'POST',
			data: {_token: token},
		})
		.done(function(data) {
			console.log("success");
			alert(data);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});

	$("button.update-enrolment").click(function() {

		$.ajax({
			url: '{{ route('update-enrolment-index') }}',
			type: 'POST',
			data: {_token: token},
		})
		.done(function(data) {
			console.log("success");
			alert(data+" enrolment records changed.");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$("button.update-placement").click(function() {

		$.ajax({
			url: '{{ route('update-placement-index') }}',
			type: 'POST',
			data: {_token: token},
		})
		.done(function(data) {
			console.log("success");
			alert(data+" placement records changed.");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});

	$("button.update-modifiedforms").click(function() {

		$.ajax({
			url: '{{ route('update-modifiedforms-index') }}',
			type: 'POST',
			data: {_token: token},
		})
		.done(function(data) {
			console.log("success");
			alert(data+" modified forms records changed.");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});
</script>

@stop