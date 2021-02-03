<div class="col-md-12">
<div class="form-group row">
    <label for="countryMission" class="col-md-12 control-label text-danger">Country of Mission: </label>
    
    <div class="col-md-12">
      <div class="dropdown">
        <select class="col-md-12 form-control select2-basic-single" style="width: 100%;" name="countryMission" autocomplete="off" >
            <option value="">--- Select Country ---</option>
            @if(!empty($countries))
            @foreach($countries as $value)
            <option class="col-md-8 wx" value="{{ $value->id }}">{{ $value->ABBRV_NAME }}</option>
            @endforeach
            @endif
        </select>
      </div>
    </div>
</div>
</div>