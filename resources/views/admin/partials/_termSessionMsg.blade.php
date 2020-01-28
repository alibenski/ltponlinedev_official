@if(Session::has('Term'))
<div class="alert alert-success col-sm-12">
    <h4><i class="icon fa fa-bullhorn fa-2x"></i>Reminder!</h4>
    <p>
        All <b>Term</b> fields are currently set to: <span class="badge" style="font-size: 150%;"><strong>{{ Session::get('Term') }}</strong></span>
    </p>
</div>
@else
<a href="{{ route('admin_dashboard') }}">
<div class="callout callout-danger col-sm-12">
    <h4>Warning!</h4>
    <p>
        <b>Term</b> is not set. Click here to set the Term field for this session.
    </p>
</div>
</a>
@endif