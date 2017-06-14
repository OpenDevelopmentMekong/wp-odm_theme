(function($) {

	/*
	 * Build summary
	 */

	$(document).ready(build);

	var container;
	var hItems;

	function build() {
		var content =  $('#content .post-content');
		var tags = ['h1','h2','h3','h4','h5','h6'];
		container = $('.table-of-contents');
		hItems = content.find('.summary-item');
		if (hItems.length > 2){ //odm
			hItems.each(function(index) {
				$(this).attr('data-count', index);
			});
			console.log(hItems);
			container.append('<ol/>');
			hItems.each(function() {
				if($(this).is('.summary-item')) {
					var item = $('<li/>').addClass($(this).attr('id') + ' ' + $(this).attr('class'));
          var clone = $(this).find('a').clone();
          clone.text($(this).find('a').attr('title'));
					item.append(clone);
					container.find('ol').append(item);
				}
			});
		}else{ // odm
			$('#content .odm-summary').parent('li').hide();
		}
	}

})(jQuery);
