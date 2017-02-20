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
				<div id="cse_pagination"></div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>

<script type="text/javascript">

  // micro tests
  //console.log(isCkanRecord("https://openDevelopmentMekong.net/dataset/1"));

  function ckanToWpLink(url){

    var dataset = url.split('dataset/')[1].split('/')[0];
    console.log("/dataset/?id=" + dataset);
    return "/dataset/?id=" + dataset;
  }

  function isCkanRecord(url){
    return url.startsWith("https://data.");
  }

	function outputSearchResults(resultsDiv,response){

		resultsDiv.innerHTML = "";

		for (var i = 0; i < response.items.length; i++) {

      var item = response.items[i];

      // in production code, item.htmlTitle should have the HTML entities escaped.
      console.log(item.link);
      console.log(item.title);

      var link = isCkanRecord(item.link) ? ckanToWpLink(item.link) : item.link;
      var component = isCkanRecord(item.link) ? "CKAN" : "WP";

      var itemHtml = jQuery('<div class="cse_result"></div>');
      var title = jQuery('<a href="' + link + '" target="_blank">' + item.htmlTitle + '</a>');
      var description = jQuery('<p>' + item.htmlSnippet + '</p>');

      var meta = jQuery('<div class="cse_result_meta"><h4>Metadata</h4></div>');
      var metatags = item.pagemap.metatags[0];
      if (metatags.odm_spatial_range){
        var country = jQuery('<p>' + metatags.odm_spatial_range + '</p>');
        meta.append(country);
      }
      if (metatags.odm_language){
        var language = jQuery('<p>' + metatags.odm_language + '</p>');
        meta.append(language);
      }
      if (metatags.odm_license){
        var license = jQuery('<p>' + metatags.odm_license + '</p>');
        meta.append(license);
      }
      var componentHtml = jQuery('<p>' + component + '</p>');
      meta.append(componentHtml);

      itemHtml.append(title);
      itemHtml.append(description);
      itemHtml.append(meta);

			resultsDiv.append(itemHtml);
		}
	}

	function renderPagination(paginationDiv,startIndex,count,totalResults){

		var paginationContent = jQuery("<h2>Pagination</h2>");
    var paginationLinks = jQuery('<div id="cse_pagination_links"><ul></ul></div>');

    var numPages = totalResults / count;
    var currentPage = startIndex / count;
    console.log(numPages);
    console.log(currentPage);

    for (var i=0; i < numPages; i++){
      var link = jQuery('<li><a href="#">' + i + '</a></li>');
      link.on('click', function(){
        var pageIndex = jQuery(this).text() * count;
        console.log("loading page: " + pageIndex);
        triggerQuery(pageIndex);
      })
      paginationLinks.append(link);
    }

		paginationDiv.append(paginationContent);
    paginationDiv.append(paginationLinks);

	}

	function hndlr(response) {

		var resultsDiv = jQuery("#cse_results");
    resultsDiv.empty();
    var paginationDiv = jQuery("#cse_results");
    paginationDiv.empty();
		var totalResults = response.queries.request[0].totalResults;
		var count = response.queries.request[0].count;
		var startIndex = response.queries.request[0].startIndex;
		console.log("showing " + startIndex + " - " + startIndex+count + " from " + totalResults);

		outputSearchResults(resultsDiv,response);
		renderPagination(paginationDiv,startIndex,count,totalResults);
	}

  function triggerQuery(startIndex){
    var search_query = "https://www.googleapis.com/customsearch/v1?key=" + api_key + "&cx=" + cx +"&q="+ $('#cse_search').val() +"&start=" + startIndex + "&callback=hndlr";
    console.log(search_query);
    $.get(search_query, function(data, status){
      console.log("Query finished with status: " + status);
    });
  }
  var api_key= "AIzaSyCHHIlx8Q1wEUj1-h9zIvfnGYqIxooTRdY";
  var cx = "018137511656225297663:skc7uxrvvfq";
	jQuery(document).ready(function($) {
		$('#cse_submit').on('click', function(){
			triggerQuery(1);
		})
	});
</script>
