@extends('admin.no_sidebar_admin')

@section('customcss')
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop

@section('content')
<div class="row">
	<div class="col-sm-12">
		<a href="{{ route('manage-user-enrolment-data', $student->id) }}" class="btn btn-danger btn-space"><span class="glyphicon glyphicon-arrow-left"></span> Back to User LTP Data</a>
	</div>
</div>
<div class="row">
    <div class="col-sm-12">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Enrol Student To Course</h3>
        </div>
    <div class="box-body">
		<form method="POST" action="{{ route('enrol-student-to-course-insert') }}" class="col-sm-12 form-horizontal form-prevent-multi-submit" enctype="multipart/form-data">
		    {{ csrf_field() }}
		<input type="hidden" name="id" value="{{$student->id}}">
		<div class="row">
		<div class="col-sm-6 ">
			<div class="form-group col-sm-12">
				<label for="INDEXID">Index:</label>
				@if($student->sddextr)
					<input type="text" name="INDEXID" value="{{ $student->sddextr->INDEXNO }}" class="form-control" readonly>
				@else
					<div class="alert alert-danger">
						<p>No SDDEXTR record in the system</p>
					</div>
				@endif
				<label for="name">Name:</label>
				<input type="text" name="name" value="{{ $student->name }}" class="form-control" readonly>
			</div>

			<div class="form-group col-sm-12">
				<label for="profile">Profile:</label>
				@include('ajax-profile-select')
			</div>
			
			<div class="form-group col-sm-12">
				<label for="DEPT">Organization</label>
				<select name="DEPT" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" autocomplete="off">
				@foreach($orgs as $org)
				  <option></option>
		          <option value="{{ $org['Org name'] }}">{{ $org['Org name'] }} - {{ $org['Org Full Name'] }}</option>
		        @endforeach
		        </select>
			</div>
			<div class="form-group col-sm-12">
				<label for="Term">Term</label>
				<select name="Term" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" autocomplete="off">
				@foreach($terms as $term)
				  <option></option>
		          <option value="{{ $term->Term_Code}}">{{ $term->Comments }} - {{ $term->Term_Name }}</option>
		        @endforeach
		        </select>
			</div>
			
			<div class="form-group col-sm-12">
		      <label for="L" class="col-sm-12">Language:</label>
		        @foreach ($languages as $id => $name)
		        <div class="col-sm-4">
		            <div class="input-group"> 
		              <span class="input-group-addon">       
		                <input type="radio" name="L" value="{{ $id }}" autocomplete="off">                 
		              </span>
		                <label type="text" class="form-control">{{ $name }}</label>
		            </div>
		        </div>
		        @endforeach 
		    </div>

			<div class="form-group col-sm-12">
				<label for="Te_Code">Course</label>
				<select name="Te_Code" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
		          <option></option>
		        </select>
			</div>

			<div class="form-group col-sm-12">
				<label for="schedule_id">Schedule</label>
				<select name="schedule_id" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
		          <option></option>
		        </select>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group col-sm-12">
				<label for="decision">Self-paying Student?</label>
				<div class="col-sm-12">
		                    <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" required="required" autocomplete="off">
		                    <label for="decision1" class="form-control-static">Yes</label>
		          </div>

		          <div class="col-sm-12">
		                    <input id="decision2" name="decision" class="with-font dno" type="radio" value="" required="required" autocomplete="off">
		                    <label for="decision2" class="form-control-static">No</label>
		          </div>
		    </div>

			<div class="form-group col-sm-12 file-section hidden">
                <div class="alert alert-default alert-block">
                  <div class="small text-danger">
                    <strong>Note: accepts pdf, doc, and docx files only. File size must less than 8MB.</strong>
                  </div>
                
                  <div class="form-group">
                    <label for="identityfile">Upload Proof of Identity: </label>
                    <input name="identityfile" type="file">
                  </div>

                  <div class="form-group">
                    <label for="payfile">Upload Proof of Payment: </label>
                    <input name="payfile" type="file">
                  </div>  

				  @include('file_attachment_field.contract-file-attachment')
				  
                </div>
            </div>
			
			<div class="form-group col-sm-12">
			    <label class="control-label" for="created_at">Manually Set Time Stamp: </label>

			        <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - hh:ii:ss" data-link-field="created_at">
                        <input class="form-control" size="16" type="text" value="{{\Carbon\Carbon::now()}}" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
					</div>
                        <input type="hidden" name="created_at" id="created_at" value="{{\Carbon\Carbon::now()}}" required=""/>
			</div>

			<div class="form-group col-sm-12">
			    <label class="control-label" for="Comments">Admin Comment: </label>
			    <div class="">
			        <textarea class="form-control" name="Comments" cols="40" rows="3">Created and approved by CLM Language Administrator</textarea>
			    </div>
			</div>
		</div>
		</div> <!-- /.row -->

			<div class="col-sm-2 col-sm-offset-5">
              <button type="submit" class="btn btn-success button-prevent-multi-submit">Add Enrolment Form</button>
              <input type="hidden" name="_token" value="{{ Session::token() }}">
            </div>
		</form>
    </div>
    <div class="box-footer">
        <div class="form-group">    

        </div>
    </div>
    </div>
    </div>
</div>


@stop

@section('java_script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script>
  $(document).ready(function() {
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        // weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        // todayHighlight: 1,
        // startView: 4,
        // forceParse: 0,
        // showMeridian: 1,
        // minView: 2
    });
  });
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2').select2({
    placeholder: "--- Select Here ---",
    });
	$('.select-profile-single').select2({
    placeholder: "--- Select Profile Here ---",
    });
    $("input[name='decision']").prop('checked', false);
});
</script>
<script type="text/javascript">
  $("input[name='L']").click(function(){
      var L = $(this).val();
      var term = $("select[name='Term']").val();
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
  $("select[name='Te_Code']").on('change',function(){
      var course_id = $(this).val();
      var term = $("select[name='Term']").val();
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
  $("input[name='decision']").click(function(){
      if($('#decision1').is(':checked')) {
        $('.file-section').removeClass('hidden');
      } else if ($('#decision2').is(':checked')) {
        $('.file-section').addClass('hidden');
      }  
    });
</script>


@stop