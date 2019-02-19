@if(Session::has('Term'))
<input type="hidden" name="term_id" value="{{ Session::get('Term') }}">
<div class="form-group input-group col-sm-12">
    <div class="form-group col-sm-12">
      <label for="search" class="control-label">Search by Name:</label>
      <input type="text" name="search" class="form-control" placeholder="Enter name here">
    </div>

    <div class="form-group col-sm-12">
      <label for="L" class="control-label"> Language:</label>
      <div class="col-sm-12">
        @foreach ($languages as $id => $name)
        <div class="col-sm-4">
            <div class="input-group"> 
              <span class="input-group-addon">       
                <input type="radio" name="L" value="{{ $id }}" >                 
              </span>
                <label type="text" class="form-control">{{ $name }}</label>
            </div>
        </div>
        @endforeach 
      </div>
    </div>
    
    <div class="form-group">           
      <label for="organization" class="col-md-12 control-label"> Organization:</label>
      <div class="form-group col-sm-12">
        <div class="dropdown">
          <select id="input" name="DEPT" class="col-md-10 form-control select2-basic-single" style="width: 100%;">
            @if(!empty($org))
              @foreach($org as $value)
                <option></option>
                <option value="{{ $value['Org Name'] }}">{{ $value['Org Name'] }} - {{ $value['Org Full Name'] }}</option>
              @endforeach
            @endif
          </select>
        </div>
      </div>
    </div>
    
    @if(!Request::is('admin/selfpayform*'))
    <div class="form-group col-sm-12">
      <label for="is_self_pay_form" class="control-label"> Additional Filters:</label>
      <div class="col-sm-12">
        
        <div class="col-sm-4">
            <div class="input-group"> 
              <span class="input-group-addon">       
                <input type="checkbox" name="is_self_pay_form" value=1 >                 
              </span>
                <label type="text" class="form-control">View Payment-based Forms Only</label>
            </div>
        </div>
        
      </div>
    </div>
    @endif

</div> <!-- end filter div -->

<div class="form-group">           
                <button type="submit" class="btn btn-success filter-submit-btn">Submit</button>
@else
@endif