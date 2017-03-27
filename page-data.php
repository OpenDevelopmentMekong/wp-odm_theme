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
  $active_filters =  !empty($param_taxonomy) || !empty($param_language) || !empty($param_query) || !empty($param_license);

  //Get Datasets
  $attrs = array(
    'type' => 'dataset'
  );

	$control_attrs = array(
    'limit' => 15,
    'page' => $param_page
  );

  //================ Filter Values ===================== //

  $taxonomy_list = odm_taxonomy_manager()->get_taxonomy_list();
  $countries = odm_country_manager()->get_country_codes();
  $languages = odm_language_manager()->get_supported_languages();
  $license_list = wpckan_get_license_list();

  //================ Build Filters ===================== //

  // Attributes
  $attrs = [];

	//Type
	$attrs["dataset_type"] = 'dataset';

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

  $result = WP_Odm_Solr_CKAN_Manager()->query($param_query,$attrs);
  $datasets = $result["resultset"];

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

  $total_pages = ceil($datasets->getNumFound()/$control_attrs['limit']);
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
              foreach($taxonomy_list as $value): ?>
              <option value="<?php echo $value; ?>" <?php if($value == $param_taxonomy) echo 'selected'; ?>><?php echo $value; ?></option>
            <?php
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
                if ($key != 'mekong'): ?>
                  <option value="<?php echo $key; ?>" <?php if($key == $param_country) echo 'selected'; ?> <?php if (odm_country_manager()->get_current_country() != 'mekong' && $key != odm_country_manager()->get_current_country()) echo 'disabled'; ?>><?php echo odm_country_manager()->get_country_name($key); ?></option>
              <?php
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
              foreach($languages as $key => $value): ?>
              <option value="<?php echo $key; ?>" <?php if($key == $param_language) echo 'selected'; ?>><?php echo $value; ?></option>
            <?php
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
              foreach($license_list as $license):?>
                <option value="<?php echo $license->id; ?>" <?php if($license->id == $param_license) echo 'selected'; ?>><?php echo $license->title; ?></option>
            <?php
              endforeach; ?>
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
      <div class="results_info"><h2><?php echo $datasets->getNumFound() ?> records found.</h2></div>
      <div class="result_container container">

        <?php foreach($datasets as $document):
          $dataset = wpckan_api_package_show(wpckan_get_ckan_domain(),$document["id"]);
          ?>
        <!-- TEMPLATE -->
        <div class="single_result_container row">

          <h2 class="data_title twelve columns">
            <a href="<?php echo wpckan_get_link_to_dataset($dataset['name']);?>">
              <?php echo getMultilingualValueOrFallback($dataset['title_translated'], odm_language_manager()->get_current_language(),$dataset['title']);?>
            </a>
          </h2>
          <div class="data_format four columns">
            <?php
              $resource_formats = array_unique(array_column($dataset['resources'], 'format'));
             ?>
            <?php foreach ($resource_formats as $format): ?>
              <span class="meta-label <?php echo strtolower($format); ?>"><?php echo strtolower($format); ?></span>
            <?php endforeach ?>
          </div>
          <p class="data_description sixteen columns">
            <?php echo getMultilingualValueOrFallback($dataset["notes_translated"], odm_language_manager()->get_current_language(),$dataset['notes']) ?>
          </p>
          <div class="data_meta_wrapper sixteen columns">
            <?php if (odm_country_manager()->get_current_country() == 'mekong'): ?>
							<div class="country_indicator data_meta">
								<ul>
							<?php foreach ($dataset['odm_spatial_range'] as $country_code):
								$country_name = odm_country_manager()->get_country_name_by_country_code($country_code); ?>
	              <li><?php echo $country_name; ?></li>
							<?php endforeach; ?>
								</ul>
							</div>
            <?php endif; ?>
              <div class="data_languages data_meta">
                <?php foreach ($dataset['odm_language'] as $lang): ?>
                  <img alt="<?php echo $lang ?>" src="<?php echo odm_language_manager()->get_path_to_flag_image($lang); ?>"></img>
                <?php endforeach; ?>
              </div>
              <div class="data_topics data_meta">
                <i class="fa fa-tags"></i>
                <?php
                  $tags = array_column($dataset['tags'], 'display_name');
                  echo implode(", ", $tags);
                ?>
              </div>
          </div>
        </div>
        <!-- END OF TEMPLATE -->
        <?php endforeach; ?>
      </div>
      <div class="pagination_wrapper">
        <?php if ($param_page > 1): ?>
          <a href="<?php echo $prev_page_link; ?>"> << Previous Page </a>
        <?php endif ?>
        <?php if ($param_page < $total_pages): ?>
          <a href="<?php echo $next_page_link; ?>"> Next Page >> </a>
        <?php endif ?>
      </div>
    </div> <!-- end of right container -->
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
