<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Maps catalog','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">
		<div class="sixteen columns">
			<div class="panel more-filters-content row">
				<?php odm_archive_nav_filters(); ?>
			</div>
		</div>
	</section>

	<section class="container">

    <div class="row">

      <div class="sixteen columns">
	      <?php
				//get id of base-layer and map-catalogue category for excluding
				$cat_baselayers = 'base-layers';
				$term_baselayers = get_term_by('slug', $cat_baselayers, 'layer-category');
				$cat_baselayers_id =  $term_baselayers->term_id;
				$cat_map_catalogue = 'map-catalogue';
				$term_map_catalogue = get_term_by('slug', $cat_map_catalogue, 'layer-category');
				$cat_map_catalogue_id =  $term_map_catalogue->term_id;
				$exclude_posts_in_cats = array($cat_baselayers_id, $cat_map_catalogue_id);
				//List cetegory and layer by cat for menu items
				$map_catalogue = get_all_layers_grouped_by_subcategory(0, $exclude_posts_in_cats);

				$pagination = get_pagination_of_layers_grouped_by_subcategory($map_catalogue);
				foreach ($map_catalogue as $key => $layer) {
					if($key >= $pagination["start_post"]):
						odm_get_template('post-grid-single-4-cols',array(
	            "post" => $layer,
	            "show_meta" => false)
	          , true);
					endif;

					if($key == $pagination["end_post"]):
						 break;
					endif;
				}
			?>
      </div>

    </div>

	</section>

	<section class="container">
		<div class="row">
			<div class="sixteen columns">
				<?php odm_get_template('pagination', array("paging_arg" => $pagination["paging_arg"]), true); ?>
			</div>
		</div>
	</section>

</div>


<?php get_footer(); ?>
