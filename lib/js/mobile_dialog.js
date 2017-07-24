
(function($) {

	$(document).ready(initMobileDialogs);
	
	var visibleSiblings = [];	

	function initMobileDialogs() {
		
		$(".mobile-dialog").siblings().each(function(index){
			if ($(this).css('display') !== 'none'){
				visibleSiblings.push($(this));
			}			
		});

    $(".open-mobile-dialog").on('click',function( ) {
			visibleSiblings.hide();
      $(".mobile-dialog").slideDown();
		});

    $(".close-mobile-dialog").on('click',function( ) {
      visibleSiblings.show();
      $(".mobile-dialog").hide();
		});
	}

})(jQuery);
