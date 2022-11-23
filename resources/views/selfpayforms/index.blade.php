@extends('layouts.adminLTE3.index')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@stop
@section('content')
<div class="alert bg-purple col-sm-12">
    <h4 class="text-center"><strong><i class="fa fa-file-o"></i> <span> Payment-based <u>Regular Enrolment Forms</u>:</strong> Confirm if ID and payment proof attachments are valid or not.</span></h4>
</div>

@include('admin.partials._termSessionMsg')

<div class="row">
    <div class="col-sm-12">
    <div class="card card-default">
        <div class="card-header with-border">
            <h3 class="card-title">Filters:</h3>
            <div class="card-tools pull-right">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
    <div class="card-body">
        <form id="form-filter" method="GET" action="{{ route('selfpayform.index',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term')]) }}">
            
            @include('admin.partials._filterIndex')

            <!-- submit button included admin.partials._filterIndex view -->
                <a href="/admin/selfpayform/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
            </div>

        </form>
    </div>
    <div class="card-footer">
        <div class="form-group">    
            <div class="input-group-btn">
                <a href="{{ route('selfpayform.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term'),'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'),'selfpay_approval' => \Request::input('selfpay_approval'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
                <a href="{{ route('selfpayform.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Session::get('Term'),'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'),'selfpay_approval' => \Request::input('selfpay_approval'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
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
                <th>Contract Exp</th>
                <th>Contract Proof</th>
                <th>Course</th>
	            <th>ID Proof</th>
	            <th>Payment Proof</th>
	            <th>Time Stamp</th>
	        </tr>
	    </thead>
	    <tbody>
			@foreach($selfpayforms as $form)
			<tr>
				<td>
                    <a href="{{ route('selfpayform.edit', [$form->INDEXID, $form->Te_Code, $form->Term]) }}" target="_blank" class="btn btn-space btn-warning"><span class="glyphicon glyphicon-eye-open"></span> Show</a> 
                    <a href="{{ route('admin-add-attachments', [$form->INDEXID, $form->L, $form->Te_Code, $form->Term, $form->eform_submit_count]) }}" class="btn btn-space btn-info"> Upload Documents</a>
                </td>
				<td>
                @if(is_null($form->selfpay_approval)) <span class="label label-info">Waiting for Admin</span> @elseif( $form->selfpay_approval == 0 ) <span class="label label-danger">Disapproved</span> @elseif ($form->selfpay_approval == 1) <span class="label label-success">Approved</span> @else <span class="label label-warning">Pending</span> @endif   
                </td>
				<td>
				@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
				</td>
				<td>
                    @if(empty($form->DEPT)) None @else {{ $form->DEPT }}  @endif
                    @if($form->DEPT == '999') SPOUSE @endif
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
                <td>
                    @if (is_null($form->users->contract_date))
                    <button id="confirmBtn{{ $form->INDEXID }}-{{ $form->Te_Code }}-{{ $form->Term }}" data-id="{{ $form->users->id }}" data-email="{{ $form->users->email }}" type="button" class="show-modal btn btn-space btn-default  button-prevent-multi-submit confirm" title="Enter Contract Expiry"><i class="fa fa-calendar"></i> Enter Contract</button>
                    @else
                    {{ $form->users->contract_date }}
                    @endif
                </td>
                <td>{{ $form->Term }}</td>
                <td>{{ $form->courses->Description }}</td>
				<td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fas fa-file fa-2x" aria-hidden="true"></i></a> @endif</td>
				<td>@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="far fa-file fa-2x" aria-hidden="true"></i></a> @endif </td>
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
                
                @include('contract_field.contract-form-selfpay-index')

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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datetimepicker4').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
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
    $('#userId').val($(this).data('id'));
    $('.contract-form').attr('id', 'updateContractDate-'+$(this).data('id'));
    $('#showModal').modal('show'); 
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