@extends('public')
@section('tabtitle', '| Learning Partner Approval Page')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">

        <div class="panel-heading">CLM Learning Partner Approval Page Enrolment Form for Semester: <strong>{{ $next_term_code }}</strong></div>
          <div class="panel-body">
            <form method="POST" action="{{ route('approval.updateform2hr', [$input_staff->INDEXID, $input_staff->Te_Code]) }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <input  name="INDEXID" type="hidden" value="{{$input_staff->INDEXID}}" readonly>
                <input  name="Te_Code" type="hidden" value="{{$input_staff->Te_Code}}" readonly>
                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Staff Member Name:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="" class="form-control"  type="text" value="{{$input_staff->users->name}}" readonly>                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-md-3 control-label">Staff Member's Email Address:</label>
                    
                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="email" placeholder="" class="form-control"  type="text" value="{{$input_staff->users->email}}"readonly="">                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-3 control-label">Organization:</label>

                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span><input  name="" class="form-control"  type="text" value="{{$input_staff->DEPT}}" readonly>                                    
                        </div>
                        <p class="small text-danger"><strong>Please check the organization indicated in this field. Kindly take note that the organization in this field will be billed for this course (when necessary). Please refer to <a href="#">STI Policy blah blah</a> for more details.</strong></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-md-3 control-label">Manager/supervisor's Email Address:</label>
                    
                    <div class="col-md-8 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="email" placeholder="" class="form-control"  type="text" value="{{$input_staff->mgr_email}}"readonly="">                                    
                        </div>
                        <p class="small text-danger"><strong>This is the email address of the manager/supervisor who has approved the enrolment.</strong></p>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <table class="table">
                      <thead>
                        <th>Title A</th>
                        <th>Title B</th>
                        <th>Title C</th>
                        <th>Title D</th>
                        
                      </thead>

                      <tbody>
                        @foreach($input_course as $course)                         
                          <tr>
                            <th>{{ $course->CodeIndexID }}</th>
                            <td>{{ $course->courses->Description }}</td>
                            <td>{{ $course->languages->name }}</td>
                            <td>
                              @if(empty($course->schedule))
                              null
                              @else
                              {{ $course->schedule->name }}
                              @endif
                            </td>
                          </tr>
                        @endforeach

                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- MAKE A DECISION SECTION -->
                <div class="alert alert-warning" style="text-align: center">
                  <strong>Warning!</strong> Once you have made your decision, it cannot be changed. The page will redirect you to the confirmation page once a decision has been submitted. Thank you for your kind attention. 
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Do you approve the above enrolment?</label>

                      <div class="col-md-2">
                                <input id="decision1" name="decisionhr" class="with-font dyes" type="radio" value="1" >
                                <label for="decision1" class="form-control-static">YES</label>
                      </div>

                      <div class="col-md-2">
                                <input id="decision2" name="decisionhr" class="with-font dno" type="radio" value="0">
                                <label for="decision2" class="form-control-static">NO</label>
                      </div>
                </div>

                <div class="form-group">
                  <label class="col-md-3 control-label">Comment/Reason: <i>(optional)</i></label>
                  <div class="col-md-8 ">
                  <textarea name="hr_comment" class="form-control"></textarea>
                  <p class="small text-danger"><strong>Please note that for transparency, the text written above will be included in the email notification sent to the staff member.</strong></p>
                  </div>
                </div>

                <div class="col-sm-offset-5">
                  @component('form.modal')
                    @slot('title')
                      Confirmation
                    @endslot
                    @slot('body')
                      <p>Once you submit your decision, you agree to the ST/AI/ policy that...</p>
                      <p>Please double check your decision as this implicates possible billing to your organization. It never hurts to be sure. Thank you for your kind attention.</p>
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

<script>
  $(document).ready(function(){
    $('input[type=radio]').prop('checked',false);
  });
</script>
@stop