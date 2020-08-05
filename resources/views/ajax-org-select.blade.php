<div class="col-md-12">
<div class="form-group row card">
    <label for="organization" class="col-md-12 control-label text-danger">Change Organization to: </label>
    
    <div class="col-md-12">
      <div class="dropdown">
        <select class="col-md-12 form-control select2-basic-single" style="width: 100%;" name="organization" autocomplete="off" >
            <option value="">--- Please Select Organization ---</option>
            @if(!empty($select_org))
            @foreach($select_org as $value)
            <option class="col-md-8 wx" value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{ $value['Org Full Name'] }}</option>
            @endforeach
            @endif
        </select>
      </div>
      <p class="small text-danger"><strong>Please check that you select the correct Organization in this field. A wrong input may nullify your enrolment.</strong></p>
    </div>
</div>
</div>
