(function($) {
	$(document).ready(function(){
		$('.view-enlarge').click(function(){
				$(this).siblings('.popup-overlay').show();
		});

		$('.popup-overlay .enlarge-close').click(function(){
			$('.popup-overlay').hide();
		});
	});

 })(jQuery);
