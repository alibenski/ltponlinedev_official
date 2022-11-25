<div class="form-group row">
    <div class="col-md-12">
        <label for="contract-date" class="col-md-4 col-form-label">Contract Date:</label>

            <form id="" method="POST" action="{{ route('user-update-contract') }}" class="form-horizontal contract-form">
               
                <input id="userIdModal" type="hidden" name="id" value="" />
                
                @include('contract_field.contract-file-field')
    
                <div class="col-md-4 offset-md-4">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                    {{ method_field('PUT') }}
                </div>
            </form>

    </div>
</div>