<div class="form-group col-sm-12">
    <label>Organization</label>
    <div class="col-sm-12">
      <div class="dropdown">
        <select id="input" name="DEPT" class="col-md-8 form-control select2-basic-single" style="width: 100%;" required="">
        @if(!empty($org))
            <option value="">Select</option>
            @foreach($org as $value)
            <option value="{{ $value['Org name'] }}">{{ $value['Org name'] }} - {{ $value['Org Full Name'] }}</option>
            @endforeach
        @endif
        </select>
      </div>
    </div>
</div>