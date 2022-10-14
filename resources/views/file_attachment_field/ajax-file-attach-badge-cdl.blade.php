<div class="form-group{{ $errors->has('contractfile') ? 'is-invalid' : '' }}">
    <label for="contractfile" class="col-md-12 control-label"><strong>Please provide a copy of either of the following:</strong> <span style="color: red"><i class="fa fa-asterisk" aria-hidden="true"></i> required field</span> <br />UN agency badge or <br/>contract or <br/>carte de légitimation </label>
    <div class="col-md-12">
    <input name="contractfile" type="file" class="col-md-12 form-control-static" required="required">
        @if ($errors->has('contractfile'))
            <span class="alert alert-danger help-block">
                <strong>{{ $errors->first('contractfile') }}</strong>
            </span>
        @endif
        <p class="small text-danger"><strong>File size must be less than 8MB <br/>Only accepts files with pdf, doc, docx extensions</strong></p>
    </div>
</div>