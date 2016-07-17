(function($) {
	jeo.markersReady(function(map) {

		var t;
        //var tab_sticky_posts_active  = ".sticky-posts-active " ;
        var tab_sticky_posts_active  = "" ;
		function openSticky(postid) {

			var item = $(tab_sticky_posts_active + '.sticky-item[data-postid="' + postid + '"]');

			map.markers.focusMarker('post-' + postid);

			$(tab_sticky_posts_active + '.sticky-item').removeClass('active');
			$('.sticky-posts-active').addClass('post-active');
			item.addClass('active');
			//$('.sticky-posts-active').height(($('.sticky-posts-active').height()));
			//item.height(($('.sticky-posts-active').height()));
            adjustPostItem_height($('.sticky-posts-active').height());
			//adjustImageSize();

		}

		function closeSticky() {
			$('.sticky-posts-active').removeClass('post-active');
			$(tab_sticky_posts_active + '.sticky-item').removeClass('active');

			adjustImageSize();
		}

		function runSticky() {

			var current = $(tab_sticky_posts_active + '.sticky-item.active');

			if(!current.length) {
				var toGo = $(tab_sticky_posts_active + '.sticky-item:first-child');
			} else {
				if(current.is(':last-child'))
					var toGo = $(tab_sticky_posts_active + '.sticky-item:first-child');
				else
					var toGo = current.next('.sticky-item');
			}

			openSticky(toGo.data('postid'));

		}
        function adjustPostItem_height(h) {
            var opened = $(tab_sticky_posts_active + '.sticky-item.active');
			var closed = $(tab_sticky_posts_active + '.sticky-item');

			opened.height(h);
			//closed.height("100%");   //if enable the mCustomScrollbar
			closed.height("auto");

            var highestBox = 0;
            $(".three_per_row").each(function(){
                if($(this).height() > highestBox)
                   highestBox = $(this).height();
            });
            $(".three_per_row").height(highestBox);

            // Set of the two colum of each posts items in News Container equal height
            for(var i = 1; i <= $(".two_per_row").length; i+=2) {
                var next = i+1;
                var highestCol = Math.max($(".two_per_row"+i).height(),$(".two_per_row"+next).height());
                $(".two_per_row"+i).height(highestCol);
                $(".two_per_row"+next).height(highestCol);
            }
        }
		function adjustImageSize() {

			var opened = $(tab_sticky_posts_active + '.sticky-item.active img');
			var closed = $(tab_sticky_posts_active + '.sticky-item img');

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

			$(tab_sticky_posts_active + '.sticky-item').each(function() {
				var $item = $(this);
				$item.data('shareUrl', $(this).find('.share-button').attr('href'));
				$item.find('.share-button').attr('href', $item.data('shareUrl') + '&map_id=' + map.currentMapID);

			});

			jeo.groupChanged(function(group) {

				$(tab_sticky_posts_active + '.sticky-item').each(function() {
					var $item = $(this);
					$item.find('.share-button').attr('href', $item.data('shareUrl') + '&map_id=' + group.currentMapID);
				});

			});

			map.on('click mouseup', function() {
				clearInterval(t);
			});

			setTimeout(function() {
				openSticky($(tab_sticky_posts_active + '.sticky-item:first-child').data('postid'));
				t = setInterval(runSticky, 6000);
			}, 700);
		}

	});

})(jQuery);