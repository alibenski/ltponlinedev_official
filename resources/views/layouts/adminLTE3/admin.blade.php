@extends('layouts.adminLTE3.index')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<style>
#placeholder, #placeholder2 {
    width: 100%;
    height: 300px;
}
</style>
@stop

@section('content')

<div class="container-fluid">

    <p id="demo"></p>

    <div class="row">
        @include('admin.partials._termSessionMsg')
    </div>

    <div class="preloader hidden"></div>

    @include('admin.partials._dropdownSetSessionTerm')

    <div class="mb-3">
        @if(Session::has('Term'))
            {{ $enrolment_arabic_count }} enrolment forms in Arabic
        @else
            Set the Term
        @endif
    </div>

    <!-- ================= FIRST ROW ================= -->
    <div class="row">

        <!-- LEFT COLUMN -->
        <div class="col-md-6">

            <!-- Regular Enrolment -->
            <a href="{{ route('preenrolment.index') }}">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Regular Enrolment Forms</span>
                        <span class="info-box-number">
                            @if(Session::has('Term')) Submission Total: {{$enrolment_forms}} @else Set the Term @endif
                        </span>
                        <span class="info-box-text">Total number of submitted forms</span>
                        <span class="info-box-text">(approved + unapproved)</span>
                    </div>
                </div>
            </a>

            <!-- Self Pay -->
            <a href="{{ route('selfpayform.index') }}">
                <div class="info-box bg-purple">
                    <span class="info-box-icon"><i class="fas fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Payment-based Enrolment Forms</span>
                        <span class="info-box-number">
                            @if(Session::has('Term')) Total: {{$selfpay_enrolment_forms}} @else Set the Term @endif
                        </span>
                        <span class="info-box-text">Validated <span class="badge badge-success">{{ $selfpay_enrolment_forms_validated }}</span></span>
                        <span class="info-box-text">Pending <span class="badge badge-warning">{{ $selfpay_enrolment_forms_pending }}</span></span>
                        <span class="info-box-text">Disapproved <span class="badge badge-danger">{{ $selfpay_enrolment_forms_disapproved }}</span></span>
                        <span class="info-box-text">Waiting <span class="badge badge-info">{{ $selfpay_enrolment_forms_waiting }}</span></span>
                    </div>
                </div>
            </a>

            <!-- All Unassigned -->
            @if(Session::has('Term')) 
            <a class="link-to-orphans" href="{{ route('query-orphan-forms-to-assign') }}">
                <div class="info-box bg-navy">
                    <span class="info-box-icon"><i class="fas fa-tasks"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Manage ALL Unassigned Enrolment Forms</span>
                        <span class="info-box-number">{{$all_unassigned_enrolment_form}} Form(s)</span>
                        <small>Shows ALL APPROVED regular enrolment forms</small>
                    </div>
                </div>
            </a>
            @else
            <div class="info-box bg-navy">
                <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Manage ALL Unassigned Enrolment Forms</span>
                    <span class="info-box-number">Set the Term</span>
                </div>
            </div>
            @endif

            <!-- Unassigned -->
            @if(Session::has('Term')) 
            <a href="{{ route('query-regular-forms-to-assign') }}">
                <div class="info-box bg-teal">
                    <span class="info-box-icon"><i class="fas fa-files-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Manage Unassigned Enrolment Forms</span>
                        <span class="info-box-number">{{$arr3_count}} forms</span>
                        <small>Students not yet in a class</small>
                    </div>
                </div>
            </a>
            @else
            <div class="info-box bg-teal">
                <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Manage Unassigned Enrolment Forms</span>
                    <span class="info-box-number">Set the Term</span>
                </div>
            </div>
            @endif

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">

            <!-- Placement -->
            <a href="{{ route('placement-form.index') }}">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Placement Forms</span>
                        <span class="info-box-number">
                            @if(Session::has('Term')) {{$placement_forms}} @else Set the Term @endif
                        </span>
                    </div>
                </div>
            </a>

            <!-- Placement Self Pay -->
            <a href="{{ route('index-placement-selfpay') }}">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Payment-based Placement Forms</span>
                        <span class="info-box-number">
                            @if(Session::has('Term')) {{$selfpay_placement_forms}} @else Set the Term @endif
                        </span>
                    </div>
                </div>
            </a>

            <!-- Non Assigned Placement -->
            @if(Session::has('Term')) 
            <a href="{{ route('placement-form-filtered') }}">
                <div class="info-box bg-orange">
                    <span class="info-box-icon"><i class="fas fa-files-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Non-assigned Placement</span>
                        <span class="info-box-number">{{$countNonAssignedPlacement}}</span>
                    </div>
                </div>
            </a>
            @else
            <div class="info-box bg-orange">
                <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Non-assigned Placement</span>
                    <span class="info-box-number">Set the Term</span>
                </div>
            </div>
            @endif

        </div>

    </div>

    <!-- ================= SECOND ROW ================= -->
    <div class="row">

        <!-- Modified forms not imported -->
        <div class="col-md-3 students-not-in-class hidden">
            <a href="{{ route('fully-approved-forms-not-in-class') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Modified forms not imported</span>
                        <span class="info-box-number">
                            @if(Session::has('Term')) {{count($merge)}} @else Set the Term @endif
                        </span>
                        <small>Fully approved but not imported</small>
                    </div>
                </div>
            </a>
        </div>

        <!-- Reimbursement -->
        <div class="col-md-3 cancelled-selfpayment hidden">
            <a href="{{ route('waitlisted-and-valid-cancelled-forms-view') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-navy"><i class="fas fa-money-bill"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Reimbursement / Carry-Over</span>
                        <span class="info-box-number"></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- New users -->
        <div class="col-md-3">
            <a href="{{ route('newuser.index') }}">
                <div class="info-box">
                    <span class="info-box-icon {{ $new_user_count < 5 ? 'bg-navy' : 'bg-success' }}">
                        <i class="fas {{ $new_user_count < 5 ? 'fa-users' : 'fa-user' }}"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            {{ $new_user_count < 5 ? 'New User Access Request' : 'New User Request' }}
                        </span>
                        <span class="info-box-number">{{ $new_user_count }}</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Preview -->
        <div class="col-md-3">
            <a href="{{ route('preview-merged-forms') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-navy"><i class="fas fa-eye"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Preview</span>
                        <span class="info-box-number"></span>
                        <small>Before batch run</small>
                    </div>
                </div>
            </a>
        </div>

        <!-- Manage Classes -->
        <div class="col-md-3">
            <a href="{{ route('preview-vsa-page-2') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-navy"><i class="fas fa-cogs"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Manage Classes</span>
                        <span class="info-box-number"></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Waitlisted -->
        <div class="col-md-3">
            @if(Session::has('Term')) 
            <a href="{{ route('preview-waitlisted') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-navy"><i class="fas fa-exclamation-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Waitlisted Students</span>
                    </div>
                </div>
            </a>
            @else
            <div class="info-box">
                <span class="info-box-icon bg-navy"><i class="fas fa-exclamation-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Waitlisted Students</span>
                    <span class="info-box-number">Set the Term</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Cancelled -->
        <div class="col-md-3">
            <a href="{{ route('cancelled-convocation-view') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cancelled Convocations</span>
                        <span class="info-box-number">
                            @if(Session::has('Term')){{$cancelled_convocations}} @else Set the Term @endif
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Class Table -->
        <div class="col-md-3">
            <a href="{{ route('preview-class-status') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-navy"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Class Table</span>
                        <span class="info-box-number">@if(Session::has('Term')) @else Set the Term @endif</span>
                        <small>Students & cancellations</small>
                    </div>
                </div>
            </a>
        </div>

        <!-- No Show -->
        <div class="col-md-3">
            <a href="{{ route('no-show-list') }}">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-hand-paper"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">No-Show List</span>
                        <span class="info-box-number">@if(Session::has('Term')) @else Set the Term @endif</span>
                        <small>Marked as no-show</small>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

<input type="hidden" name="_token" value="{{ Session::token() }}">
	@if ($term_for_timer)	
		<input type="hidden" name="termForCounter" value="{{ date('M d, Y H:i:s', strtotime($term_for_timer->Enrol_Date_Begin )) }}">
		<input type="hidden" name="termCodeForCounter" value="{{ $term_for_timer->Term_Code }}">
	@else
		<input type="hidden" name="termForCounter" value="Nov 4, 2020 08:00:00">
		<input type="hidden" name="termCodeForCounter" value="">
	@endif
@endsection

@section('java_script')
<script src="{{ asset('js/submit.js') }}"></script>
<script src="{{ asset('js/select2.full.js') }}"></script>
<script src="{{ asset('js/countDownTimer.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2-basic-single').select2({
    placeholder: "select here",
    });

    $("a.link-to-orphans").not('[target="_blank"]').click(function() {
    	$(".preloader").removeClass('hidden');
    });

    var Term = "{{ Session::get('Term') }}";
    var token = $("input[name='_token']").val();
    console.log(Term)

    $.ajax({
    	url: '{{ route('ajax-check-batch-has-ran') }}',
    	type: 'GET',
    	data: {Term:Term,_token: token},
    })
    .done(function(data) {
    	if (!jQuery.isEmptyObject( data )) {
    		$(".students-not-in-class").removeClass('hidden');
    		$(".cancelled-selfpayment").removeClass('hidden');
    	}

    })
    .fail(function() {
    	console.log("error");
    })
    .always(function() {
    	console.log("complete check if batch has ran");
    });
});
</script>

@stop