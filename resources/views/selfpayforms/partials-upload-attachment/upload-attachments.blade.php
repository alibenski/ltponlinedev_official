			  <div class="form-check">
				<input class="form-check-input" type="checkbox" id="idCheck1">
				<label class="form-check-label" for="idCheck1">
					Upload IDs
				</label>

				<div class="show-id-attach-section" hidden>
					@include('file_attachment_field.id-file-attachment')
				</div>

			  </div>

			  <div class="form-check">
				<input class="form-check-input" type="checkbox" id="paymentCheck1">
				<label class="form-check-label" for="paymentCheck1">
					Upload Payment Proof
				</label>

				<div class="show-payment-attach-section" hidden>
					@include('file_attachment_field.payment-file-attachment')
				</div>

			  </div>

			  <div class="form-check">
				<input class="form-check-input" type="checkbox" id="contractCheck1">
				<label class="form-check-label" for="idCheck1">
					Upload Contract
				</label>

				<div class="show-contract-attach-section" hidden>
					@include('file_attachment_field.contract-file-attachment')
				</div>

			  </div>

			  <div class="form-check">
				<input class="form-check-input" type="checkbox" id="additionalCheck1">
				<label class="form-check-label" for="idCheck1">
					Upload Additional Document
				</label>

				<div class="show-additional-attach-section" hidden>
					@include('file_attachment_field.multiple-file-attachment')
				</div>

			  </div>
                          
              <div class="col-md-4 col-md-offset-4">
                  <button type="submit" class="btn btn-success btn-block" disabled>Submit Files</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
				  {{ method_field('PUT') }}
              </div>