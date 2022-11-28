<div class="form-group row">
    <label for="contract-date" class="col-md-4 col-form-label">Contract Date:</label>
    
    @if (is_null($student->contract_date)) 
        <form id="updateContractDate" method="POST" action="{{ route('user-update-contract') }}" class="form-horizontal">
            
            <input id="userId" type="hidden" name="id" value="{{ $student->id }}" />
            
            @include('contract_field.contract-file-field')

            <div class="col-sm-12">
                <button type="submit" class="btn btn-success btn-block">Submit</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                {{ method_field('PUT') }}
            </div>
        </form>
    @else
        <div class="col-md-8 font-weight-bold">
            <input type="text" readonly class="form-control-plaintext" id="staticId" value="{{ $student->contract_date }}">
        </div>
    @endif

</div>