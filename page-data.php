<?php
/*
Template Name: Data
*/
?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

<?php
  // get following variables from URL for filtering
  $param_type = isset($_GET['type']) ? $_GET['type'] : 'dataset';
  $param_query = !empty($_GET['query']) ? $_GET['query'] : null;
  $param_taxonomy = isset($_GET['taxonomy']) ? $_GET['taxonomy'] : null;
  $param_language = isset($_GET['language']) ? $_GET['language'] : null;
  $param_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $param_country = odm_country_manager()->get_current_country() == 'mekong' ? null : odm_country_manager()->get_current_country();
  $active_filters = !empty($_GET['type']) || !empty($param_taxonomy) || !empty($param_language) || !empty($param_query);
?>

<?php
  $types = array(
    "dataset" => "Datasets",
    "library_record" => "Library records",
    "laws_record" => "Laws"
  );
?>

  <div class="container">
    <div class="row">

      <form class="advanced-nav-filters sixteen columns panel">

        <div class="two columns">
          <div class="adv-nav-input">
            <p class="label"><label for="s"><?php _e('Text search', 'odm'); ?></label></p>
            <input type="text" id="query" name="query" placeholder="<?php _e('Type your search here', 'odm'); ?>" value="<?php echo $param_query; ?>" />
          </div>
        </div>

        <div class="three columns">
          <div class="adv-nav-input">
            <p class="label"><label for="type"><?php _e('Type', 'odm'); ?></label></p>
            <select id="type" name="type" data-placeholder="<?php _e('Select dataset type', 'odm'); ?>">
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
        <div class="three columns">
          <div class="adv-nav-input">
            <p class="label"><label for="language"><?php _e('Language', 'odm'); ?></label></p>
            <select id="language" name="language" data-placeholder="<?php _e('Select language', 'odm'); ?>">
              <option value="<?php _e('All','odm') ?>" selected><?php _e('All','odm') ?></option>
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
        <div class="three columns">
          <div class="adv-nav-input">
            <p class="label"><label for="country"><?php _e('Country', 'odm'); ?></label></p>
            <select id="country" name="country" data-placeholder="<?php _e('Select country', 'odm'); ?>">
              <?php
                if (odm_country_manager()->get_current_country() == 'mekong'): ?>
                  <option value="<?php _e('All','odm') ?>" selected><?php _e('All','odm') ?></option>
              <?php
                endif; ?>
              <?php
                foreach($countries as $key => $value):
                  if ($value["iso2"] != 'mekong'): ?>
                    <option value="<?php echo $value["iso2"]; ?>" <?php if($value["iso2"] == $param_country) echo 'selected'; ?> <?php if (isset($param_country) && $key != odm_country_manager()->get_current_country()) echo 'disabled'; ?>><?php echo odm_country_manager()->get_country_name($key); ?></option>
                <?php
                  endif; ?>
                  <?php
                endforeach; ?>
            </select>
          </div>
        </div>

        <?php
          $taxonomy_list = odm_taxonomy_manager()->get_taxonomy_list();
        ?>
        <div class="three columns">
          <div class="adv-nav-input">
            <p class="label"><label for="taxonomy"><?php _e('Taxonomy', 'odm'); ?></label></p>
            <select id="taxonomy" name="taxonomy" data-placeholder="<?php _e('Select term', 'odm'); ?>">
              <option value="<?php _e('All','odm') ?>" selected><?php _e('All','odm') ?></option>
              <?php
                foreach($taxonomy_list as $value): ?>
                <option value="<?php echo $value; ?>" <?php if($value == $param_taxonomy) echo 'selected'; ?>><?php echo $value; ?></option>
              <?php
                endforeach; ?>
            </select>
          </div>
        </div>

        <div class="two columns">
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

      <?php
        if (!$active_filters):
          $shortcode = '[wpckan_number_of_query_datasets limit="1"';
          if (isset($param_country)):
            $shortcode .= ' filter_fields=\'{"extras_odm_spatial_range":"'. $countries[$param_country] . '"}\'';
          endif;
          ?>
          <div class="sixteen columns">
            <div class="data-number-results-small">
              <p>
                <p class="label"><label><?php _e('Current statistics: ','odm'); ?></label></p>
                <?php echo do_shortcode($shortcode . ' type="dataset" suffix=" Datasets"]'); ?>
                <?php echo do_shortcode($shortcode . ' type="library_record" suffix=" Library records"]'); ?>
                <?php echo do_shortcode($shortcode . ' type="laws_record" suffix=" Laws"]'); ?>
              </p>
            </div>
          </div>
          <?php
        endif; ?>

    </div>
  </div>

  <section class="container">
    <div class="row">

      <?php
        if (!$active_filters):
          $shortcode = '[wpckan_query_datasets limit="8" include_fields_dataset="title,notes" include_fields_resources="format" blank_on_empty="true"';
          if (isset($param_country)):
            $shortcode .= ' filter_fields=\'{"extras_odm_spatial_range":"'. $countries[$param_country] . '"}\'';
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
          $shortcode_params = ' type="'.$param_type.'" limit="16" include_fields_dataset="title,notes" include_fields_resources="format" blank_on_empty="true"';
          //query
          if (isset($param_query)):
            $shortcode_params .= ' query="'. $param_query. '"';
          endif;
          //language, country and taxonomy
          if (!empty($param_language) || !empty($param_country)):
            $shortcode_params .= ' filter_fields=\'{';
            $filter_field_strings = array();
            if (!empty($param_language) && $param_language != 'All'):
              array_push($filter_field_strings,'"extras_odm_language":"'. $param_language . '"');
            endif;
            if (!empty($param_country) && $param_country != 'All'):
              array_push($filter_field_strings,'"extras_odm_spatial_range":"'. $countries[$param_country] . '"');
            endif;
            if (!empty($param_taxonomy) && $param_taxonomy != 'All'):
              array_push($filter_field_strings,'"extras_taxonomy":"'. $param_taxonomy . '"');
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
          <?php echo do_shortcode('[wpckan_number_of_query_datasets suffix=" ' . $types[$param_type] .' found."' . $shortcode_params . ']'); ?>
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
    });
    $('.wpckan_dataset').mouseout(function() {
      $(this).css("border","none");
      $(this).children('.wpckan_resources_list').hide();
      $(this).children('.wpckan_dataset_notes').hide();
    });
  })
</script>

<?php get_footer(); ?>
