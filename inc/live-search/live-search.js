(function($) {

	var spinner;

  var stopSpinner = function(){
    spinner.stop();
    spinner = null;
  };

	var initSpinner = function(target){
		var opts = {
      lines: 13, // The number of lines to draw
      length: 5, // The length of each line
      width: 2, // The line thickness
      radius:5, // The radius of the inner circle
      top: "50%",
      left: "50%",
      position: "absolute"
		};
    if (!spinner){
      spinner = new Spinner(opts).spin();
    }
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

        if (spinner){
          stopSpinner();
        }

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

      var column = $('<div class="four columns"><h2>' + postType.title + '</h2></div>');

      if (postType.posts.length >0){

        _.each(postType.posts, function(item, i) {
    			//var type = $('<p class="type">' + postType.title + '</p>');
    			var title = $('<p><a class="post-list-item-title">' + item.title + '</a></p>');
    			var thumbnail = $(item.thumbnail);
				var content = $('<div class="post-list-item-content"></div>');
    			var desc = $('<p class="excerpt">' + item.excerpt + '</p>');

					content.append(thumbnail);
					content.append(desc);

    			var postItem = $('<div class="post-list-item">');
          		postItem.append(title);
          		postItem.append(content);
    			column.append(postItem);

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
		resultsContainer.slideDown();
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

		stopSpinner();

	};

	$(document).ready(function() {

		var $livesearch = $('.mega-search');
    var $resultsContainer = $('.results-container');

		if($livesearch.length) {

			$livesearch.find('input[type=text]').on('keyup', function() {

				var self = $(this);

				var s = $(this).val();
				if(s) {
					initSpinner($('#mega-menu-wrap-header_menu'));
					query(s, function(data) {
						if(self.val())
							display($resultsContainer, data, s);
						else
							$livesearch.find('.results').remove();
					});
				} else {
					stopSpinner();
					$livesearch.find('.results').remove();
		      $resultsContainer.hide();
				}

			});

		}

	});

})(jQuery);
