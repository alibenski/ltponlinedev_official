@extends('public')
@section('tabtitle', '| Learning Partner Approval Page')
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
      <div class="card">

        <div class="card-header bg-default">CLM Learning Partner Approval Page Placement Test: <strong>{{ $next_term_name }}</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ route('approval.updateplacementformdata2hr', [$input_staff->INDEXID, $input_staff->L, $input_staff->eform_submit_count,$next_term_code]) }}" class="form-horizontal form-prevent-multi-submit">
                {{ csrf_field() }}
                <input  name="INDEXID" type="hidden" value="{{$input_staff->INDEXID}}" readonly>
                <input  name="L" type="hidden" value="{{$input_staff->L}}" readonly>
                <input  name="form_counter" type="hidden" value="{{$input_staff->eform_submit_count}}" readonly>

                <div class="form-group">
                    <label for="" class="col-md-12 control-label">Staff Member Name:</label>

                    <div class="col-md-12 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input  name="" class="form-control"  type="text" value="{{$input_staff->users->name}}" readonly>                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-md-12 control-label">Staff Member's Email Address:</label>
                    
                    <div class="col-md-12 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span><input  name="email" placeholder="" class="form-control"  type="text" value="{{$input_staff->users->email}}"readonly="">                                    
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-12 control-label">Organization:</label>

                    <div class="col-md-12 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span><input  name="" class="form-control"  type="text" value="{{$input_staff->DEPT}}" readonly>                                    
                        </div>
                        <p class="small text-danger"><strong>Please check the organization indicated in this field.</strong></p>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <table class="table">
                      <thead>
                        <th>Language Course</th>
                        <th>Date Duration of Classes</th>
                        <th>Manager's Approval</th>
                      </thead>

                      <tbody>                       
                          <tr>
                            <th>{{ $input_staff->languages->name }}</th>
                            <td>{{ $next_term_name }}</td>
                            <td>
                              @if($input_staff->approval == 1)
                              <h5><span class="label label-success">Yes</span></h5>
                              @else
                              <h5><span class="label label-danger">No</span></h5>
                              @endif
                            </td>
                          </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- MAKE A DECISION SECTION -->
                <div class="alert alert-warning col-md-12 col-md-12 fset-3 text-center">
                  <strong>Warning!</strong> Once you have made your decision, it cannot be changed. The page will redirect you to the confirmation page once your decision is submitted.
                </div>
                <div class="form-group col-md-12 ">
                    <label class="col-md-12 control-label">Do you approve the request?</label>

                      <div class="col-md-3" >
                                <input id="decision1" name="decisionhr" class="with-font dyes" type="radio" value="1" >
                                <label for="decision1" class="form-control-static">YES</label>
                      </div>

                      <div class="col-md-3" >
                                <input id="decision2" name="decisionhr" class="with-font dno" type="radio" value="0">
                                <label for="decision2" class="form-control-static">NO</label>
                      </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12 control-label">Comment/Reason: <i>(optional)</i></label>
                  <div class="col-md-12 ">
                  <textarea name="hr_comment" class="form-control" maxlength="3500"></textarea>
                  <p class="small text-danger"><strong>Please note that for transparency, the text written above will be included in the email notification sent to the staff member.</strong></p>
                  </div>
                </div>

                <div class="offset-sm-5">
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
                      <p>Please double check before submitting your decision as this may implicate your organization being billed. Once you have submitted your decision, you agree that this is final and cannot be revoked. </p>
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