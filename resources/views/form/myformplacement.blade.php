@extends('main')
@section('tabtitle', '| Placement Test Form')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop
@section('content')
<div id="loader">
</div>
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>Hello {{Auth::user()->sddextr->FIRSTNAME}},</p>
            <p>You have answered <strong>NO</strong>, you are not a complete beginner on your selected language. You have been redirected to this page to answer additional questions.</p>
          </div>

          <div class="panel panel-default col-md-12">
            <div class="panel-heading "><strong>Placement Test Additional Questions</strong></div>
                <div class="panel-body col-md-12">
                  <div class="alert alert-danger">
                    <p>decide whatever messageto put here. i.e. why, how, check all possible options of availability, etc.</p>
                  </div>
                  <form method="POST" action="{{ route('postplacementinfo-additional') }}" class="form-horizontal form-prevent-multi-submit">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="hidden" name="indexno" value="{{ Auth::user()->indexno }}">
                        <input type="hidden" name="id" value="{{ $latest_placement_form->id }}">
                        <label for="" class="col-md-3 control-label">Name:</label>

                        <div class="col-md-8 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span><input  name="" class="form-control"  type="text" value="{{ Auth::user()->sddextr->FIRSTNAME }} {{ Auth::user()->sddextr->LASTNAME }}" readonly>                                    
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="org" class="col-md-3 control-label">Organization:</label>
                      <div class="col-md-8">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-globe"></i></span><input  name="org" class="form-control"  type="text" value="{{ Auth::user()->sddextr->DEPT }}" readonly>
                            </div>
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="org" class="col-md-3 control-label">Placement Form Language:</label>
                      <div class="col-md-8">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-commenting"></i></span><input  name="language" class="form-control"  type="text" value="{{ $latest_placement_form->languages->name }}" readonly>
                            </div>
                      </div>
                    </div>

                    <div class="row panel panel-info col-md-10 col-md-offset-1">
                      <div class="otherQuestions col-md-5">
                        <div class="form-group">
                          <label for="" class="control-label">Your Time Availability:</label>
                          <div class="col-md-12">
                                <div class="input-group col-md-12">                             
                                  <input id="morning" name="timeInput[]" class="with-font" type="checkbox" value="morning">
                                  <label for="morning" class="form-control-static">morning</label>
                                </div>
                                <div class="input-group col-md-12">
                                  <input id="lunchtime1" name="timeInput[]" class="with-font" type="checkbox" value="lunchtime1">
                                  <label for="lunchtime1" class="form-control-static">lunchtime1 (tbc)</label>
                                </div>
                                <div class="input-group col-md-12">
                                  <input id="lunchtime2" name="timeInput[]" class="with-font" type="checkbox" value="lunchtime2">
                                  <label for="lunchtime2" class="form-control-static">lunchtime2 (tbc)</label>
                                </div>
                                <div class="input-group col-md-12">
                                  <input id="afternoon" name="timeInput[]" class="with-font" type="checkbox" value="afternoon">
                                  <label for="afternoon" class="form-control-static">afternoon</label>
                                </div>
                           </div>
                        </div>
                      </div>

                      <div class="otherQuestions3 col-md-7">
                        <div class="form-group">
                          <label for="" class="control-label">Your Day Availability:</label>
                          <div class="col-md-12">
                            @foreach ($days as $id => $name)
                                <div class="input-group col-md-12">                             
                                  <input id="{{ $name }}" name="dayInput[]" class="with-font" type="checkbox" value="{{ $id }}">
                                  <label for="{{ $name }}" class="form-control-static">{{ $name }}</label>
                                </div>
                            @endforeach
                           </div>
                        </div>
                      </div>
                      <div class="otherQuestions2 row col-md-7">
                        <div class="insert-container col-md-12">
                            <div class="form-group">
                              <div class="place-here">
                              <label for="scheduleChoices"></label>
                                <div class="scheduleChoices col-md-12">
                                {{-- insert jquery schedules here --}}
                                </div>
                              </div>
                            </div>
                          <div class="insert-msg"></div>
                        </div>    
                      </div>
                    </div>


                   
                    {{-- disclaimer --}}
                    <div class="form-group">
                      <div class="disclaimer alert col-md-10 col-md-offset-1">
                        <input id="agreementBtn" name="agreementBtn" class="with-font" type="radio" value="1" required="required">
                        <label for="agreementBtn" class="form-control-static">I understand blah blah blah... disclaimer text here</label>
                      </div>
                    </div>
                    {{-- end of disclaimer --}}
                    <div class="col-sm-offset-5">
                      <button type="submit" class="btn btn-success button-prevent-multi-submit">Submit</button>
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                    </div>
                  </form>
                </div> 
                {{-- end panel body --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('scripts_code')

<script src="{{ asset('js/submit.js') }}"></script>    

<script>
 $(window).load(function(){
 $("#loader").fadeOut(2000);
 });
 </script>

{{-- <script>
  $(document).ready(function() {
    $.get("/check-placement-form-ajax", function(data) {
      $.each(data, function(index, val) {
        console.log('placementFormLang = ' + val.L);
        $("input[name='langInput'][value='"+ val.L +"']").attr('disabled', true);    
      });
    }); 
  });
</script>

<script>
  $(document).ready(function() {
    $("input[name='langInput']").on('click', function() {
      $("label[for='scheduleChoices']").remove();
      $(".scheduleChoices").remove();
      $('.insert-msg').remove();
      $('.insert-container').append('<div class="insert-msg"></div>')

      if ($(this).val() == 'F') {
        $(".place-here").hide().append('<label for="scheduleChoices">The French Placement Test is Online. You may take the test anytime between the dates indicated below. Click on the radio button if you agree:</label>').fadeIn('fast');

      } else {
        $(".place-here").hide().append('<label for="scheduleChoices">Available Placement Test Date(s):</label>').fadeIn('fast');
      }

      $(".place-here").hide().append('<div class="scheduleChoices col-md-12"></div>').fadeIn('fast');

      var L = $(this).val();
      var token = $("input[name='_token']").val();
      console.log(L);
      $.ajax({
          url: "{{ route('check-placement-sched-ajax') }}", 
          method: 'POST',
          data: {L:L, _token:token},
          success: function(data) {
              $.each(data, function(index, val) {
              console.log(val);
                  $(".scheduleChoices").append('<input id="placementLang'+val.language_id+'" name="placementLang" type="radio" value="'+val.id+'" required="required">').fadeIn();
                  if ($("input[name='langInput']:checked").val() == 'F') {
                    $(".scheduleChoices").append('<label for="placementLang'+val.language_id+'" class="label-place-sched form-control-static btn-space">from '+ val.date_of_plexam +' to ' + val.date_of_plexam_end + '</label>'+'<br>').fadeIn();
                  } else {
                    $(".scheduleChoices").append('<label for="placementLang'+val.language_id+'" class="label-place-sched form-control-static btn-space"> '+ val.date_of_plexam +'</label>'+'<br>').fadeIn();
                  }
              });
                $('input[name="placementLang"]').on('click', function() {
                  $('.insert-msg').hide();
                  $('.insert-msg').html("<div class='alert alert-info'>You will receive a convocation email from the Language Secretariat to confirm the time and place of the placement test.</div>").fadeIn();
                });
            }
      });
    });
  });
</script> --}}

@stop
