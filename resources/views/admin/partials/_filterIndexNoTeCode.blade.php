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
                <option value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{ $value['Org Full Name'] }}</option>
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
                <input type="checkbox" name="not_assigned" value=1 >                 
              </span>
                <label type="text" class="form-control bg-red">View Non-Assigned Placement Forms Only</label>
            </div>
        </div>

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

    <div class="form-group col-sm-12">
      <label for="pending" class="control-label"> Filters for Pending Placement Forms:</label>
      <div class="col-sm-12">
        <div class="col-sm-4">
            <div class="input-group"> 
              <span class="input-group-addon">       
                <input type="checkbox" name="selfpay_approval" value=2 >                 
              </span>
                <label type="text" class="form-control bg-yellow">View Pending Payment-based Forms</label>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="input-group"> 
              <span class="input-group-addon">       
                <input type="checkbox" name="pending_approval_hr" value=1 >                 
              </span>
                <label type="text" class="form-control">View HR-Pending Placement Forms</label>
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

<script type="text/javascript">
  $("input[name='pending_approval_hr']").on("click", function(){
    console.log("ching")
    if ($(this).is(":checked", true)) {
      $("input[type='checkbox']").not("input[name='pending_approval_hr']").prop("disabled", true);
    } else {
      $("input[type='checkbox']").prop("disabled", false);
    }
  }); 

  $("input[name='selfpay_approval']").on("click", function(){
      
  }); 
</script>