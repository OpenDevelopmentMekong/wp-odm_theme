<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Maps catalogue','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
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

				//Pagination
				$pagination = get_pagination_of_layers_grouped_by_subcategory($map_catalogue);
				foreach ($map_catalogue as $key => $layer) {
					odm_get_template('post-grid-single-4-cols',array(
            "post" => $layer,
            "show_meta" => false)
          , true);
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
