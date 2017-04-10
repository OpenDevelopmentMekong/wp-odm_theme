(function($) {
	$(document).ready(function(){
		$('.view-enlarge').click(function(){
				$(this).siblings('.popup-overlay').show();
		});

		$('.popup-overlay .enlarge-close').click(function(){
			$('.popup-overlay').hide();
		});
	});

	$(document).keyup(function(e) {
	  if (e.keyCode == 27){
			$('.popup-overlay').hide();
		}
	});

})(jQuery);
