(function($) {

	$(document).ready(slideUpAll);

	function slideUpAll() {
		$(".slideable").each(function( index ) {
			slideUp($(this));
		});
	}

	function slideUp(div) {
		var slideArrow = $('<i class="fa fa-caret-down"></i>');
		var heading = div.find(":header").first();
		if (heading.html().indexOf('<i class="fa fa-caret-down"></i>') == -1){
			heading.append(" ");
			heading.append(slideArrow);
		}
		var content = div.find(".slideable-content");
		content.slideUp();
		heading.on('click', function(item){
			slideDown(div);
		});
	}

	function slideDown(div) {
		var heading = div.find(":header").first();
		var content = div.find(".slideable-content");
		content.slideDown();
		heading.off('click');
		heading.on('click', function(item){
			slideUp(div);
		});
	}

})(jQuery);
