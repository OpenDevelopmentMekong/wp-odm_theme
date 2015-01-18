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
		hItems.each(function(index) {
			$(this).attr('data-count', index);
		});
		container.append('<ol/>');
		$.each(tags, function(i, tag) {
			var cTag = content.find(tag);
			if(cTag.length) {
				cTag.each(function() {
					if($(this).find('.summary-item')) {
						var item = $('<li/>').addClass($(this).attr('id') + ' ' + $(this).attr('class'));
						item.append($(this).find('a').clone());
						//item.append($('<span class="percentage" />'));
						container.find('ol').append(item);
					}
				});
			}
		});

		// $('.aside-item.toolkit-summary').followScroll({
		// 	startPadding: 40,
		// 	stopFollow: {
		// 		element: $('.post-content'),
		// 		stopAtEnd: true
		// 	}
		// });

		// $('#main-aside').followScroll({
		// 	startPadding: 40,
		// 	stopFollow: {
		// 		element: $('.post-content'),
		// 		stopAtEnd: true
		// 	}
		// });

		// imagesLoaded('body', function() {
		// 	$(window).scroll(follow).scroll();
		// });
	}

	function follow() {

		var scrollTop = $(window).scrollTop() + ($(window).height() / 3);

		hItems.each(function() {

			var tocItem = container.find('.' + $(this).attr('id'));

			var next = $('.summary-item[data-count="' + (parseInt($(this).data('count')) + 1) + '"]');

			if(scrollTop >= $(this).offset().top && (!next.length || scrollTop < next.offset().top)) {
	 			container.find('li').removeClass('active');
				tocItem.addClass('active');

			}

			var height;
			if(next.length) {
				height = next.offset().top - $(this).offset().top;
			} else {
				height = ($(this).parent().offset().top + $(this).parent().height()) - $(this).offset().top;
			}
			var percentage = (scrollTop - $(this).offset().top) / (($(this).offset().top+height) - $(this).offset().top);

			if(percentage <= 0)
				percentage = 0;
			else if(percentage >= 1)
				percentage = 1;

			tocItem.find('.percentage').css('width', Math.ceil(percentage*100) + '%');

		});

	}

	/*
	 * Hashchange
	 */

	 $(document).ready(function() {
		//$(window).hashchange(update).hashchange();
		//$(window).hashchange();
	});

	 function update() {
	 	if(location.hash) {
	 		var hash = location.hash.replace('#', '');
	 		$('.summary-item').removeClass('active');
	 		$('.summary-item.' + hash + ', #' + hash).addClass('active');
	 	}
	 }

})(jQuery);