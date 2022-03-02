@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
{{-- <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li role="presentation" class="{{ Request::is('admin/selfpayform/approved-placement-selfpay') ? "active" : ""}}"><a href="{{ route('approved-placement-selfpay') }}" target="_blank">Validated</a></li>
        <li role="presentation" class="{{ Request::is('admin/selfpayform/cancelled-placement-selfpay') ? "active" : ""}}"><a href="{{ route('cancelled-placement-selfpay') }}" target="_blank">Disapproved</a></li>
        <li role="presentation" class="{{ Request::is('admin/selfpayform/pending-placement-selfpay') ? "active" : ""}}"><a href="{{ route('pending-placement-selfpay') }}" target="_blank">Pending</a></li>
    </ul>
</div> --}}
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
                <a href="{{ route('index-placement-selfpay', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term'),'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'),'selfpay_approval' => \Request::input('selfpay_approval'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
                <a href="{{ route('index-placement-selfpay', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Session::get('Term'),'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'),'selfpay_approval' => \Request::input('selfpay_approval'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
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
	            <th>Payment Status</th>
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
                    <a href="{{ route('edit-placement-selfpay', [$form->INDEXID, $form->L, $form->Term]) }}" target="_blank" class="btn btn-space btn-warning"><span class="glyphicon glyphicon-eye-open"></span> Show</a> 
                    <a href="{{ route('admin-add-attachments-placement', [$form->INDEXID, $form->L,  $form->Term, $form->eform_submit_count]) }}" class="btn btn-space btn-info"> Upload Documents</a>
                </td>
				<td>
				@if(is_null($form->selfpay_approval)) <span class="label label-info">Waiting for Admin</span> @elseif( $form->selfpay_approval == 0 ) <span class="label label-danger">Disapproved</span> @elseif ($form->selfpay_approval == 1) <span class="label label-success">Approved</span> @else <span class="label label-warning">Pending</span> @endif	
				</td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>
                    @if($form->DEPT == '999') SPOUSE @else {{ $form->DEPT }} @endif
                    @if ($form->DEPT === 'MSU')
                        @if ($form->users->sddextr->countryMission)
                        - {{ $form->users->sddextr->countryMission->ABBRV_NAME }} 
                        @else 
                        - (country update needed)
                        @endif
                    @endif

                    @if ($form->DEPT === 'NGO')
                        @if ($form->users->sddextr->ngo_name)
                        - {{ $form->users->sddextr->ngo_name }} 
                        @else
                        - (NGO name update needed)
                        @endif
                    @endif
                
                </td>
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
<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='term_id']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data, status) {
            $("select[name='Te_Code']").html('');
            $("select[name='Te_Code']").html(data.options);
          }
      });
  }); 
</script>
@stop