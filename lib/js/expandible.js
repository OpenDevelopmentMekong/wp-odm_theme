const MAX_LENGTH = 200;

(function($) {

	$(document).ready(collapseAll);

	function collapseAll() {
		$(".expandible").each(function( index ) {
			collapse($(this));
		});
	}

	function cut(n) {
    return function textCutter(i, text) {
        var short = text.substr(0, n);
        if (/^\S/.test(text.substr(n)))
            return short.replace(/\s+\S*$/, "");
        return short;
    };
	}

	function collapse(paragraph) {
		var contents = paragraph.html();
		if (contents.length > MAX_LENGTH){
			var choppedContents = cut(MAX_LENGTH);
			var showMore = $('<a class="collapse"> More...</a> ');
			showMore.on('click', function(e){
				if (contents.indexOf('<a class="collapse"> Less...</a>') > -1){
					contents = contents.replace('<a class="collapse"> Less...</a>','');
				}
				expand(paragraph,contents);
			});
			paragraph.html(choppedContents);
			paragraph.append(showMore);
		}
	}

	function expand(paragraph,allContents) {
		var showLess = $('<a class="collapse"> Less...</a>');
		showLess.on('click', function(e){
			collapse(paragraph);
		});
		paragraph.html(allContents);
		paragraph.append(showLess);
	}

})(jQuery);
