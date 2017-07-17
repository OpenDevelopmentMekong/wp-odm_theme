
(function($) {

	$(document).ready(function(){
    $("iframe").each(function( index ) {
			$(this).find("#embed-nav").hide();
      $(this).find("#rerun-button").hide();
		});
  });
  
})
