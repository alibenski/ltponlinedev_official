@if(Session::has('Term'))
<div class="alert alert-success col-sm-12 d-flex align-items-start">
    <i class="fas fa-bullhorn fa-2x mr-3"></i>
    <div>
        <h4 class="mb-1">Reminder!</h4>
        <p class="mb-0">
            All <strong>Term</strong> fields are currently set to:
            <span class="badge badge-primary" style="font-size:150%;">
                <strong>{{ Session::get('Term') }}</strong>
            </span>
        </p>
    </div>
</div>
@else
<a href="{{ route('admin_dashboard') }}" class="text-reset text-decoration-none">
    <div class="callout callout-danger col-sm-12">
        <h4 class="mb-1">Warning!</h4>
        <p class="mb-0">
            <strong>Term</strong> is not set. Click here to set the Term field for this session.
        </p>
    </div>
</a>
@endif