<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Map catalogue','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

    <div class="row">

      <div class="eleven columns">
      <?php
		    $taxonomy = get_query_var( 'taxonomy' );
		    $queried_object = get_queried_object();
		    $term_id =  (int) $queried_object->term_id;

				//get id of base-layer and map-catalogue category for excluding
				$cat_baselayers = 'base-layers';
				$term_baselayers = get_term_by('slug', $cat_baselayers, 'layer-category');
				$cat_baselayers_id =  $term_baselayers->term_id;
				$cat_map_catalogue = 'map-catalogue';
				$term_map_catalogue = get_term_by('slug', $cat_map_catalogue, 'layer-category');
				$cat_map_catalogue_id =  $term_map_catalogue->term_id;
				$exclude_posts_in_cats = array($cat_baselayers_id, $cat_map_catalogue_id);
				//List cetegory and layer by cat for menu items
				$map_catalogue = get_all_layers_grouped_by_subcategory($term_id, false, $exclude_posts_in_cats);
				$sorted_map_catalogue = get_sort_posts_by_post_title($map_catalogue);

				//Pagination
				$pagination = get_pagination_of_layers_grouped_by_subcategory($sorted_map_catalogue);
				if (is_array($sorted_map_catalogue)):
					foreach ($sorted_map_catalogue as $key => $layer) {
						if($key >= $pagination["start_post"] && $key <= $pagination["end_post"] ):
							if($key == $pagination["start_post"]):
								echo "<div class='grid-row'>";
							elseif ($key % 4 == 1):
									echo "<div class='grid-row'>";
							endif;
							odm_get_template('post-grid-single-4-cols-caption-below',array( "post" => $layer, "show_meta" => false), true);
							if($key % 4 == 0 || $key == $pagination["end_post"]) :
								echo "</div>";
							endif;
						endif;
					}
				endif;
				?>
      </div>

      <div class="four columns offset-by-one">
        <?php dynamic_sidebar('archive-sidebar'); ?>
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
