(function($) {
	$(document).ready(function(){
		$('.view-enlarge').click(function(){
				$('.popup-overlay').show();
		});

		$('.popup-overlay .enlarge-close').click(function(){
			$('.popup-overlay').hide();
		});
	});

 })(jQuery);
