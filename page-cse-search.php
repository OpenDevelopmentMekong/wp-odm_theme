<?php
/*
 * Template Name: CSE Test search page
 *
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

  <section class="container section-title main-title">
		<header class="row">
			<div class="sixteen columns">
				<h1><?php the_title(); ?></h1>
			</div>
		</header>
	</section>

	<section class="container">
		<div class="row">
			<div class="four columns">
				<form id="cse_search_form" method="GET">
					<input id="cse_search" type="text" name="ckan_s" placeholder="<?php _e('Type your search and hit enter', 'odm'); ?>" value="" />
					<input id="cse_submit" type="button" value="Submit"></input>
				</form>
			</div>
			<div class="twelve columns">
				<h2>Results</h2>
				<div id="cse_results"></div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">

  // micro tests
  //console.log(isCkanRecord("https://openDevelopmentMekong.net/dataset/1"));

  function ckanToWpLink(url){
    return url;
  }

  function isCkanRecord(url){
    return url.startsWith("https://data.");
  }

	function hndlr(response) {

		for (var i = 0; i < response.items.length; i++) {

      var item = response.items[i];

      // in production code, item.htmlTitle should have the HTML entities escaped.
      console.log(item.link);
      console.log(item.title);

      var link = isCkanRecord(item.link) ? ckanToWpLink(item.link) : item.link;
      var component = isCkanRecord(item.link) ? "CKAN" : "WP";

      var itemHtml = jQuery('<div class="cse_result"></div>');
      var title = jQuery('<a href="' + link + '" target="_blank">' + item.title + '</a>');
      var description = jQuery('<p>' + item.htmlSnippet + '</p>');
      var component = jQuery('<p>' + component + '</p>');

      itemHtml.append(title);
      itemHtml.append(description);
      itemHtml.append(component);

			document.getElementById("cse_results").innerHTML += itemHtml.html();
		}
	}

	var api_key= "AIzaSyCHHIlx8Q1wEUj1-h9zIvfnGYqIxooTRdY";
  	var cx = "018137511656225297663:skc7uxrvvfq";
	jQuery(document).ready(function($) {
		$('#cse_submit').on('click', function(){
			var search_query = "https://www.googleapis.com/customsearch/v1?key=" + api_key + "&cx=" + cx +"&q="+ $('#cse_search').val() +"&callback=hndlr";
			console.log(search_query);
			$.get(search_query, function(data, status){
        console.log("Data: " + data + "\nStatus: " + status);
	    });
		})
	});
</script>
