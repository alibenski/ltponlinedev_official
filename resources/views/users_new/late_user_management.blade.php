@extends('admin.admin')

@section('content')

<div>
    <form class="text-left">
        <div class="form-group">
            <label for="email" class="col-md-12">Enter email:</label>
            <div class="col-md-12">
            <input type="text" id="email" name="email" class="col-md-6" autofocus required>
            </div>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <div class="col-md-12" style="margin: 1em 0">
                <button id="generateLink" type="button" class="btn btn-success">Generate and Send Link</button>
            </div>
        </div>
    </form>
    
    <div class="form-group">
        <label for="email" class="col-md-12">Generated Link:</label>
        <div class="col-md-12">
        <input class="url-link col-md-12">
        </div>
    </div>
</div>


@stop

@section('java_script')
<script>
    $(document).ready(function() {
        $("button#generateLink").click(function () {
            $(this).attr("disabled", true);
            const email = $("input#email").val();
            const token = $("input[name='_token']").val();
            
            if (email) {
                $.ajax({
                    url: '{{ route('generate-URL') }}',
                    type: 'POST',
                    data: {email:email, _token:token},
                })
                .done(function(data) {
                    $("input#email").val('');
                    $("input.url-link").val(data);
                    $("button#generateLink").removeAttr("disabled");
                })
                .fail(function() {
                    console.log("error");
                    $("button#generateLink").removeAttr("disabled");
                })
                .always(function() {
                    console.log("complete");
                });  

                return true;
            } 
            $(this).removeAttr("disabled");
            return alert('email address missing');
        });
    });
</script>
@stop