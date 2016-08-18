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
  $param_taxonomy = isset($_GET['taxonomy']) ? explode(",",$_GET['taxonomy']) : array();
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
      <form class="advanced-nav-filters panel sixteen columns">

        <div class="three columns">
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
                foreach($countries as $key => $value): ?>
                  <option value="<?php echo $value; ?>" <?php if($value == $param_country) echo 'selected'; ?> <?php if (isset($param_country) && $key != odm_country_manager()->get_current_country()) echo 'disabled'; ?>><?php echo $key; ?></option>
              <?php
                endforeach; ?>
            </select>
          </div>
        </div>

        <div class="four columns">
          <input class="button" type="submit" value="<?php _e('Search Filter', 'odm'); ?>"/>
        </div>

      </form>

    </div>
  </div>

  <section class="container">
    <div class="row">

      <?php
        if (!$active_filters):  ?>

        <section class="container">
      		<div class="sixteen columns">

            <div class="four columns">
              <h2><?php _e('Popular datasets','odm') ?></h2>
              <?php echo do_shortcode('[wpckan_query_datasets type="dataset" limit="10" include_fields_dataset="title" include_fields_resources="" blank_on_empty="true"]'); ?>
            </div>
            <div class="four columns">
              <h2><?php _e('Popular library records','odm') ?></h2>
              <?php echo do_shortcode('[wpckan_query_datasets type="library_record" limit="10" include_fields_dataset="title" include_fields_resources="" blank_on_empty="true"]'); ?>
            </div>
            <div class="four columns">
              <h2><?php _e('Popular laws','odm') ?></h2>
              <?php echo do_shortcode('[wpckan_query_datasets type="laws_record" limit="10" include_fields_dataset="title" include_fields_resources="" blank_on_empty="true"]'); ?>
            </div>
            <div class="four columns">
              <div class="panel">
                <h2><?php _e('Statistics','odm') ?></h2>
                <p>X datasets</p>
                <p>Y library records</p>
                <p>Z laws</p>
              </div>

            </div>

          </div>
        </section>

      <?php
        else:  ?>

        <div class="sixteen columns">
          <h2><?php echo 'X Results found.' ?></h2>
        </div>

        <?php
          $shortcode = '[wpckan_query_datasets type="'.$param_type.'" limit="10" include_fields_dataset="title,notes" include_fields_resources="" blank_on_empty="true"';
          //query
          if (isset($param_query)):
            $shortcode .= ' query="'. $param_query. '"';
          endif;
          //language and country
          if (!empty($param_language) || !empty($param_country)):
            $shortcode .= ' filter_fields=\'{';
            $filter_field_strings = array();
            if (!empty($param_language)):
              array_push($filter_field_strings,'"extras_odm_language":"'. $param_language . '"');
            endif;
            if (!empty($param_country)):
              array_push($filter_field_strings,'"extras_odm_spatial_range":"'. $countries[$param_country] . '"');
            endif;
            $shortcode .= implode(",",$filter_field_strings) . '}\'';
          endif;
          //Pagination
          $url_no_pagination = remove_querystring_var($_SERVER['REQUEST_URI'],"page");
          if ($param_page > 1):
            $shortcode .= ' prev_page_link="' . $url_no_pagination . '&page=' . ($param_page - 1) . '"';
          endif;
            $shortcode .= ' next_page_link="' . $url_no_pagination . '&page=' . ($param_page + 1) . '"';
          $shortcode .= ' page='. $param_page;
        ?>
        <div class="sixteen columns data-results">
          <?php echo do_shortcode($shortcode . ']'); ?>
        </div>

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

<?php get_footer(); ?>
