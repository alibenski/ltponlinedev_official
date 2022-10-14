<div class="col-md-12">
    <div class="col-md-12 my-3 border">
        <p>Applicant's UN badge or carte de légitimation</p>
        <input name="contractfile" type="file" class="col-md-12 form-control-static mb-1" required="required">
        @if ($errors->has('contractfile'))
            <span class="alert alert-danger help-block">
                <strong>{{ $errors->first('contractfile') }}</strong>
            </span>
        @endif
    </div>
    <p class="small text-danger"><strong>File size must be less than 8MB <br/>Only accepts files with pdf, doc, docx extensions</strong></p>
</div>
