@extends('teachers.teacher_template')

@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="alert alert-info col-sm-12">
	<h4 class="text-center"><strong>Teacher's View of Regular Enrolment Forms</strong></h4>
</div>

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
    <form method="GET" action="{{ route('teacher-enrolment-preview',['L' => \Request::input('L'), 'Te_Code' => \Request::input('Te_Code'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term')]) }}">
		
		@if(Session::has('Term'))
		<input type="hidden" name="term_id" value="{{ Session::get('Term') }}">
		<div class="form-group input-group col-sm-12">
		    <div class="form-group col-sm-12">
		      <label for="search" class="control-label">Search by Name:</label>
		      <input type="text" name="search" class="form-control" placeholder="Enter name here">
		    </div>

		    <div class="form-group col-sm-12">
		      <label for="L" class="control-label"> Language:</label>
		      <div class="col-sm-12">
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
		    </div>
		    
		    @if(!Request::is('admin/placement-form') && !Request::is('admin/selfpayform/index-placement-selfpay'))
		    <div class="form-group">
		        <label for="Te_Code" class="col-md-12 control-label"> Course: </label>
		        <div class="form-group col-sm-12">
		          <div class="dropdown">
		            <select class="col-md-10 form-control select2-basic-single" style="width: 100%;" name="Te_Code" autocomplete="off">
		                <option value="">--- Select ---</option>
		            </select>
		          </div>
		        </div>
		    </div>
		    @else
		    @endif

		    <div class="form-group col-sm-12">
		      <label for="overall_approval" class="control-label"></label>
		      <div class="col-sm-12">
		        
		        <div class="col-sm-8">
		            <div class="input-group"> 
		              <span class="input-group-addon">       
		                <input type="checkbox" name="overall_approval" class="add-filter" value=1 >                 
		              </span>
		                <label type="text" class="form-control bg-green">View Approved Forms Only</label>
		            </div>
		        </div>
		        
		      </div>
		    </div>

		</div> <!-- end filter div -->

		<div class="form-group">           
		                <button type="submit" class="btn btn-success filter-submit-btn">Submit</button>
		@else
		@endif

        <!-- submit button included admin.partials._filterIndex view -->
        	<a href="/admin/teacher-enrolment-preview" class="filter-reset btn btn-danger"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
        </div>

    </form>
</div>
	<div class="box-footer">
        <div class="form-group">    
            <div class="input-group-btn">
		        <a href="{{ route('teacher-enrolment-preview', ['L' => \Request::input('L'), 'Te_Code' => \Request::input('Te_Code'), 'DEPT' => Request::input('DEPT'), 'Term' => Session::get('Term'),'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'),'sort' => 'asc']) }}" class="btn btn-default">Oldest First</a>
		        <a href="{{ route('teacher-enrolment-preview', ['L' => \Request::input('L'), 'Te_Code' => \Request::input('Te_Code'), 'DEPT' => Request::input('DEPT'),'Term' => Session::get('Term'),'is_self_pay_form' => \Request::input('is_self_pay_form'), 'overall_approval' => \Request::input('overall_approval'),'sort' => 'desc']) }}" class="btn btn-default">Newest First</a>
			</div>
        </div>
    </div>
    </div>
    </div>
</div>

@if(empty(Request::all())) 
@else

	@if(is_null($enrolment_forms))

	@else
	<div class="row">
		<div class="col-sm-12">
			<div class="col-sm-4">
				<h3>Total # <span class="label label-info">{{$count}}</span></h3>
			</div>
			@if (Request::has('Te_Code')||Request::has('overall_approval')||Request::has('L'))
			<div class="pull-right col-sm-4">
				<div class="box box-default">
					<div class="box-header with-border">
			            <h3 class="box-title">Current Filters:</h3>
			            <div class="box-tools pull-right">
			                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			            </div>
			        </div>
					<div class="box-body">
						
						<p>Language: {{Request::get('L')}}</p>
						<p>Course Code: {{Request::get('Te_Code')}}</p>
						<p>
							@if (Request::get('overall_approval') == 1)
								Viewing only approved enrolment forms
							@else
								Viewing approved and non-approved enrolment forms
							@endif
						</p>
					
					</div>
				</div>
			</div>
			@endif
		
		</div>
	</div>
	{{ $enrolment_forms->links() }}
	<div class="filtered-table table-responsive">
		<table class="table table-bordered table-striped">
		    <thead>
		        <tr>
		        	<th>Validated/Assigned Course?</th>
		            <th>Name</th>
		            <th>Email</th>
		            <th>Contact No.</th>
		            <th>Course</th>
		            <th>Schedule</th>
		            <th>Organization</th>
		            <th>Student Cancelled?</th>
		            <th>HR Approval</th>
		            <th>Payment Status</th>
		            {{-- <th>ID Proof</th>
		            <th>Payment Proof</th> --}}
		            <th>Comment</th>
		            <th>Time Stamp</th>
		            <th>Cancel Date/Time Stamp</th>
		        </tr>
		    </thead>
		    <tbody>
				@foreach($enrolment_forms as $form)
				<tr @if($form->deleted_at) style="background-color: #eed5d2;" @else @endif>
					<td>
						@if(empty($form->updated_by_admin)) <span class="label label-danger margin-label">Not Assigned </span>
		                @else
		                  @if ($form->modified_by)
		                    <span class="label label-success margin-label">Yes by {{$form->modifyUser->name }} </span>
		                  @endif
		                @endif
					</td>
					<td>
					@if(empty($form->users->name)) None @else {{ $form->users->name }} @endif
					</td>
					<td>
					@if(empty($form->users->email)) None @else {{ $form->users->email }} @endif
					</td>
					<td>
					@if(empty($form->users->sddextr->PHONE)) None @else {{ $form->users->sddextr->PHONE }} @endif
					</td>
					<td>{{ $form->courses->Description }}</td>
					<td>
						<a id="modbtn" class="btn btn-default btn-space" data-toggle="modal" href="#modalshow" data-indexno="{{ $form->INDEXID }}"  data-term="{{ $form->Term }}" data-tecode="{{ $form->Te_Code }}" data-approval="{{ $form->approval }}" data-formx="{{ $form->form_counter }}" data-mtitle="{{ $form->courses->EDescription }}"> View</a>
					</td>
					<td>{{ $form->DEPT }}</td>
					<td>
						@if( is_null($form->cancelled_by_student))
						@else <span id="status" class="label label-danger margin-label">YES</span>
						@endif
					</td>
					<td>
						@if(is_null($form->is_self_pay_form))
							@if(in_array($form->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO']))
								<span id="status" class="label label-info margin-label">
								N/A - Non-paying organization</span>
							@else
								@if(is_null($form->approval) && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 0 && (is_null($form->approval_hr) || isset($form->approval_hr)))
								<span id="status" class="label label-danger margin-label">
								N/A - Disapproved by Manager</span>
								@elseif($form->approval == 1 && is_null($form->approval_hr))
								<span id="status" class="label label-warning margin-label">
								Pending Approval</span>
								@elseif($form->approval == 1 && $form->approval_hr == 1)
								<span id="status" class="label label-success margin-label">
								Approved</span>
								@elseif($form->approval == 1 && $form->approval_hr == 0)
								<span id="status" class="label label-danger margin-label">
								Disapproved</span>
								@endif
							@endif
						@else
						<span id="status" class="label label-info margin-label">
						N/A - Self-Payment</span>
						@endif
					</td>

					<td>
						@if(is_null($form->is_self_pay_form))
	                    <span id="status" class="label label-info margin-label">N/A</span>
	                    @else
	                      @if($form->selfpay_approval === 1)
	                      <span id="status" class="label label-success margin-label">Approved</span>
	                      @elseif($form->selfpay_approval === 2)
	                      <span id="status" class="label label-warning margin-label">Pending Valid Document</span>
	                      @elseif($form->selfpay_approval === 0)
	                      <span id="status" class="label label-danger margin-label">Disapproved</span>
	                      @else 
	                      <span id="status" class="label label-info margin-label">Waiting for Admin</span>
	                      @endif
	                    @endif
					</td>

					{{-- <td>@if(empty($form->filesId->path)) None @else <a href="{{ Storage::url($form->filesId->path) }}" target="_blank"><i class="fa fa-file fa-2x" aria-hidden="true"></i></a> @endif
					</td>
					<td>
					@if(empty($form->filesPay->path)) None @else <a href="{{ Storage::url($form->filesPay->path) }}" target="_blank"><i class="fa fa-file-o fa-2x" aria-hidden="true"></i></a> @endif
					</td> --}}
					<td>
						@if ($form->std_comments)
							<button type="button" class="show-std-comments btn btn-primary btn-space" data-toggle="modal"> View </button>
						@endif
						<input type="hidden" name="eform_submit_count" value="{{$form->eform_submit_count}}">
						<input type="hidden" name="term" value="{{$form->Term}}">
						<input type="hidden" name="indexno" value="{{$form->INDEXID}}">
						<input type="hidden" name="tecode" value="{{$form->Te_Code}}">
						<input type="hidden" name="_token" value="{{ Session::token() }}">
					</td>
					<td>{{ $form->created_at}}</td>
					<td>{{ $form->deleted_at}}</td>
				</tr>
				@endforeach
		    </tbody>
		</table>
		{{ $enrolment_forms->links() }}
	</div>
	@endif

<!-- modal for enrolments form chosen schedule -->
<div id="modalshow" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body-schedule">
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal form to show student comments on regular forms -->
<div id="showStdComments" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title-std-comments"><i class="fa fa-comment fa-2x text-primary"></i> Student Comment</h4>
            </div>
            <div class="modal-body">
				@if(empty($enrolment_forms))

				@else
					@if(count($enrolment_forms) == 0)
					
					@else
	                <div class="panel-body modal-body-std-comments"></div>
                	@endif
                @endif	  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                    <span class='glyphicon glyphicon-remove'></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endif

@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).on('click', '.show-std-comments', function() {
	var indexno = $(this).closest("tr").find("input[name='indexno']").val();
	var tecode = $(this).closest("tr").find("input[name='tecode']").val();
	var eform_submit_count = $(this).closest("tr").find("input[name='eform_submit_count']").val();
	var term = $(this).closest("tr").find("input[name='term']").val();
	var token = $("input[name='_token']").val();
    $('#showStdComments').modal('show'); 

    $.post('{{ route('ajax-std-comments') }}', {'indexno':indexno, 'tecode':tecode, 'term':term,  'eform_submit_count':eform_submit_count, '_token':token}, function(data) {
          $('.modal-body-std-comments').html(data);
      });
});

$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "Select Filter",
    });

    $('#modalshow').on('show.bs.modal', function (event) {
      var link = $(event.relatedTarget); // Link that triggered the modal
      var dtitle = link.data('mtitle');
      var dindexno = link.data('indexno');
      var dtecode = link.data('tecode');
      var dterm = link.data('term');
      var dapproval = link.data('approval');
      var dFormCounter = link.data('formx');
      var token = $("input[name='_token']").val();
      var modal = $(this);
      modal.find('.modal-title').text(dtitle);

      var token = $("input[name='_token']").val();      

      $.post('{{ route('ajax-show-modal') }}', {'indexno':dindexno, 'tecode':dtecode, 'term':dterm, 'approval':dapproval, 'form_counter':dFormCounter, '_token':token}, function(data) {
          console.log(data);
          $('.modal-body-schedule').html(data)
      });
    });

});
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