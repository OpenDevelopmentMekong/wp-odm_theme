<?php
/*
Template Name: Data
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

<?php
  // get following variables from URL for filtering
  $param_type = isset($_GET['type']) ? $_GET['type'] : null;
  $param_query = !empty($_GET['query']) ? $_GET['query'] : null;
  $param_query_source = !empty($_GET['source']) ? $_GET['source'] : null;
  $param_license = !empty($_GET['license']) ? $_GET['license'] : null;
  $param_taxonomy = isset($_GET['taxonomy']) ? $_GET['taxonomy'] : null;
  $param_language = isset($_GET['language']) ? $_GET['language'] : null;
  $param_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $param_country = odm_country_manager()->get_current_country() == 'mekong' && isset($_GET['country']) ? $_GET['country'] : odm_country_manager()->get_current_country();
  $active_filters = !empty($_GET['type']) || !empty($param_taxonomy) || !empty($param_language) || !empty($param_query) || !empty($param_query_source) | !empty($param_license);
?>

<?php
  $types = array(
    "dataset" => "Datasets",
    "library_record" => "Library records",
    "laws_record" => "Laws"
  );
?>

  <?php
    if (!$active_filters && function_exists('wpdash_get_ckan_stats_dataviz_by_type') && function_exists('wpdash_get_ckan_stats_dataviz_by_taxonomy')): ?>
      <div class="container hideOnMobileAndTablet">
        <div class="row">
          <div class="two columns align-center">
            <i class="fa fa-table fa-5x"></i>
            <a href="?type=dataset"><div id="num_datasets" class="counter"></div>
            <div><?php _e('Datasets','odm') ?></div></a>
          </div>
          <div class="two columns align-center">
            <i class="fa fa-book fa-5x"></i>
            <a href="?type=library_record"><div id="num_library_records" class="counter"></div>
            <div><?php _e('Library records','odm') ?></div></a>
          </div>
          <div class="two columns align-center">
            <i class="fa fa-gavel fa-5x"></i>
            <a href="?type=laws_record"><div id="num_laws_records" class="counter"></div>
            <div><?php _e('Laws records','odm') ?></div></a>
          </div>
          <div class="ten columns">
            <?php wpdash_get_ckan_stats_dataviz_by_taxonomy(null); ?>
          </div>
        </div>
      </div>
  <?php
    endif; ?>

  <div class="container">
    <div class="row">

      <form class="advanced-nav-filters sixteen columns panel">

        <?php $num_columns = ($param_country === 'mekong') ? "two" : "three"; ?>
        <div class="<?php echo $num_columns ?> columns">
          <div class="adv-nav-input">
            <p class="label"><label for="s"><?php _e('Text search', 'odm'); ?></label></p>
            <input type="text" id="query" name="query" placeholder="<?php _e('Type your search here', 'odm'); ?>" value="<?php echo $param_query; ?>" />
          </div>
        </div>

        <div class="two columns">
          <div class="adv-nav-input">
            <p class="label"><label for="type"><?php _e('Type', 'odm'); ?></label></p>
            <select id="type" name="type" data-placeholder="<?php _e('Select dataset type', 'odm'); ?>">
							<option value="all"  selected><?php _e('All','odm') ?></option>
							<?php
                foreach($types as $key => $value): ?>
                <option value="<?php echo $key; ?>" <?php if ($key == $param_type) echo 'selected'; ?>><?php echo $value; ?></option>
              <?php
                endforeach; ?>
            </select>
          </div>
        </div>

        <?php
          $languages = odm_language_manager()->get_supported_languages();
        ?>
        <div class="two columns">
          <div class="adv-nav-input">
            <p class="label"><label for="language"><?php _e('Language', 'odm'); ?></label></p>
            <select id="language" name="language" data-placeholder="<?php _e('Select language', 'odm'); ?>">
              <option value="all"  selected><?php _e('All','odm') ?></option>
              <?php
                foreach($languages as $key => $value): ?>
                <option value="<?php echo $key; ?>" <?php if($key == $param_language) echo 'selected'; ?>><?php echo $value; ?></option>
              <?php
                endforeach; ?>
            </select>
          </div>
        </div>

        <?php
          $countries = odm_country_manager()->get_country_codes();
        ?>
        <?php if ($param_country === 'mekong'): ?>
          <div class="<?php echo $num_columns ?> columns">
            <div class="adv-nav-input">
              <p class="label"><label for="country"><?php _e('Country', 'odm'); ?></label></p>
              <select id="country" name="country" data-placeholder="<?php _e('Select country', 'odm'); ?>">
                <?php
                  if (odm_country_manager()->get_current_country() == 'mekong'): ?>
                    <option value="all" selected><?php _e('All','odm') ?></option>
                <?php
                  endif; ?>
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
          </div>
        <?php endif; ?>

        <?php
          $taxonomy_list = odm_taxonomy_manager()->get_taxonomy_list();
        ?>
        <div class="two columns">
          <div class="adv-nav-input">
            <p class="label"><label for="taxonomy"><?php _e('Taxonomy', 'odm'); ?></label></p>
            <select id="taxonomy" name="taxonomy" data-placeholder="<?php _e('Select term', 'odm'); ?>">
              <option value="all" selected><?php _e('All','odm') ?></option>
              <?php
                foreach($taxonomy_list as $value): ?>
                <option value="<?php echo $value; ?>" <?php if($value == $param_taxonomy) echo 'selected'; ?>><?php echo $value; ?></option>
              <?php
                endforeach; ?>
            </select>
          </div>
        </div>

				<?php
          $license_list = wpckan_get_license_list();
        ?>
				<div class="two columns">
          <div class="adv-nav-input">
            <p class="label"><label for="license"><?php _e('License', 'odm'); ?></label></p>
            <select id="license" name="license" data-placeholder="<?php _e('Select license', 'odm'); ?>">
              <option value="all" selected><?php _e('All','odm') ?></option>
              <?php
                foreach($license_list as $license):?>
                	<option value="<?php echo $license->id; ?>" <?php if($license->id == $param_license) echo 'selected'; ?>><?php echo $license->title; ?></option>
              <?php
                endforeach; ?>
            </select>
          </div>
        </div>

        <div class="two columns">
          <div class="adv-nav-input">
            <p class="label"><label for="source"><?php _e('Source', 'odm'); ?></label></p>
            <input type="text" id="source" name="source" placeholder="<?php _e('Type your search here', 'odm'); ?>" value="<?php echo $param_query_source; ?>" />
          </div>
        </div>

        <div class="<?php echo $num_columns; ?> columns">
          <input class="button" type="submit" value="<?php _e('Search Filter', 'odm'); ?>"/>
          <?php
            if ($active_filters):
              ?>
              <a href="?clear"><?php _e('Clear','odm') ?></a>
          <?php
            endif;
           ?>
        </div>

      </form>

    </div>
  </div>

  <section class="container">
    <div class="row">

      <?php
        if (!$active_filters):
          $shortcode = '[wpckan_query_datasets limit="8" include_fields_dataset="title,notes" include_fields_resources="format" blank_on_empty="true"';
          if (isset($param_country) && $param_country != 'mekong'):
            $shortcode .= ' filter_fields=\'{"extras_odm_spatial_range":"'. $countries[$param_country]['iso2'] . '"}\'';
          endif;?>

        <section class="container">

      		<div class="sixteen columns data-results">
            <h2><?php _e('Most viewed datasets','odm') ?></h2>
            <?php echo do_shortcode($shortcode . ' type="dataset"]'); ?>
          </div>

          <div class="sixteen columns data-results">
            <h2><?php _e('Most viewed library records','odm') ?></h2>
            <?php echo do_shortcode($shortcode . ' type="library_record"]'); ?>
          </div>

          <div class="sixteen columns data-results">
            <h2><?php _e('Most viewed laws','odm') ?></h2>
            <?php echo do_shortcode($shortcode . ' type="laws_record"]'); ?>
          </div>

        </section>

      <?php
        else:  ?>

        <?php
          $shortcode_params = ' limit="16" include_fields_dataset="title,notes" include_fields_resources="format" blank_on_empty="true"';
					//type
					if (isset($param_type) && $param_type != 'all'):
						$shortcode_params .= ' type="'.$param_type.'"';
					endif;
					//query
          if (isset($param_query)):
            $shortcode_params .= ' query="'. $param_query. '"';
          endif;
          //language, country and taxonomy
          if (!empty($param_language) || !empty($param_country)):
            $shortcode_params .= ' filter_fields=\'{';
            $filter_field_strings = array();
            if (!empty($param_language) && $param_language != 'all'):
              array_push($filter_field_strings,'"extras_odm_language":"'. $param_language . '"');
            endif;
            if (!empty($param_country) && $param_country != 'mekong' && $param_country != 'all'):
              array_push($filter_field_strings,'"extras_odm_spatial_range":"'. $countries[$param_country]['iso2'] . '"');
            endif;
            if (!empty($param_taxonomy) && $param_taxonomy != 'all'):
              array_push($filter_field_strings,'"extras_taxonomy":"'. $param_taxonomy . '"');
            endif;
            if (!empty($param_query_source)):
              array_push($filter_field_strings,'"extras_odm_source":"'. $param_query_source . '"');
            endif;
						if (!empty($param_license) && $param_license != 'all'):
              array_push($filter_field_strings,'"license_id":"'. $param_license . '"');
            endif;
            $shortcode_params .= implode(",",$filter_field_strings) . '}\'';
          endif;
          //Pagination
          $url_no_pagination = remove_querystring_var($_SERVER['REQUEST_URI'],"page");
          if ($param_page > 1):
            $shortcode_params .= ' prev_page_title="Previous results" prev_page_link="' . $url_no_pagination . '&page=' . ($param_page - 1) . '"';
          endif;
            $shortcode_params .= ' next_page_title="More results" next_page_link="' . $url_no_pagination . '&page=' . ($param_page + 1) . '"';
          $shortcode_params .= ' page='. $param_page;
        ?>

        <div class="sixteen columns data-number-results">
          <?php
            $suffix = isset($types[$param_type]) ? $types[$param_type] : __('Records', 'odm');
            echo do_shortcode('[wpckan_number_of_query_datasets suffix=" ' . $suffix .' found."' . $shortcode_params . ']'); ?>
        </div>

        <div class="sixteen columns data-results">
          <?php echo do_shortcode('[wpckan_query_datasets' . $shortcode_params . ']'); ?>
        </div>

        <?php
          if ($param_type == 'laws_record' && url_path_exists("/tabular/laws/")): ?>
            <div class="sixteen columns more-links">
              <a href="/tabular/laws/"><?php _e('More on the law compendium','odm'); ?></a>
            </div>
        <?php
          endif; ?>

      <?php
        endif;  ?>

    </div>
  </section>

	<section class="container">
		<div class="eleven columns">
			<div class="six columns">
				<?php dynamic_sidebar('data-main-left'); ?>
			</div>
			<div class="six columns">
				<?php dynamic_sidebar('data-main-middle'); ?>
			</div>
			<div class="four columns">
				<?php dynamic_sidebar('data-main-right'); ?>
			</div>
			<div class="sixteen columns">
				<?php dynamic_sidebar('data-main-bottom'); ?>
			</div>
		</div>
		<div class="four columns offset-by-one">
			<aside id="sidebar">
				<ul class="widgets">
					<?php dynamic_sidebar('data-sidebar'); ?>
				</ul>
			</aside>
		</div>
</section>
<?php endif; ?>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('.wpckan_dataset').mouseover(function() {
      $(this).css("border","1px solid #ccc");
      $(this).children('.wpckan_resources_list').show();
      $(this).children('.wpckan_dataset_notes').show();
      $(this).children('.wpckan_dataset_notes_translated').show();
    });
    $('.wpckan_dataset').mouseout(function() {
      $(this).css("border","none");
      $(this).children('.wpckan_resources_list').hide();
      $(this).children('.wpckan_dataset_notes').hide();
      $(this).children('.wpckan_dataset_notes_translated').hide();
    });
  })
</script>

<?php get_footer(); ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">

var getNumberOfRecordsByType = function(type,output){
  var url = '<?php echo wpckan_get_ckan_domain(); ?>/api/3/action/package_search?fq=type:' + type;
  $.ajax({
    url: url,
    dataType : 'json',
    success: function (data) {
      output.text(data["result"]["count"]);
    },
    error: function (xhr, status, error) {
      console.log(xhr);
    }
  });
}

jQuery(document).ready(function($) {

 $('select').select2();
 getNumberOfRecordsByType('dataset',$('#num_datasets'));
 getNumberOfRecordsByType('library_record',$('#num_library_records'));
 getNumberOfRecordsByType('laws_record',$('#num_laws_records'));

});

</script>
