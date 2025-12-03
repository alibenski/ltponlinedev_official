<div class="col-md-12">
  <div class="dropdown">
      <select id="profile" class="col-md-12 form-control select-profile-single" style="width: 100%;" name="profile" autocomplete="off" required="required">
          <option value="">--- Please Select ---</option>
            @if(!empty($profiles))
                @foreach($profiles as $value)
                <option value="{{ $value->code }}">{{ $value->name }}</option>
                @endforeach
            @else
                <option value="STF">staff member</option>
                <option value="INT">intern</option>
                <option value="CON">consultant</option>
                <option value="WAE">when actually employed</option>
                <option value="JPO">JPO</option>
                {{-- <option value="MSU">staff of a permanent mission</option> --}}
                <option value="SPOUSE">spouse of UN staff members or spouse of staff of permanent missions</option>
                <option value="RET">retired UN staff member</option>
                <option value="FSTF">former staff member [non-retired]</option>
                {{-- <option value="SERV">employee of service providers in the Palais des Nations</option>
                <option value="NGO">staff of UN-accredited NGO's</option>
                <option value="PRESS">staff of UN-accredited press corps</option> --}}
            @endif
      </select>
  </div>

      @if ($errors->has('profile'))
          <span class="help-block">
              <strong>{{ $errors->first('profile') }}</strong>
          </span>
      @endif
</div>