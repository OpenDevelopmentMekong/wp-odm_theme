const MAX_LENGTH = 200;

(function($) {

	$(document).ready(collapseAll);
	
	function collapseAll() {
		$(".expandible").each(function( index ) {
			collapse($(this));
		});
	}
	
	function collapse(paragraph) {
		var contents = paragraph.html();		
		if (contents.indexOf('<a class="collapse"> Less...</a> ') > -1){
			contents.replace('<a class="collapse"> Less...</a> ','');
		}
		if (contents.length > MAX_LENGTH){
			var choppedContents = contents.substring(0, MAX_LENGTH);				
			var showMore = $('<a class="collapse"> More...</a> ');
			showMore.on('click', function(e){				
				expand(paragraph,contents);				
			});
			paragraph.html(choppedContents);
			paragraph.append(showMore);
		}
	}
	
	function expand(paragraph,allContents) {		
		var showLess = $('<a class="collapse"> Less...</a> ');
		showLess.on('click', function(e){
			collapse(paragraph);
		});		
		paragraph.html(allContents);
		paragraph.append(showLess);
	}

})(jQuery);
