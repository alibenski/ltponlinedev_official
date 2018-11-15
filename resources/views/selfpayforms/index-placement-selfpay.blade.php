@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li role="presentation" class="{{ Request::is('admin/selfpayform/approved-placement-selfpay') ? "active" : ""}}"><a href="{{ route('approved-placement-selfpay') }}" target="_blank">Validated</a></li>
        <li role="presentation" class="{{ Request::is('admin/selfpayform/cancelled-placement-selfpay') ? "active" : ""}}"><a href="{{ route('cancelled-placement-selfpay') }}" target="_blank">Disapproved</a></li>
        <li role="presentation" class="{{ Request::is('admin/selfpayform/pending-placement-selfpay') ? "active" : ""}}"><a href="{{ route('pending-placement-selfpay') }}" target="_blank">Pending</a></li>
    </ul>
</div>
<div class="alert alert-selfpay col-sm-12">
    <h4 class="text-center"><strong><i class="fa fa-file"></i> <span> Payment-based <u>Placement Forms</u>:</strong> Confirm if ID and payment proof attachments are valid or not.</span></h4>
</div>

@include('admin.partials._termSessionMsg')

<div class="row">
    <div class="col-sm-12">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Filters:</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    <div class="box-body">
        <form id="form-filter" method="GET" action="{{ route('index-placement-selfpay',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term')]) }}">
            
            @include('admin.partials._filterIndex')

            <!-- submit button included admin.partials._filterIndex view -->
                <a href="/admin/selfpayform/index-placement-selfpay/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
            </div>

        </form>
    </div>
    <div class="box-footer">
        <div class="form-group">    
            <div class="input-group-btn">
                <a href="{{ route('index-placement-selfpay', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
                <a href="{{ route('index-placement-selfpay', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Session::get('Term'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>
@if(is_null($selfpayforms))

@else
{{ $selfpayforms->links() }}
<div class="table-responsive col-sm-12 filtered-table">
	<table class="table table-bordered table-striped">
	    <thead>
	        <tr>
	            <th>Operation</th>
	            <th>Status</th>
	            <th>Name</th>
	            <th>Organization</th>
                <th>Term</th>
                <th>Language</th>
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($selfpayforms as $form)
			<tr>
				<td>
					{{-- <button class="show-modal btn btn-warning" data-index="{{$form->INDEXID}}" data-tecode="{{$form->Te_Code}}" data-term="{{$form->Term}}"><span class="glyphicon glyphicon-eye-open"></span> Show</button> --}}
                    <a href="{{ route('edit-placement-selfpay', [$form->INDEXID, $form->L, $form->Term]) }}" target="_blank" class="btn btn-warning"><span class="glyphicon glyphicon-eye-open"></span> Show</a> 
                </td>
				<td>
				@if(is_null($form->selfpay_approval)) None @elseif( $form->selfpay_approval == 0 ) <span class="alert alert-danger">Disapproved</span> @elseif ($form->selfpay_approval == 1) <span class="label label-success">Approved</span> @else <span class="label label-warning">Pending</span> @endif	
				</td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>@if($form->DEPT == '999') SPOUSE @else {{ $form->DEPT }} @endif</td>
                <td>{{ $form->Term }}</td>
                <td>{{ $form->L }}</td>
				<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif</td>
				<td>@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif </td>
				<td>{{ $form->created_at}}</td>
			</tr>
			@endforeach
	    </tbody>
	</table>
</div>
<!-- Modal form to show a post -->
<div id="showModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group class-list"></div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                        <span class='glyphicon glyphicon-remove'></span> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });
});
</script>
<script>
// Show a post
$(document).on('click', '.show-modal', function() {
    $('.modal-title').text('Showing Information');
    $('#id_show').val($(this).data('id'));
    $('#title_show').val($(this).data('title'));
    // $('#form-filter').removeAttr('action');
    // $('.filter-submit-btn').replaceWith('<a class="btn btn-success next-link btn-default btn-block button-prevent-multi-submit" disabled>Next</a>');
    var index = $(this).data('index');
    var tecode = $(this).data('tecode');
    var term = $(this).data('term');
    $('#showModal').modal('show');

    $.ajax({
        url: '{{ route('show-schedule-selfpay') }}',
        type: 'GET',
        data: {'index' : index, 'tecode' : tecode, 'term' : term,
        },
    })
    .done(function(data) {
        console.log("success");
        console.log(data);
        $(".class-list").html('');
        $(".class-list").html(data.options);
        $( '#accordion' ).accordion({collapsible: true,heightStyle: "content"});
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });    
});

// on modal close
  $('#showModal').on('hide.bs.modal', function() {
    location.reload();
  })
</script>
<script language="javascript">
    window.setInterval(function(){
    if(localStorage["update"] == "1"){
        localStorage["update"] = "0";
        window.location.reload();
    }
}, 500);
</script>
@stop