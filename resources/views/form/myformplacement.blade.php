@extends('main')
@section('tabtitle', '| Placement Test Form')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop
@section('content')
<div class="container">
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>Hello {{Auth::user()->sddextr->FIRSTNAME}},</p>
            <p>You have answered <strong>NO</strong>, you are not a beginner on your selected language. You have been redirected to this page to choose the <strong>Placement Test</strong> schedule of your preferred language.</p>
          </div>
            <div class="panel panel-info">
              <div class="panel-heading"><strong>Placement Test Questionnaire</div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('postplacementinfo') }}" class="form-horizontal form-prevent-multi-submit">
                      {{ csrf_field() }}
                      <div class="form-group">
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

                      <div class="row panel panel-default col-md-10 col-md-offset-1">
{{--                         <div class="form-group">
                            <label class="col-md-4 control-label">Have you already taken a placement test with us?</label>

                              <div class="col-md-4">
                                        <input id="placementDecision1" name="placementDecisionA" class="with-font" type="radio" value="1" required="required">
                                        <label for="placementDecision1" class="form-control-static">YES</label>
                              </div>

                              <div class="col-md-4">
                                        <input id="placementDecision2" name="placementDecisionA" class="with-font" type="radio" value="0" required="required">
                                        <label for="placementDecision2" class="form-control-static">NO</label>
                              </div>
                        </div> --}}
                      
                        <div class="otherQuestions col-md-offset-1 col-md-10" style="display: none">
                          <div class="form-group">
                            <label for="whenInput" class="control-label">When? </label>
                            <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="whenInput">
                              <input class="form-control whenInputText" size="16" type="text" value="" readonly>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                            <input type="hidden" name="whenInput" id="whenInput" value="" required="" />
                          </div>

                          <div class="form-group">
                            <label for="langInput" class="control-label">Which Language?</label>
                            <div class="col-md-10 col-md-offset-1">
                                    @foreach($languages as $id => $name)
                                  <div class="input-group col-md-12">                                     
                                    <input id="{{ $name }}" name="langInput" class="with-font" type="radio" value="{{ $id }}">
                                    <label for="{{ $name }}" class="form-control-static">{{ $name }}</label>
                                  </div>
                                    @endforeach
                             </div>
                          </div>
                          {{-- end of otherQuestions --}}
                        </div>
                      </div>

                      <div class="row panel panel-default col-md-10 col-md-offset-1">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Are you enrolling for a beginner course?</label>

                              <div class="col-md-4">
                                        <input id="placementDecision3" name="placementDecisionB" class="with-font" type="radio" value="1" required="required">
                                        <label for="placementDecision3" class="form-control-static">YES</label>
                              </div>

                              <div class="col-md-4">
                                        <input id="placementDecision4" name="placementDecisionB" class="with-font" type="radio" value="0" required="required">
                                        <label for="placementDecision4" class="form-control-static">NO</label>
                              </div>
                        </div>

                        <div class="otherQuestions2 col-md-offset-1 col-md-10" style="display: none">
                            <div class="form-group">
                              <label for="">Choose one of the following dates:</label>
                              <div class="col-md-12">
                              <input id="placementLangA" name="placementLangA" class="with-font" type="radio" value="A" required="required">
                              <label for="placementLangA" class="form-control-static">Arabic 12 March</label>
                              </div>
                              <div class="col-md-12">
                              <input id="placementLangC" name="placementLangA" class="with-font" type="radio" value="C" required="required">
                              <label for="placementLangC" class="form-control-static">Chinese 12 March</label>
                              </div>
                              <div class="col-md-12">
                              <input id="placementLangE" name="placementLangA" class="with-font" type="radio" value="E" required="required">
                              <label for="placementLangE" class="form-control-static">English 12 March</label>
                              </div>
                              <div class="col-md-12">
                              <input id="placementLangF" name="placementLangA" class="with-font" type="radio" value="F" required="required">
                              <label for="placementLangF" class="form-control-static">French 12 March</label>
                              </div>
                              <div class="col-md-12">
                              <input id="placementLangR" name="placementLangA" class="with-font" type="radio" value="R" required="required">
                              <label for="placementLangR" class="form-control-static">Russian 12 March</label>
                              </div>
                              <div class="col-md-12">
                              <input id="placementLangS" name="placementLangA" class="with-font" type="radio" value="S" required="required">
                              <label for="placementLangS" class="form-control-static">Spanish 12 March</label>
                              </div>
                            </div>
                        </div>    

                        <div class="otherQuestions3 col-md-offset-1 col-md-10" style="display: none">
                            <div class="form-group">
                              <label for="">Choose one of the following:</label>
                              <div class="col-md-12">
                              <input id="noTakingPlacement1" name="noTakingPlacementA" class="with-font" type="radio" value="1" required="required">
                              <label for="noTakingPlacement1" class="form-control-static">I have completed level 9 </label>
                              </div>
                              <div class="col-md-12">
                              <input id="noTakingPlacement2" name="noTakingPlacementA" class="with-font" type="radio" value="2" required="required">
                              <label for="noTakingPlacement2" class="form-control-static">I am enrolling in a post–level 9 course </label>
                              </div>
                              {{-- <div class="col-md-12">
                              <input id="noTakingPlacement3" name="noTakingPlacementA" class="with-font" type="radio" value="3" required="required">
                              <label for="noTakingPlacement3" class="form-control-static">I am enrolling in a post–level 9 course</label>
                              </div> --}}
                            </div>
                        </div>
                      </div>
                      
                      <div class="form-group">
                            <div class="disclaimer alert col-md-10 col-md-offset-1">
                                      <input id="agreementBtn" name="agreementBtn" class="with-font" type="radio" value="0" required="required">
                                      <label for="agreementBtn" class="form-control-static">I have read and understood the <a href="http://learning.unog.ch/sites/default/files/ContainerEn/LTP/Admin/LanguageCourses_en.pdf" target="_blank">Information Circular ST/IC/Geneva/2017/6</a> regarding language courses at UNOG.</label>
                            </div>
                      </div>

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

<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.fr.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/submit.js') }}"></script>    

<script>
  $(document).ready(function() {
    $("#placementDecision1").on('click', function() {
      $(".otherQuestions").removeAttr('style');
    });
    $("#placementDecision2").on('click', function() {
      $(".otherQuestions").attr('style', 'display: none');;
      $("input[name='whenInput']").val('')
      $(".whenInputText").val('')
      $("input[name='langInput']").val('')
      $("input[name='langInput']").prop('checked', false);
    });
    $("#placementDecision3").on('click', function() {
      $(".otherQuestions2").removeAttr('style');
      $(".otherQuestions3").attr('style', 'display: none');
      $("input[name='noTakingPlacementA']").val('');
      $("input[name='noTakingPlacementA']").prop('checked', false);
    });
    $("#placementDecision4").on('click', function() {
      $(".otherQuestions3").removeAttr('style');
      $(".otherQuestions2").attr('style', 'display: none');
      $("input[name='placementLangA']").val('');
      $("input[name='placementLangA']").prop('checked', false);
    });
    $("input[name='agreementBtn']").on('click',function(){
      $(".disclaimer").addClass('alert-success', 500);
    }); 
    
  });
</script>

<script>
  $('.form_datetime').datetimepicker({
      //language:  'fr',
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      forceParse: 0,
      showMeridian: 1,
      format: "dd MM yyyy",
      minView: 2, // force to pick date only 
      pickerPosition: "bottom-left",

  });
</script>

@stop
