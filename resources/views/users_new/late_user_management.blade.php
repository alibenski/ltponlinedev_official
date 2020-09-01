@extends('admin.admin')

@section('content')

<div>
    <form class="text-center">
        <label for="email">Enter email</label>
        <input type="text" id="email" name="email" required>
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        <button id="generateLink" type="button">Generate and Send Link</button>
    </form>
    
    <input class="url-link col-md-12">
</div>


@stop

@section('java_script')
<script>
    $(document).ready(function() {
        $("button#generateLink").click(function () {
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
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });  

                return true;
            } 
            
            return alert('email address missing');
        });
    });
</script>
@stop