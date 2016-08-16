<?php
/*
Template Name: Data
*/
?>
<?php get_header(); ?>


<?php
  // get following variables from URL for filtering
  $param_type = isset($_GET['type']) ? $_GET['type'] : array();
  $param_s = isset($_GET['s']) ? $_GET['s'] : '';
  $param_taxonomy = isset($_GET['taxonomy']) ? $_GET['taxonomy'] : array();
  $param_language = isset($_GET['language']) ?$_GET['language'] : array();
  $param_country = odm_country_manager()->get_current_country();
  $active_filters =  !empty($param_type) || !empty($param_taxonomy) || !empty($param_language) || !empty($param_s);
 ?>

  <section class="container section-title main-title">
		<header class="row">
			<div class="sixteen columns">
				<h1><?php the_title(); ?></h1>
			</div>
		</header>
	</section>

  <form class="advanced-nav-filters panel row">

    <div class="four columns">
      <div class="adv-nav-input">
        <p class="label"><label for="s"><?php _e('Text search', 'odm'); ?></label></p>
        <input type="text" id="s" name="s" placeholder="<?php _e('Type your search here', 'odm'); ?>" value="<?php echo $param_s; ?>" />
      </div>
    </div>

    <?php
      $types = array(
        "dataset" => "Datasets",
        "library_record" => "Library records",
        "laws_record" => "Laws"
      );
    ?>
    <div class="four columns">
      <div class="adv-nav-input">
        <p class="label"><label for="type"><?php _e('Type', 'odm'); ?></label></p>
        <select id="type" name="type" multiple data-placeholder="<?php _e('Select dataset type', 'odm'); ?>">
          <?php
            foreach($types as $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php if(in_array($key, $param_type)) echo 'selected'; ?>><?php echo $value; ?></option>
          <?php
            endforeach; ?>
        </select>
      </div>
    </div>

    <?php
      $languages = odm_language_manager()->get_supported_languages();
    ?>
    <div class="four columns">
      <div class="adv-nav-input">
        <p class="label"><label for="type"><?php _e('Language', 'odm'); ?></label></p>
        <select id="type" name="type" multiple data-placeholder="<?php _e('Select language', 'odm'); ?>">
          <?php
            foreach($languages as $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php if(in_array($key, $param_language)) echo 'selected'; ?>><?php echo $value; ?></option>
          <?php
            endforeach; ?>
        </select>
      </div>
    </div>

    <div class="four columns">
      <input class="button" type="submit" value="<?php _e('Search Filter', 'odm'); ?>"/>
    </div>
  </form>

  <?php
    if (!$active_filters):  ?>

    <section class="container">
      <div class="row">
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
          <h2><?php _e('Popular maps','odm') ?></h2>
          <?php echo do_shortcode('[wpckan_query_datasets type="dataset" limit="10" include_fields_dataset="title" include_fields_resources="" blank_on_empty="true"]'); ?>
        </div>
      </div>
    </section>

  <?php
    endif  ?>

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

<?php get_footer(); ?>
