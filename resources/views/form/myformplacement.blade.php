@extends('main')
@section('tabtitle', '| Placement Test Form')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
@stop
@section('content')
<div class="container">
  <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>Dear {{Auth::user()->sddextr->FIRSTNAME}},</p>
            <p>Our records show that you are a new student or you have not been enrolled in a UNOG language course during the past 2 terms.</p>
            <p>You are recquired to fill in the <strong>Placement Test</strong> questionnaire form below before proceeding further to the enrolment process.</p>
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

                      <div class="form-group">
                          <label class="col-md-4 control-label">Have you already taken a placement test with us?</label>

                            <div class="col-md-4">
                                      <input id="placementDecision1" name="placementDecisionA" class="with-font dyes" type="radio" value="1" required="required">
                                      <label for="placementDecision1" class="form-control-static">YES</label>
                            </div>

                            <div class="col-md-4">
                                      <input id="placementDecision2" name="placementDecisionA" class="with-font dno" type="radio" value="0" required="required">
                                      <label for="placementDecision2" class="form-control-static">NO</label>
                            </div>
                      </div>
                    
                      <div class="otherQuestions col-md-offset-1 col-md-10" style="display: none">
                        <div class="form-group">
                          <label for="enrolBeginInput" class="control-label">When? </label>
                          <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd MM yyyy - HH:ii p" data-link-field="enrolBeginInput">
                            <input class="form-control" size="16" type="text" value="" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                          </div>
                          <input type="hidden" name="enrolBeginInput" id="enrolBeginInput" value="" />
                        </div>

                        <div class="form-group">
                          <label for="" class="control-label">Which Language?</label>
                          <div class="col-md-10 col-md-offset-1">
                                  @foreach($languages as $id => $name)
                                <div class="input-group col-md-12">                                     
                                  <input id="{{ $name }}" name="L" class="with-font lang_select_no" type="radio" value="{{ $id }}">
                                  <label for="{{ $name }}" class=" form-control-static">{{ $name }}</label>
                                </div>
                                  @endforeach
                           </div>
                        </div>
                        {{-- end of otherQuestions --}}
                      </div>

                      <div class="form-group">
                          <label class="col-md-4 control-label">Are you taking the placement test?</label>

                            <div class="col-md-4">
                                      <input id="placementDecision3" name="placementDecisionB" class="with-font dyes" type="radio" value="1" required="required">
                                      <label for="placementDecision3" class="form-control-static">YES, I will attend a placement test.</label>
                            </div>

                            <div class="col-md-4">
                                      <input id="placementDecision4" name="placementDecisionB" class="with-font dno" type="radio" value="0" required="required">
                                      <label for="placementDecision4" class="form-control-static">NO, I will not attend a placement test. </label>
                            </div>
                      </div>

                      <div class="otherQuestions2 col-md-offset-1 col-md-10" style="display: none">
                          <div class="form-group">
                            <label for="">Choose one of the following dates:</label>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">Arabic 12 March</label>
                            </div>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">Chinese 12 March</label>
                            </div>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">English 12 March</label>
                            </div>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">French 12 March</label>
                            </div>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">Russian 12 March</label>
                            </div>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">Spanish 12 March</label>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label for="">Choose one of the following:</label>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">I am a complete beginner </label>
                            </div>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">I have completed level 9 </label>
                            </div>
                            <div class="col-md-12">
                            <input id="placementLang" name="placementLangA" class="with-font dyes" type="radio" value="1" required="required">
                            <label for="placementLang" class="form-control-static">I am enrolling in a post –level 9 course</label>
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

<script>
  $("input[name='agreementBtn']").on('click',function(){
    $(".disclaimer").addClass('alert-success', 500);
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
      showMeridian: 1
  });
</script>

@stop
