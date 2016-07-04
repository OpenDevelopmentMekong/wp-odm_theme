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
        $("a.close_result" ).click(function(e) {
		    $('.results-container').hide();
        });
			}
		});

	}, 200);

	var display = function(resultsContainer,data, s) {
		var results = $('<div class="results">');

    _.each(data, function(postType, i){

      var column = $('<div class="three columns"><h1>' + postType.title + '</h1></div>');

      if (postType.posts.length >0){
        _.each(postType.posts, function(item, i) {
    			var type = $('<p class="type">' + livesearch.labels[item.postType] + '</p>');
    			var title = $('<h2>' + item.title + '</h2>');
    			var thumbnail = $(item.thumbnail);
    			var desc = $('<p class="excerpt">' + item.excerpt + '</p>');
    			var link = $('<a " href="' + item.url + '" title="' + item.title + '">' + item.title + '</a>');

    			var item = $('<div>')
    				.append(type)
    				.append(title.html(link))
    				.append(thumbnail)
    				.append(desc);

    			item.addClass('item-' + (i+1));
    			column.append(item);

    		});
      }else{
        var noItem = $('<p>No item found</p>');
        column.append(noItem);
      }

      results.append(column);

    });

    // Results
		resultsContainer.find('.results').remove();
    $('#od-search-results').show();
		resultsContainer.show();
		resultsContainer.append(results);

    // Close button
    resultsContainer.append("<a href='#' class='close_result'><i class='fa fa-times-circle' aria-hidden='true'></i></a>");

    // More results
    var more = $('<div class="more" />');
		var link = $('<a class="button" id="more-results" href="' + livesearch.siteurl + '?s=' + s + '" />');
		link.text(livesearch.labels.more);
		more.append(link);
    resultsContainer.append(more);


		$('#loading').hide();

	};

	$(document).ready(function() {

		var $livesearch = $('.mega-search');
    var $resultsContainer = $('.results-container');

		if($livesearch.length) {

			$livesearch.find('input[type=text]').on('keyup', function() {

				var self = $(this);

				var s = $(this).val();
				if(s) {
					//$('#loading').show();
					query(s, function(data) {
						if(self.val())
							display($resultsContainer, data, s);
						else
							$livesearch.find('.results').remove();
					});
				} else {
					$('#loading').hide();
					$livesearch.find('.results').remove();
		      $resultsContainer.hide();
				}

			});

		}

	});

})(jQuery);
