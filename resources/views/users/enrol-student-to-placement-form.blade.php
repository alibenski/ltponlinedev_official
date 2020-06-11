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
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Create Placement Test Form</h3>
        </div>
    <div class="box-body">
		<form method="POST" action="{{ route('enrol-student-to-placement-insert') }}" class="col-sm-12 form-horizontal form-prevent-multi-submit" enctype="multipart/form-data">
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
				<select name="profile" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" required="required" autocomplete="off">
				    <option></option>
				    <option value="STF">Staff Member</option>
				    <option value="INT">Intern</option>
				    <option value="CON">Consultant</option>
            <option value="WAE">When Actually Employed</option>
				    <option value="JPO">JPO</option>
				    <option value="MSU">Staff of Permanent Mission</option>
				    <option value="SPOUSE">Spouse of Staff from UN or Mission</option>
				    <option value="RET">Retired UN Staff Member</option>
				    <option value="SERV">Staff of Service Organizations in the Palais</option>
				    <option value="NGO">Staff of UN-accredited NGO's</option>
				    <option value="PRESS">Staff of UN Press Corps</option>
				</select>
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
				<div class="place-here col-sm-12">
				<label for="scheduleChoices">Placement Test Dates</label>
				<div class="scheduleChoices col-md-12">
                {{-- insert jquery schedules here --}}
                </div>
            	</div>
			</div>
			
			<div class="time-section hidden"> {{-- start of hidden fields --}}
				<div class="form-group col-sm-12">
					<label for="placement_time">Time of Test</label>
					<select name="placement_time" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" autocomplete="off">
					  <option></option>
					@foreach($times as $time)
			          <option value="{{ $time->id }}">{{ date('h:i:sa', strtotime($time->Begin_Time)) }}</option>
			        @endforeach
			        </select>
				</div>
			</div> {{-- end of hidden fields --}}
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
			        <textarea class="form-control" name="Comments" cols="40" rows="3">Placement form created and approved by CLM Language Administrator</textarea>
			    </div>
			</div>
		</div>
		</div> <!-- /.row -->

			<div class="col-sm-2 col-sm-offset-5">
              <button type="submit" class="btn btn-success button-prevent-multi-submit">Add Placement Form</button>
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
    $("input[name='decision']").prop('checked', false);
});
</script>

<script type="text/javascript">
  $("input[name='L']").click(function(){
  	// reset select2 (4.0.3) value to NULL and show placeholder  
    $("select[name='placement_time']").val([]).trigger('change');
  	$("label[for='scheduleChoices']").remove();
    $(".scheduleChoices").remove();
      if ($(this).val() == 'F') {
        $(".place-here").hide().append('<label for="scheduleChoices">The French placement test is Online:</label>').fadeIn('fast');
        $(".time-section").addClass('hidden');
      } else {
        $(".place-here").hide().append('<label for="scheduleChoices">Available Placement Test Date(s):</label>').fadeIn('fast');
        // $(".time-section").removeClass('hidden');
      }

      $(".place-here").hide().append('<div class="scheduleChoices col-md-12"></div>').fadeIn('fast');

      var L = $(this).val();
      var term = $("select[name='Term']").val();
      var token = $("input[name='_token']").val();
      console.log(L);
      $.ajax({
          url: "{{ route('check-placement-sched-ajax') }}", 
          method: 'POST',
          data: {L:L, term_id:term, _token:token},
          success: function(data) { // get the placement test schedules
              $.each(data, function(index, val) {
                  var m_names = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                  var d = new Date(val.date_of_plexam);
                  var curr_date = d.getDate();
                  var curr_month = d.getMonth();
                  var curr_year = d.getFullYear();
                  var dateString = curr_date + " " + m_names[curr_month] + " " + curr_year;

                  var dend = new Date(val.date_of_plexam_end);
                  var curr_date_end = dend.getDate();
                  var curr_month_end = dend.getMonth();
                  var curr_year_end = dend.getFullYear();
                  var dateStringEnd = curr_date_end + " " + m_names[curr_month_end] + " " + curr_year_end;

                  console.log('is online:' + val.is_online)
                  if (val.is_online == 1) {
                    $(".scheduleChoices").append('<div class="input-group"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space">Online from '+ dateString +' to ' + dateStringEnd + '</label></div>').fadeIn();
                  } else {
                    $(".scheduleChoices").append('<div class="input-group"><input id="placementLang'+val.language_id+'-'+val.id+'" name="placementLang" type="radio" class="with-font" value="'+val.id+'" ><label for="placementLang'+val.language_id+'-'+val.id+'" class="label-place-sched form-control-static btn-space"> '+ dateString +'</label></div>').fadeIn();
                  }
              }); // end of $.each
              // if no schedule, tell student there is none
              if (!$("input[name='placementLang']").length){
                console.log('no schedule input');
                $("label[for='scheduleChoices']").html("<div class='alert alert-danger'>Either you forgot to set the Term field or there is no placement test schedule available for this language</div>");
              } 

              // insert message of convocation email
              $('input[name="placementLang"]').on('click', function() {
                // $("textarea[name='course_preference_comment']").attr('required', 'required');
                $('.insert-msg').hide();
                $('.insert-msg').addClass('col-md-6 col-md-offset-3');     
                $('.insert-msg').html("<div class='alert alert-info'>You will receive further information from the Language Secretariat regarding the placement test.</div>").fadeIn();
              });
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