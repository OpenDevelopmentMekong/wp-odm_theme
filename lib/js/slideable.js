(function($) {

	$(document).ready(slideUpAll);

	function slideUpAll() {
		$(".slideable").each(function( index ) {
			slideUp($(this));
		});
	}

	function slideUp(div) {
		var heading = div.find(":header");
		var content = div.find(".slideable-content");
		content.slideUp();
		heading.on("click", function(item){
			slideDown(div);
		});
	}

	function slideDown(div) {
		var heading = div.find(":header");
		var content = div.find(".slideable-content");
		content.slideDown();
		heading.on("click", function(item){
			slideUp(div);
		});
	}

})(jQuery);
