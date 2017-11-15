//submit button disable after submit data
(function(){
	$('.form-prevent-multi-submit').on('submit', function() {
		$('.button-prevent-multi-submit').attr('disabled', 'true');
	})
})();
//show and hide jquery
$(document).ready(function(){
    $('input:radio[name="decision"]').change(function(){
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide(200);
        $(targetBox).show(500);
    });
});