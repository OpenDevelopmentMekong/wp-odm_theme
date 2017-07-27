
(function($) {

	$(document).ready(initMobileDialogs);

	function initMobileDialogs() {

		$(".mobile-dialog").siblings().each(function(index){
			if ($(this).css('display') !== 'none'){
				$(this).addClass('hide-on-mobile-dialog')
			}
		});

    $(".open-mobile-dialog").on('click',function( ) {
			$(".hide-on-mobile-dialog").each(function(index){
				$(this).hide();
			});
			var open_mobile_dialog_id = (typeof $(this).attr("id") != 'undefined')? $(this).attr('id') : null;
			if(open_mobile_dialog_id){
	      $("."+open_mobile_dialog_id+".mobile-dialog").css("display","block");
			}else{
		    $(".mobile-dialog").css("display","block");
			}
		});

    $(".close-mobile-dialog").on('click',function( ) {
			$(".hide-on-mobile-dialog").each(function(index){
				$(this).show();
			});
      $(".mobile-dialog").hide();
		});
	}

})(jQuery);
