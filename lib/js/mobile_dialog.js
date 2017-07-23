
(function($) {

	$(document).ready(initMobileDialogs);

	function initMobileDialogs() {
		$(".mobile-dialog").siblings().each(function( index ) {
			console.log($(this));
		});
    
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
