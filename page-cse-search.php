<?php
/*
 * Template Name: CSE Test search page
 *
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post();

	global $post;

	?>

  <section class="container section-title main-title">
		<header class="row">
			<div class="sixteen columns">
				<h1><?php the_title(); ?></h1>
			</div>
		</header>
	</section>

	<section style="display:none">
		<div id="cse_cfg" data-cse_api_key="<?php echo get_post_meta($post->ID,'cse_api_key',true); ?>" data-cse_cx="<?php echo get_post_meta($post->ID,'cse_cx',true); ?>"></div>
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
				<div id="cse_results" class="accordion">
					<!-- <div class="cse_results_section"><h3><?php _e("Datasets","odm") ?></h3><div id="cse_results_dataset"></div></div> -->
					<h3><?php _e("Maps","odm") ?></h3>
					<div id="cse_results_maps" class="cse_results_section"></div>
					<h3><?php _e("News articles","odm") ?></h3>
					<div id="cse_results_news" class="cse_results_section"></div>
					<h3><?php _e("Topics","odm") ?></h3>
					<div id="cse_results_topics" class="cse_results_section"></div>
					<h3><?php _e("Profiles","odm") ?></h3>
					<div id="cse_results_profiles" class="cse_results_section"></div>
					<h3><?php _e("Dashboards","odm") ?></h3>
					<div id="cse_results_dashboards" class="cse_results_section"></div>
				</div>
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

		console.log(response);

		resultsDiv.innerHtml = "";

		if (response.items){

			var totalHeight = 0;

			for (var i = 0; i < response.items.length; i++) {

	      var item = response.items[i];

	      var link = isCkanRecord(item.link) ? ckanToWpLink(item.link) : item.link;
	      var component = isCkanRecord(item.link) ? "CKAN" : "WP";

	      var itemHtml = jQuery('<div class="cse_result"></div>');
	      var title = jQuery('<a href="' + link + '" target="_blank">' + item.htmlTitle + '</a>');
	      var description = jQuery('<p>' + item.htmlSnippet + '</p>');

	      var meta = jQuery('<div class="cse_result_meta"></div>');
				var metadataList = jQuery('<ul></ul>');

	      var metatags = item.pagemap.metatags[0];
	      if (metatags.odm_spatial_range){
	        var country = jQuery('<li>' + metatags.odm_spatial_range + '</li>');
	        metadataList.append(country);
	      }
	      if (metatags.odm_language){
	        var language = jQuery('<li>' + metatags.odm_language + '</li>');
	        metadataList.append(language);
	      }
	      if (metatags.odm_license){
	        var license = jQuery('<li>' + metatags.odm_license + '</li>');
	        metadataList.append(license);
	      }
				if (metatags.odm_type){
	        var type = jQuery('<li>' + metatags.odm_type + '<li>');
	        metadataList.append(type);
	      }
	      var componentHtml = jQuery('<li>' + component + '</li>');
	      metadataList.append(componentHtml);
				meta.append(metadataList);

	      itemHtml.append(title);
	      itemHtml.append(description);
	      itemHtml.append(meta);

				resultsDiv.append(itemHtml);
			}

		}else{

			var noRecordsFound = jQuery("<p>No records found</p>");
			resultsDiv.append(noRecordsFound);
			resultsDiv.height(100);
		}

		jQuery(".accordion").accordion("refresh");

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

	/*function hndlr(response) {

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
	}*/

  function triggerQuery(resultsDiv,startIndex,type){

    var search_query = "https://www.googleapis.com/customsearch/v1?key=" + api_key + "&cx=" + cx +"&q="+ $('#cse_search').val() +" more:pagemap:metatags-odm_type:" + type + "&start=" + startIndex;
		//search_query += "&callback=hndlr"
		console.log(search_query);

    $.get(search_query, function(response, status){
      console.log("Query finished with status: " + status);

	    resultsDiv.empty();

			var totalResults = response.queries.request[0].totalResults;
			var count = response.queries.request[0].count;
			var startIndex = response.queries.request[0].startIndex;
			console.log("showing " + startIndex + " - " + startIndex+count + " from " + totalResults);

			if (count > 0){
				outputSearchResults(resultsDiv,response);
			}

			//renderPagination(paginationDiv,startIndex,count,totalResults);
    });
  }

	var api_key;
	var cx;

	jQuery(document).ready(function($) {

		jQuery(".accordion").accordion({ header: "h3", active: false, collapsible: true, heightstyle: "content" });
		$('#cse_submit').on('click', function(){
			//triggerQuery(jQuery("#cse_results_dataset"),1,"");
			triggerQuery(jQuery("#cse_results_maps"),1,"layer");
			triggerQuery(jQuery("#cse_results_news"),1,"news-article");
			triggerQuery(jQuery("#cse_results_topics"),1,"topic");
			triggerQuery(jQuery("#cse_results_profiles"),1,"profile");
			triggerQuery(jQuery("#cse_results_dashboards"),1,"dashboard");
		})

		api_key = $("#cse_cfg").data("cse_api_key");
		cx = $("#cse_cfg").data("cse_cx");
		console.log(api_key);
		console.log(cx);
	});
</script>
