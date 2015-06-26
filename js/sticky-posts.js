(function($) {
	jeo.markersReady(function(map) {

		var t;

		function openSticky(postid) {

			var item = $('.sticky-posts-active .sticky-item[data-postid="' + postid + '"]');

			map.markers.focusMarker('post-' + postid);

			$('.sticky-posts-active .sticky-item').removeClass('active');
			$('.sticky-posts-active').addClass('post-active');
			item.addClass('active');

			adjustImageSize();

		}

		function closeSticky() {
			$('.sticky-posts-active').removeClass('post-active');
			$('.sticky-posts-active .sticky-item').removeClass('active');

			adjustImageSize();
		}

		function runSticky() {

			var current = $('.sticky-posts-active .sticky-item.active');

			if(!current.length) {
				var toGo = $('.sticky-posts-active .sticky-item:first-child');
			} else {
				if(current.is(':last-child'))
					var toGo = $('.sticky-posts-active .sticky-item:first-child');
				else
					var toGo = current.next('.sticky-item');
			}

			openSticky(toGo.data('postid'));

		}

		function adjustImageSize() {

			var opened = $('.sticky-posts-active .sticky-item.active img');
			var closed = $('.sticky-posts-active .sticky-item img');

			if(opened.length) {
				//opened.attr('style', '');
			}

			closed.each(function() {

				var img = $(this);

				if(!img.parents('.sticky-item').is('.active')) {

					var imgT = setInterval(function() {
						img.css({
							//width: img.parents('.sticky-item').innerHeight()-30,
							//height: img.parents('.sticky-item').innerHeight()-30
							width: 50,
							height: 50
						});
					}, 10);

					setTimeout(function() {
						clearInterval(imgT);
					}, 250);

				} else {
					img.attr('style', '');
				}

			});

		}

		$('.sticky-posts .sticky-item').click(function() {
			clearInterval(t);
			if(!$(this).is('.active')) {
				openSticky($(this).data('postid'));
				return false;
			} else {
				window.location = $(this).find('.link').attr('href');
			}
		});

		if($('.sticky-posts-active').length) {

			jeo.markerOpened(function() {
				clearInterval(t);
				closeSticky();
			});

			$('.sticky-posts-active .sticky-item').each(function() {

				var $item = $(this);
				$item.data('shareUrl', $(this).find('.share-button').attr('href'));
				$item.find('.share-button').attr('href', $item.data('shareUrl') + '&map_id=' + map.currentMapID);

			});

			jeo.groupChanged(function(group) {

				$('.sticky-posts-active .sticky-item').each(function() {
					var $item = $(this);
					$item.find('.share-button').attr('href', $item.data('shareUrl') + '&map_id=' + group.currentMapID);
				});

			});

			map.on('click mouseup', function() {
				clearInterval(t);
			});

			setTimeout(function() {
				openSticky($('.sticky-posts-active .sticky-item:first-child').data('postid'));
				t = setInterval(runSticky, 6000);
			}, 800);       
		}

	}); 

})(jQuery);