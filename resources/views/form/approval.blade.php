@extends('public')
@section('tabtitle', 'Manager Approval Page')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">

        <div class="card-header bg-primary">Manager/Supervisor Approval Page Enrolment Form for: <strong>{{ $next_term_name }}</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ route('approval.updateform', [$input_staff->INDEXID, $input_staff->Te_Code, $input_staff->form_counter, $next_term_code]) }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <input  name="INDEXID" type="hidden" value="{{$input_staff->INDEXID}}" readonly>
                <input  name="Te_Code" type="hidden" value="{{$input_staff->Te_Code}}" readonly>
                <input  name="form_counter" type="hidden" value="{{$input_staff->form_counter}}" readonly>
                @foreach($input_course as $course)
                  <input  name="schedule_id[]" type="hidden" value="{{$course->schedule->name}}" readonly>
                @endforeach
                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Student Name:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="" class="form-control"  type="text" value="{{$input_staff->users->name}}" readonly>                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-md-3 control-label">Student Email Address:</label>
                    
                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="email" placeholder="" class="form-control"  type="text" value="{{$input_staff->users->email}}" readonly="">                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Organization:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span><input  name="org" class="form-control"  type="text" value="{{$input_staff->DEPT}}" readonly>                                    
                        </div>
                        <p class="small text-danger"><strong>Please check the organization indicated in this field.</strong></p>
                    </div>
                </div>
                
                {{-- <div class="form-group">
                    <label for="" class="col-md-3 control-label">Contract Expiry Date:</label>
                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-exclamation-sign"></i></span><input  name="org" class="form-control"  type="text" value="{{ date('d M Y', strtotime($input_staff->contractDate))}}" readonly>                                    
                        </div>
                        <p class="small text-danger"><strong>Please check and verify the date indicated in this field.</strong></p>
                    </div>
                </div> --}}

                <div class="row">
                  <div class="col-md-12">
                    <table class="table">
                      <thead>
                        <th>Course</th>
                        <th>Language</th>
                        <th>Schedule</th>
                        <th>Is your staff available?</th>
                        
                      </thead>

                      <tbody>
                        @foreach($input_course as $course)                         
                          <tr>
                            <th>{{ $course->courses->Description }}</th>
                            <td>{{ $course->languages->name }}</td>
                            <td>
                              @if(empty($course->schedule))
                              null
                              @else
                              {{ $course->schedule->name }}
                              @endif
                            </td>
                            <td>
                              <div class="col-md-6">
                                <input id="decision1-{{ $course->CodeIndexID }}" name="decision-{{ $course->CodeIndexID }}" class="with-font dyes" type="radio" value="1" required="">
                                <label for="decision1-{{ $course->CodeIndexID }}" class="form-control-static">YES</label>
                              </div>
                              
                              <div class="col-md-6">
                                <input id="decision2-{{ $course->CodeIndexID }}" name="decision-{{ $course->CodeIndexID }}" class="with-font dno" type="radio" value="0" required="">
                                <label for="decision2-{{ $course->CodeIndexID }}" class="form-control-static">NO</label>
                              </div>
                            </td>
                          </tr>
                        @endforeach

                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- MAKE A DECISION SECTION -->
                <div class="alert alert-warning col-md-6 col-md-offset-3 text-center">
                  <strong>Warning!</strong> Once you have made your decision, it cannot be changed. The page will redirect you to the confirmation page once a decision has been submitted. Thank you for your kind attention. 
                </div>
{{--                 <div class="form-group col-md-12">
                    <label class="col-md-3   control-label">Do you approve the above enrolment?</label>

                      <div class="col-md-2">
                                <input id="decision1" name="decision" class="with-font dyes" type="radio" value="1" >
                                <label for="decision1" class="form-control-static">YES</label>
                      </div>
                      
                      <div class="col-md-2">
                                <input id="decision2" name="decision" class="with-font dno" type="radio" value="0">
                                <label for="decision2" class="form-control-static">NO</label>
                      </div>
                </div> --}}
                
                <div class="col-md-12 form-group">
                  <label class="col-md-3 control-label">Comment/Reason: <i>(optional)</i></label>
                  <div class="col-md-8 ">
                  <textarea name="mgr_comment" class="form-control" maxlength="3500"></textarea>
                  <p class="small text-danger"><strong>Please note that for transparency, the text written above will be included in the email notification sent to the student.</strong></p>
                  </div>
                </div>

                <div class="col-sm-5 col-sm-offset-5">
                  @component('form.modal')
                    @slot('buttonclass')
                      btn-primary
                    @endslot
                    @slot('buttonlabel')
                      Submit Decision
                    @endslot
                    @slot('title')
                      Confirmation
                    @endslot
                    @slot('body')
                      <p>Once you submit your decision, you agree that this is final and cannot be revoked.</p>
                      <p>Please double check your comment and decision before submitting. Thank you for your kind attention.</p>
                    @endslot
                    @slot('buttonoperation')
                      <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                      <button type="submit" class="btn btn-success button-prevent-multi-submit">Submit Decision</button>
                      <input type="hidden" name="_token" value="{{ Session::token() }}">
                      {{ method_field('PUT') }}
                    @endslot
                  @endcomponent
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop 
@section('scripts_code')

<script src="{{ asset('js/submit.js') }}"></script>     
<script src="{{ asset('js/bootstrap-maxlength.js') }}"></script>
<script>
  $(document).ready(function(){
    $('input[type=radio]').prop('checked',false);

    $('textarea').maxlength({
      alwaysShow: false,
      threshold: 500,
      warningClass: "label label-success",
      limitReachedClass: "label label-danger",
      separator: ' out of ',
      preText: 'Writing ',
      postText: ' chars.',
      validate: true
    });
  });
</script>
@stop