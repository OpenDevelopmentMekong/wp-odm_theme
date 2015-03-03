(function($) {

	var query = _.debounce(function(s, cb) {

		$.ajax({
			url: livesearch.ajaxurl,
			data: {
				action: livesearch.action,
				s: s
			},
			dataType: 'json',
			success: function(data) {

				cb(data);

			}
		});

	}, 200);

	var display = function(container, data, s) {

		var results = $('<ul class="results clearfix" />');

		_.each(data.posts, function(item, i) {

			var type = $('<p class="type">' + livesearch.labels[item.post_type] + '</p>');
			var title = $('<h2>' + item.title + '</h2>');
			var desc = $('<p class="excerpt">' + item.excerpt + '</p>');

			var link = $('<a href="' + item.url + '" title="' + item.title + '">' + item.title + '</a>');

			var item = $('<li />')
				.append(type)
				.append(title.html(link))
				.append(desc);

			item.addClass('item-' + (i+1));

			results.append(item);

		});

		var more = $('<li class="more" />');

		var link = $('<a href="' + livesearch.siteurl + '?s=' + s + '" />');
		link.text(livesearch.labels.more);
		more.append(link);

		results.append(more);

		results.addClass('results-' + data.posts.length);

		container.find('.results').remove();
		container.find('.results-container').append(results);

	};

	$(document).ready(function() {

		var $livesearch = $('#live-search');

		if($livesearch.length) {

			$livesearch.find('input[type=text]').on('keyup', function() {

				var self = $(this);

				var s = $(this).val();

				if(s) {
					query(s, function(data) {
						if(self.val())
							display($livesearch, data, s);
						else
							$livesearch.find('.results').remove();
					});
				} else {
					$livesearch.find('.results').remove();
				}

			});

		}

	});

})(jQuery);