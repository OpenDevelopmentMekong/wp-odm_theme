
(function($) {

	$(document).ready(initMobileDialogs);

	function initMobileDialogs() {
	    
    $(".open-mobile-dialog").on('click',function( ) {
			$(".mobile-dialog").siblings().hide();
      $(".mobile-dialog").slideDown();
		});
    
    $(".close-mobile-dialog").on('click',function( ) {      
      $(".mobile-dialog").siblings().show();
      $(".mobile-dialog").hide();
		});
	}

})(jQuery);
