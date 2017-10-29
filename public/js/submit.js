(function(){
	$('.form-prevent-multi-submit').on('submit', function() {
		$('.button-prevent-multi-submit').attr('disabled', 'true');
	})
})();