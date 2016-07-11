(function($) {

	var spinner;

	var initSpinner = function(target){
		var opts = {
			top: '50%' ,
      left: '50%',
			scale: 0.1,
      position: 'absolute'
		};
		spinner = new Spinner(opts).spin();
		target.append(spinner.el);
	};

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
        $('#close-results').click(function(e) {
		    	$('.results-container').hide();
        });
			}
		});

	}, 200);

	var display = function(resultsContainer,data, s) {
		var results = $('<div class="results">');

		// wp based results
    _.each(data.wp, function(postType, i){

      var column = $('<div class="four columns"><h1>' + postType.title + '</h1></div>');

      if (postType.posts.length >0){

        _.each(postType.posts, function(item, i) {
    			//var type = $('<p class="type">' + postType.title + '</p>');
    			var title = $('<p><a class="post-list-item-title">' + item.title + '</a></p>');
    			var thumbnail = $(item.thumbnail);
					var content = $('<div class="post-list-item-content"></div>');
    			var desc = $('<p class="excerpt">' + item.excerpt + '</p>');
    			var link = $('<a href="' + item.url + '" title="' + item.title + '">' + item.title + '</a>');

					content.append(thumbnail);
					content.append(desc);

    			var item = $('<div class="post-list-item">')
    				//.append(type)
    				.append(title.html(link))
    				.append(content);

    			item.addClass('item-' + (i+1));
    			column.append(item);

    		});
      }else{
        var noItem = $('<p>No item found</p>');
        column.append(noItem);
      }

      results.append(column);

    });

		var dataColumn = $('<div class="four columns"><h1>Data</h1></div>');
		dataColumn.append(data.wpckan);
		results.append(dataColumn);

    // Results
		resultsContainer.find('.results').remove();
    $('#od-search-results').show();
		resultsContainer.show();
		resultsContainer.append(results);


    // More results
		resultsContainer.find('.more').remove();
    var buttons = $('<div class="more" />');
    var close = $('<a class="button close" id="close-results" href="#"></a>');
    var closeIcon = $('<i class="fa fa-times"></i>');
		close.text(livesearch.labels.close);
    close.prepend(closeIcon);
		var more = $('<a class="button" id="more-results" href="' + livesearch.siteurl + '?s=' + s + '" />');
    var moreIcon = $('<i class="fa fa-plus"></i>');
		more.text(livesearch.labels.more);
    more.prepend(moreIcon);
		buttons.append(more);
		buttons.append(close);
    resultsContainer.append(buttons);


		spinner.stop();

	};

	$(document).ready(function() {

		var $livesearch = $('.mega-search');
    var $resultsContainer = $('.results-container');

		if($livesearch.length) {

			$livesearch.find('input[type=text]').on('keyup', function() {

				var self = $(this);

				var s = $(this).val();
				if(s) {
					initSpinner($livesearch);
					query(s, function(data) {
						if(self.val())
							display($resultsContainer, data, s);
						else
							$livesearch.find('.results').remove();
					});
				} else {
					spinner.stop();
					$livesearch.find('.results').remove();
		      $resultsContainer.hide();
				}

			});

		}

	});

})(jQuery);
