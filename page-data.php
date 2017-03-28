<?php
/*
Template Name: Data
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

<?php
  // get following variables from URL for filtering
  $param_query = !empty($_GET['query']) ? $_GET['query'] : null;
  $param_license = !empty($_GET['license']) ? $_GET['license'] : null;
  $param_taxonomy = isset($_GET['taxonomy']) ? $_GET['taxonomy'] : null;
  $param_language = isset($_GET['language']) ? $_GET['language'] : null;
  $param_page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
  $param_country = odm_country_manager()->get_current_country() == 'mekong' && isset($_GET['country']) ? $_GET['country'] : odm_country_manager()->get_current_country();
	$param_sorting = isset($_GET['sorting']) ? $_GET['sorting'] : 'score';

  //Get Datasets
  $attrs = array(
    'dataset_type' => 'dataset',
		'capacity' => 'public'
  );

	$control_attrs = array(
    'limit' => 15,
    'page' => $param_page,
		'sorting' => $param_sorting
  );

  //================ Filter Values ===================== //

  $taxonomy_list = odm_taxonomy_manager()->get_taxonomy_list();
  $countries = odm_country_manager()->get_country_codes();
  $languages = odm_language_manager()->get_supported_languages();
  $license_list = wpckan_get_license_list();

  //================ Build Filters ===================== //

  //Taxonomy
  if (!empty($param_taxonomy) && $param_taxonomy != 'all') {
    $attrs["extras_taxonomy"] = $param_taxonomy;
  }

  // Language
  if (!empty($param_language) && $param_language != 'all') {
    $attrs["extras_odm_language"] = $param_language;
  }

  // Country
  if (!empty($param_country) && $param_country != 'mekong' && $param_country != 'all') {
    $attrs["extras_odm_spatial_range"] = $countries[$param_country]['iso2'];
  }

  //License
  if (!empty($param_license) && $param_license != 'all') {
    $attrs['license_id'] = $param_license;
  }

  $result = WP_Odm_Solr_CKAN_Manager()->query($param_query,$attrs,$control_attrs);
  $results = $result["resultset"];
  $facets = $result["facets"];

  //================== Pagination ======================
  $request_url = $_SERVER['REQUEST_URI'];
  $url_parts = parse_url($request_url);
  if (isset($url_parts['query'])) {
    parse_str($url_parts['query'], $params);
  } else {
    $params = [];
  }

  //Next Page Link
  $next_page_params = $params;
  $next_page_url_parts = $url_parts;
  $next_page_params['page'] = $param_page + 1;
  $next_page_url_parts['query'] = http_build_query($next_page_params);
  $next_page_link = $next_page_url_parts['path'] . '?' . $next_page_url_parts['query'];

  //Prev Page Link
  $prev_page_params = $params;
  $prev_page_url_parts = $url_parts;
  $prev_page_params['page'] = $param_page - 1;
  $prev_page_url_parts['query'] = http_build_query($prev_page_params);
  $prev_page_link = $prev_page_url_parts['path'] . '?' . $prev_page_url_parts['query'];

  $total_pages = ceil($results->getNumFound()/$control_attrs['limit']);
?>

<div class="container">
  <div class="row">
    <div class="four columns data-advanced-filters">
      <form>
        <h3><i class="fa fa-filter"></i> Filters</h3>

        <!-- TAXONOMY FILTER -->
        <div class="single-filter">
          <label for="taxonomy"><?php _e('Topic', 'odm'); ?></label>
          <select id="taxonomy" name="taxonomy" data-placeholder="<?php _e('Select term', 'odm'); ?>">
            <option value="all" selected><?php _e('All','odm') ?></option>
            <?php
              foreach($taxonomy_list as $value):
                if (array_key_exists("vocab_taxonomy",$facets)):
                  $taxonomy_facets = $facets["vocab_taxonomy"];
                  if (array_key_exists($value,$taxonomy_facets)):
                    $available_records = $taxonomy_facets[$value];
                    if ($available_records > 0): ?>
                    <option value="<?php echo $value; ?>" <?php if($value == $param_taxonomy) echo 'selected'; ?>><?php echo $value . " (" . $available_records . ")"; ?></option>
            <?php
                    endif;
                  endif;
                endif;
              endforeach; ?>
          </select>
        </div>
        <!-- END OF TAXONOMY FILTER -->

        <!-- COUNTRY FILTER -->
        <?php if (odm_country_manager()->get_current_country() == 'mekong'): ?>
        <div class="single-filter">
          <label for="country"><?php _e('Country', 'odm'); ?></label>
          <select id="country" name="country" data-placeholder="<?php _e('Select country', 'odm'); ?>">
            <option value="all" selected><?php _e('All','odm') ?></option>
            <?php
              foreach($countries as $key => $value):
                if ($key != 'mekong'):
                  if (array_key_exists("extras_odm_spatial_range",$facets)):
                    $spatial_range_facets = $facets["extras_odm_spatial_range"];
                    $country_codes = odm_country_manager()->get_country_codes();
                    $country_code = $country_codes[$key]["iso2"];
                    if (array_key_exists($country_code,$spatial_range_facets)):
                      $available_records = $spatial_range_facets[$country_code];
                      if ($available_records > 0): ?>
                  <option value="<?php echo $key; ?>" <?php if($key == $param_country) echo 'selected'; ?> <?php if (odm_country_manager()->get_current_country() != 'mekong' && $key != odm_country_manager()->get_current_country()) echo 'disabled'; ?>><?php echo odm_country_manager()->get_country_name($key) . " (" . $available_records . ")"; ?></option>
              <?php
                      endif;
                    endif;
                  endif;
                endif; ?>
                <?php
              endforeach; ?>
          </select>
        </div>
        <?php endif; ?>
        <!-- END OF COUNTRY FILTER  -->

        <!-- LANGUAGE FILTER -->
        <div class="single-filter">
          <label for="language"><?php _e('Language', 'odm'); ?></label>
          <select id="language" name="language" data-placeholder="<?php _e('Select language', 'odm'); ?>">
            <option value="all"  selected><?php _e('All','odm') ?></option>
            <?php
              foreach($languages as $key => $value):
                if (array_key_exists("extras_odm_language",$facets)):
                  $language_facets = $facets["extras_odm_language"];
                  if (array_key_exists($key,$language_facets)):
                    $available_records = $language_facets[$key];
                    if ($available_records > 0): ?>
            <option value="<?php echo $key; ?>" <?php if($key == $param_language) echo 'selected'; ?>><?php echo $value . " (" . $available_records . ")" ?></option>
            <?php
                    endif;
                  endif;
                endif;
              endforeach; ?>
          </select>
        </div>
        <!-- END OF LANGUAGE FILTER -->

        <!-- LICENSE FILTER -->
        <div class="single-filter">
          <label for="license"><?php _e('License', 'odm'); ?></label>
          <select id="license" name="license" data-placeholder="<?php _e('Select license', 'odm'); ?>">
            <option value="all" selected><?php _e('All','odm') ?></option>
            <?php
              foreach($license_list as $license):
                if (array_key_exists("license_id",$facets)):
                  $license_facets = $facets["license_id"];
                  if (array_key_exists($license->id,$license_facets)):
                    $available_records = $license_facets[$license->id];
                    if ($available_records > 0): ?>
                <option value="<?php echo $license->id; ?>" <?php if($license->id == $param_license) echo 'selected'; ?>><?php echo $license->title . " (" . $available_records . ")" ?></option>
            <?php
                    endif;
                  endif;
                endif;
              endforeach; ?>
          </select>
        </div>
				<!-- END OF LICENSE FILTER -->

				<!-- SORTING FUNCTION -->
				<h3><i class="fa fa-sort"></i> Sorting</h3>
				<div class="single-filter">
          <label for="sorting"><?php _e('Sort by', 'odm'); ?></label>
          <select id="sorting" name="sorting" data-placeholder="<?php _e('Sort by', 'odm'); ?>">
            <option <?php if($param_sorting == "score") echo 'selected'; ?> value="score"><?php _e('Relevance','odm') ?></option>
          	<option <?php if($param_sorting == "metadata_modified") echo 'selected'; ?> value="metadata_modified"><?php _e('Date modified','odm') ?></option>
          </select>
        </div>
				<!-- END OF LICENSE FILTER -->

        <div class="single-filter">
          <input class="button" type="submit" value="<?php _e('Search Filter', 'odm'); ?>"/>
        </div>

    </div>
    <div class="eleven columns">
      <div class="search_bar">
        <input id="search_field" type="text" class="full-width-search-box" name="query" placeholder="<?php _e('Type your search here', 'odm'); ?>" value="<?php echo $param_query; ?>" data-solr-host="<?php echo $GLOBALS['wp_odm_solr_options']->get_option('wp_odm_solr_setting_solr_host'); ?>" data-solr-scheme="<?php echo $GLOBALS['wp_odm_solr_options']->get_option('wp_odm_solr_setting_solr_scheme'); ?>" data-solr-path="<?php echo $GLOBALS['wp_odm_solr_options']->get_option('wp_odm_solr_setting_solr_path'); ?>" data-solr-core-wp="<?php echo $GLOBALS['wp_odm_solr_options']->get_option('wp_odm_solr_setting_solr_core_wp'); ?>" data-solr-core-ckan="<?php echo $GLOBALS['wp_odm_solr_options']->get_option('wp_odm_solr_setting_solr_core_ckan'); ?>"></input>
        </form>
      </div>
      <div class="results_info"><h2><?php echo $results->getNumFound() ?> records found.</h2></div>
      <div class="result_container container">

        <?php foreach($results as $document): ?>
        <!-- TEMPLATE -->
        <div class="single_result_container row">

					<?php
					$title = wp_odm_solr_parse_multilingual_ckan_content($document->extras_title_translated,odm_language_manager()->get_current_language(),$document->title);
					$title = wp_odm_solr_highlight_search_words($s,$title);
					?>
					<h4 class="data_title ten columns">
					  <a href="<?php echo wpckan_get_link_to_dataset($document->id) ?>">
					    <?php echo $title ?>
					  </a>
					</h4>
					<div class="data_format six columns">
					  <?php $resource_formats = array_unique($document->res_format); ?>
					  <?php foreach ($resource_formats as $format): ?>
					    <span class="meta-label <?php echo strtolower($format); ?>"><?php echo strtolower($format); ?></span>
					  <?php endforeach ?>
					</div>
					<?php
					  $description = wp_odm_solr_parse_multilingual_ckan_content($document->extras_notes_translated,odm_language_manager()->get_current_language(),$document->notes);
					  $description = strip_tags($description);
					  $description = substr($description,0,400);
					  $description = wp_odm_solr_highlight_search_words($s,$description);
					 ?>
					<p class="data_description sixteen columns">
					<?php
					  echo $description;
					  if (strlen($description) >= 400):
					    echo "...";
					  endif;
					  ?>
					</p>
					<div class="data_meta_wrapper sixteen columns">
					    <!-- Country -->
					  <?php if (!empty($document->extras_odm_spatial_range)): ?>
					    <div class="country_indicator data_meta">
					      <i class="fa fa-globe"></i>
					      <span>
					        <?php
					          $odm_country_arr = json_decode($document->extras_odm_spatial_range);
					          foreach ($odm_country_arr as $country_code):
					            $country_name = odm_country_manager()->get_country_name_by_country_code($country_code);
					            _e($country_name, "wp-odm_solr");
					            if ($country_code !== end($odm_country_arr)):
					              echo ', ';
					            endif;
					          endforeach; ?>
					      </span>
					    </div>
					  <?php endif; ?>
					  <!-- Language -->
					  <?php if (!empty($document->extras_odm_language)): ?>
					    <div class="data_languages data_meta">
					      <?php $odm_lang_arr = json_decode($document->extras_odm_language); ?>
					      <span>
					        <?php foreach ($odm_lang_arr as $lang): ?>
					          <img class="lang_flag" alt="<?php echo $lang ?>" src="<?php echo odm_language_manager()->get_path_to_flag_image($lang); ?>"></img>
					        <?php endforeach; ?>
					      </span>
					    </div>
					  <?php endif; ?>
					  <!-- Topics -->
					  <?php if (!empty($document->vocab_taxonomy)): ?>
					    <div class="data_meta">
					      <i class="fa fa-tags"></i>
					      <span>
					        <?php
					          foreach ($document->vocab_taxonomy as $topic):
					            _e($topic. " ", "wp-odm_solr");
					          endforeach; ?>
					      </span>
					    </div>
					  <?php endif; ?>
					  <!-- Keywords -->
					  <?php if (!empty($document->extras_odm_keywords)): ?>
					    <div class="data_meta">
					      <i class="fa fa-tags"></i>
					      <?php
					        $hihglighted_value = wp_odm_solr_highlight_search_words($s,implode(", ",$document->extras_odm_keywords));
					        _e($hihglighted_value, "wp-odm_solr") ?>
					    </div>
					  <?php endif; ?>
					</div>
        </div>
        <!-- END OF TEMPLATE -->
			<?php endforeach; ?>
      </div>

			<section class="container">
				<div class="row">
					<div class="sixteen columns">
						<?php odm_get_template('pagination_solr',array(
							"current_page" => $param_page,
							"total_pages" => $total_pages
						),true); ?>
					</div>
				</div>
			</section>

    </div>
  </div>
</div>

<?php endif; ?>

<script>

    jQuery(document).ready(function() {

      jQuery('#search_field').autocomplete({
        source: function( request, response ) {
          var host = jQuery('#search_field').data("solr-host");
          var scheme = jQuery('#search_field').data("solr-scheme");
          var path = jQuery('#search_field').data("solr-path");
          var core_wp = jQuery('#search_field').data("solr-core-wp");
          var core_ckan = jQuery('#search_field').data("solr-core-ckan");
          var url = scheme + "://" + host  + path + core_ckan + "/suggest";
					console.log("pulling suggestions from: " + url);
          jQuery.ajax({
            url: url,
            data: {'wt':'json', 'q':request.term, 'json.wrf': 'callback'},
            dataType: "jsonp",
            jsonpCallback: 'callback',
            contentType: "application/json",
            success: function( data ) {

              var options = [];
              if (data){
                if(data.spellcheck){
                  var spellcheck = data.spellcheck;
                  if (spellcheck.suggestions){
                    var suggestions = spellcheck.suggestions;
                    if (suggestions[1]){
                      var suggestionObject = suggestions[1];
                      options = suggestionObject.suggestion;
                    }
                  }
                }
              }
              response( options );
            }
          });
        },
        minLength: 2,
        select: function( event, ui ) {
          var terms = this.value.split(" ");
          terms.pop();
          terms.push( ui.item.value );
          this.value = terms.join( " " );
          return false;
        }
      });
    });

	</script>

<?php get_footer(); ?>
