<div class="col-md-12">
  <div class="dropdown">
      <select class="col-md-12 form-control select2-basic-single" style="width: 100%;" name="profile" autocomplete="off" required="">
          <option value="">--- Please Select ---</option>
          <option value="STF">Staff Member</option>
          <option value="INT">Intern</option>
          <option value="CON">Consultant</option>
          <option value="WAE">When Actually Employed</option>
          <option value="JPO">JPO</option>
          <option value="MSU">Staff of Permanent Mission</option>
          <option value="SPOUSE">Spouse of Staff from UN or Mission</option>
          <option value="RET">Retired UN Staff Member</option>
          <option value="SERV">Staff of Service Organizations in the Palais</option>
          <option value="NGO">Staff of UN-accredited NGO's</option>
          <option value="PRESS">Staff of UN Press Corps</option>
      </select>
  </div>

      @if ($errors->has('profile'))
          <span class="help-block">
              <strong>{{ $errors->first('profile') }}</strong>
          </span>
      @endif
</div>