<div class="form-group col-sm-12">
    <div class="form-group col-sm-12 file-section">
        <div class="alert alert-default alert-block">
          <div class="small text-danger">
            <strong>Note: accepts pdf, doc, and docx files only. File size must less than 8MB.</strong>
          </div>
        
          <div class="form-group">
            <label for="identityfile">Upload Proof of Identity: (required)</label>
            <input name="identityfile" type="file" required="">
          </div>

          <div class="form-group">
            <label for="payfile">Upload Proof of Payment: (required)</label>
            <input name="payfile" type="file" required="">
          </div>  

          @include('file_attachment_field.contract-file-attachment')
          
        </div>
    </div>
</div>