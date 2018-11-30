@extends('admin.no_sidebar_admin')

@section('customcss')
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop

@section('content')
<div class="row">
	<div class="box box-primary col-sm-12">
		<div class="box-header"></div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="form-control">Index: </label>
				<p class="form-control-static">{{ $enrolment_details->INDEXID }}</p>
			</div>	

			<div class="form-group">
				<label for="form-control">Name: </label>
				<p class="form-control-static">{{ $enrolment_details->users->name }}</p>
			</div>	
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<label for="form-control">Time Stamp: </label>
				<p class="form-control-static">{{ $enrolment_details->created_at }}</p>
			</div>	
		</div>
	</div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="box box-info">
            <div class="box-header">Current Fields</div>
            <div class="box-body">
                <ul>
                <li>Language: {{ $enrolment_details->L }}</li> 
                <li>Course: {{ $enrolment_details->courses->Description }}</li>
                <div class="form-group">
                	<label for="" class="">Schedule(s):</label> 
					@foreach($enrolment_schedules as $schedule)
						<div class="form-control-static">{{ $schedule->schedule->name }}</div>
					@endforeach
                </div>
                <li>Organization: {{ $enrolment_details->DEPT }}</li>
                <li>Supervisor's email: {{  $enrolment_details->mgr_email }}</li>
                <li>Supervisor first name: {{ $enrolment_details->mgr_fname }}</li>
                <li>Supervisor last name: {{ $enrolment_details->mgr_lname }}</li>
                <li>Supervisor approval: {{ $enrolment_details->approval }}</li>
                <li>HR approval: {{$enrolment_details->approval_hr }}  </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-success">
            <div class="box-header">Change to...</div>
            <div class="box-body">
                <form method="POST" action="{{ route('update-enrolment-fields', [$enrolment_details->INDEXID, $enrolment_details->Term, $enrolment_details->Te_Code, $enrolment_details->form_counter]) }}" class="col-sm-12">{{ csrf_field() }}
                <label>Language</label> 
                @foreach ($languages as $id => $name)
		            <div class="input-group col-sm-12">
                      <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
                      <label for="{{ $name }}" class="label-lang form-control-static">{{ $name }}</label>
		            </div>
	            @endforeach
				<div class="form-group">
                	<label>Course</label>
                    <div class="col-sm-12">
                      <div class="dropdown">
                        <select class="col-sm-12 form-control course_select_no select2-basic-single" style="width: 100%; display: none;" name="Te_Code">
                            <option value="">--- Select Course ---</option>
                        </select>
                      </div>
                    </div>
                </div>

                <div class="form-group">
                	<label>Schedule</label>
                    <div class="col-sm-12">
                      <div class="dropdown">
                        <select class="col-sm-12 form-control schedule_select_no select2-basic-single" style="width: 100%; display: none;" name="schedule_id">
                            <option value="">Fill Out Language and Course Options</option>
                        </select>
                      </div>
                    </div>
                </div>

                <label>Organization</label>
            	<div class="col-sm-12">
                  <div class="dropdown">
					<select id="input" name="DEPT" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="required">
					@if(!empty($org))
						<option value="">Select</option>
						@foreach($org as $value)
						<option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
						@endforeach
					@endif
					</select>
                  </div>
                </div>

                <div class="form-group">
                	<label>Supervisor's email</label>
				</div>
                <div class="form-group">
                	<label>Supervisor first name</label>
				</div>
                <div class="form-group">
                	<label>Supervisor last name</label>
				</div>
                <div class="form-group">
                	<label>Supervisor approval</label>
				</div>
                <div class="form-group">
                	<label>HR approval </label>
				</div>
                <button type="submit" class="btn btn-success">Save</button>
                <input type="hidden" name="Term" value="{{ $enrolment_details->Term }}">
                <input type="hidden" name="form_counter" value="{{ $enrolment_details->form_counter }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                {{ method_field('PUT') }}
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('java_script')
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script src="{{ asset('js/bootstrap-maxlength.js') }}"></script>
<script src="{{ asset('js/jquery.userTimeout.js') }}"></script>
{{-- <script src="{{ asset('js/customSelect2.js') }}"></script> --}}
<script src="{{ asset('js/submit.js') }}"></script> 

<script>
  $(document).ready(function(){
    $('input[type=radio]').prop('checked',false);
    
    $('.select2-basic-single').select2({
    placeholder: "--- Select ---",
    });

  });
</script>

<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("input[name='Term']").val();
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

<script type="text/javascript">
  $("select[name='Te_Code']").on('change',function(){
      var course_id = $(this).val();
      var term = $("input[name='Term']").val();
      var token = $("input[name='_token']").val();

      $.ajax({
          url: "{{ route('select-ajax2') }}", 
          method: 'POST',
          data: {course_id:course_id, term_id:term, _token:token},
          success: function(data) {
            $("select[name='schedule_id']").html('');
            $("select[name='schedule_id']").html(data.options);
          }
      });
  }); 
</script>
@stop