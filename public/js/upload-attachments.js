$( document ).ready(function() {
	// strip required attr for all attachments upon doc load
	$("input[type='file']").removeAttr("required");
	
    $("input[type='checkbox']").on("click", function() {
		if($(this).is(":checked", true)){
			// show attachment upload if checked
			// include attr required to type="file"
			if ($(this).attr("id") === "idCheck1") {
				$(".show-id-attach-section").removeAttr("hidden");
				$("input[name='identityfile']").prop("required", true);
				$("input[name='identityfile2']").prop("required", true);
			}
			if ($(this).attr("id") === "paymentCheck1") {
				$(".show-payment-attach-section").removeAttr("hidden");
				$("input[name='payfile']").prop("required", true);
			}
			if ($(this).attr("id") === "contractCheck1") {
				$(".show-contract-attach-section").removeAttr("hidden");
				$("input[name='contractFile']").prop("required", true);
			}
			if ($(this).attr("id") === "additionalCheck1") {
				$(".show-additional-attach-section").removeAttr("hidden");
				$("input[name='addFile0']").prop("required", true);
			}			
			$("button[type='submit']").removeAttr("disabled");
		} else {
			if ($(this).attr("id") === "idCheck1") {
				$(".show-id-attach-section").prop("hidden", true);
				$("input[name='identityfile']").removeAttr("required");
				$("input[name='identityfile2']").removeAttr("required");
			}
			if ($(this).attr("id") === "paymentCheck1") {
				$(".show-payment-attach-section").prop("hidden", true);
				$("input[name='payfile']").removeAttr("required");
			}
			if ($(this).attr("id") === "contractCheck1") {
				$(".show-contract-attach-section").prop("hidden", true);
				$("input[name='contractFile']").removeAttr("required");
			}
			if ($(this).attr("id") === "additionalCheck1") {
				$(".show-additional-attach-section").prop("hidden", true);
				$("input[name='addFile0']").removeAttr("required");
			}
			$("button[type='submit']").prop("disabled", true);
		}

	})
});