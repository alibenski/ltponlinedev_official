<div class="form-group{{ $errors->has('contractfile') ? 'is-invalid' : '' }}">
    <label for="contractfile" class="col-md-12 control-label">Copy of UN badge ID / Agency badge ID / Carte de légitimation <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i> required field</span></label>
    <div class="col-md-12">
    <input name="contractfile" type="file" class="col-md-12 form-control-static" required="required">
        @if ($errors->has('contractfile'))
            <span class="alert alert-danger help-block">
                <strong>{{ $errors->first('contractfile') }}</strong>
            </span>
        @endif
        <p class="small text-danger"><strong>File size must be less than 8MB</strong></p>
    </div>
</div>