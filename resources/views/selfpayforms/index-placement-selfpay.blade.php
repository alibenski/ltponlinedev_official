@extends('admin.admin')
@section('customcss')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <style>
        .alert-purple{
            background-color: #6b2b63;
            color: #fff !important;
        }
    </style>
@stop
@section('content')
<div class="alert alert-purple col-sm-12">
    <h4 class="text-center"><strong><u>Payment-based Placement Forms</u>:</strong> Confirm if ID and payment proof attachments are valid or not.</h4>
</div>
<div class="alert col-sm-12">
    @if(Request::input('Term'))<h4 class="pull-right">Currently Viewing: {{ Request::input('Term') }} </h4>@else <h4>Please Choose Term</h4> @endif
</div>
<div class="row col-sm-12">
    <form id="form-filter" method="GET" action="{{ route('index-placement-selfpay',['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Request::input('Term')]) }}">
        
        <div class="form-group input-group col-sm-12">
            <h4><strong>Filters:</strong></h4>

            <div class="form-group">
              <label for="Term" class="col-md-12 control-label">Term Select:</label>
              <div class="form-group col-sm-12">
                <div class="dropdown">
                  <select id="Term" name="Term" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
                    @foreach($terms as $value)
                        <option></option>
                        <option value="{{$value->Term_Code}}">{{$value->Term_Code}} - {{$value->Comments}} - {{$value->Term_Name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
                @foreach ($languages as $id => $name)
                <div class="col-sm-4">
                    <div class="input-group"> 
                      <span class="input-group-addon">       
                        <input type="radio" name="L" value="{{ $id }}" >                 
                      </span>
                        <label type="text" class="form-control">{{ $name }}</label>
                    </div>
                </div>
                @endforeach 
            </div>
            
            <div class="form-group">           
              <label for="organization" class="col-md-12 control-label"> Organization:</label>
              <div class="form-group col-sm-12">
                <div class="dropdown">
                  <select id="input" name="DEPT" class="col-md-10 form-control select2-basic-single" style="width: 100%;">
                    @if(!empty($org))
                      @foreach($org as $value)
                        <option></option>
                        <option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
            </div>

        </div> {{-- end filter div --}}


        <div class="form-group">           
            <button type="submit" class="btn btn-success filter-submit-btn" name="submit-filter" value="submit-filter">Submit</button>
            <a href="/admin/selfpayform/index-placement-selfpay/" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span></a>
        </div>

        <div class="form-group">    
            <div class="input-group-btn">
                <a href="{{ route('selfpayform.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'), 'Term' => Request::input('Term'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
                <a href="{{ route('selfpayform.index', ['L' => \Request::input('L'), 'DEPT' => Request::input('DEPT'),'Term' => Request::input('Term'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
            </div>
        </div>
    </form>
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