@extends('public')
@section('tabtitle', '| Manager Approval Page')
@section('customcss')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/submit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">

        <div class="panel-heading">Manager/Supervisor Placement Test Approval Page for Semester: <strong>{{ $next_term_code }} : {{ $next_term_name }}</strong></div>
          <div class="panel-body">
            <form method="POST" action="{{ route('approval.updateplacementformdata', [$input_staff->INDEXID, $input_staff->L, $input_staff->eform_submit_count]) }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <input  name="INDEXID" type="hidden" value="{{$input_staff->INDEXID}}" readonly>
                <input  name="L" type="hidden" value="{{$input_staff->L}}" readonly>
                <input  name="form_counter" type="hidden" value="{{$input_staff->eform_submit_count}}" readonly>

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
                        <div class="form-group">
{{--                           <div class="col-md-12">
                              <input id="confirmOrg" name="confirmOrg" class="with-font dyes" type="checkbox" value="1" required="required">
                              <label for="confirmOrg" class="form-control-static">YES, I confirm that the organization indicated above is correct. (required)</label>
                          </div> --}}
                          {{-- insert dropdown here call ajax-org-select.blade.php from student->edit --}}
                        </div>
                    </div>
                </div>
             
                <div class="row">
                  <div class="col-md-12">
                    <table class="table">
                      <thead>
                        <th>Language</th>
                        <th>Schedule</th>
                        <th>Do You Approve?</th>
                      </thead>

                      <tbody>                      
                          <tr>
                            <th>{{ $input_staff->languages->name }}</th>
                            <td>
                              @if($input_staff->L == 'F')
                              {{ date('d M Y', strtotime($input_staff->placementSchedule->date_of_plexam)) }} to {{ date('d M Y', strtotime($input_staff->placementSchedule->date_of_plexam_end)) }}
                              @else
                              {{ date('d M Y', strtotime($input_staff->placementSchedule->date_of_plexam)) }}
                              @endif
                            </td>
                            <td>
                              <div class="col-md-6">
                                <input id="decision1-{{ $input_staff->L }}" name="decision-{{ $input_staff->L }}" class="with-font dyes" type="radio" value="1" required="">
                                <label for="decision1-{{ $input_staff->L }}" class="form-control-static">YES</label>
                              </div>
                              
                              <div class="col-md-6">
                                <input id="decision2-{{ $input_staff->L }}" name="decision-{{ $input_staff->L }}" class="with-font dno" type="radio" value="0" required="">
                                <label for="decision2-{{ $input_staff->L }}" class="form-control-static">NO</label>
                              </div>
                            </td>
                          </tr>
                        

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
                  <p class="small text-danger"><strong>Please note that for transparency, the text written above will be included in the email notification sent to the staff member.</strong></p>
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