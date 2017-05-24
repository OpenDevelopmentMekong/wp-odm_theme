<?php get_header();

$options = get_option('odm_options');
$date_to_show = isset($options['single_page_date']) ? $options['single_page_date'] : "created"; ?>

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
		<div class="row">
			<div class="sixteen columns filter-container">
				<div class="panel more-filters-content row">
					<?php
					$cat_baselayers = 'base-layers';
					$cat_map_catalogue = 'map-catalogue';
					$layer_category = 'layer-category';

					$term_baselayers = get_term_by('slug', $cat_baselayers, $layer_category);
					$cat_baselayers_id =  $term_baselayers->term_id;
					$term_map_catalogue = get_term_by('slug', $cat_map_catalogue, $layer_category);
					$cat_map_catalogue_id =  $term_map_catalogue->term_id;
					$filter_arg = array(
															'search_box' => true,
															'cat_selector' => true,
															'con_selector' => false,
															'date_rang' => true,
															'post_type' => get_post_type(),
															'taxonomy' => "layer-category",
															'exclude_cat_id' => $cat_map_catalogue_id,
															'depth' => 1
														 );
					odm_adv_nav_filters($filter_arg);
					?>
				</div>
			</div>
		</div>

    <div class="row">
      <div class="sixteen columns">
	      <?php
				$search_string = isset($_GET['filter_s'])? $_GET['filter_s'] : null;
				$filter_date_start = isset($_GET['filter_date_start'])? $_GET['filter_date_start'] : null;
				$filter_date_end = isset($_GET['filter_date_end'])? $_GET['filter_date_end'] : null;
				$filter_category = isset($_GET['filter_category'])? $_GET['filter_category'] : null;

				$filter_post_type = isset($_GET['filter_post_type'])? $_GET['filter_post_type'] : null;
				$filter_taxonomy = isset($_GET['filter_taxonomy'])? $_GET['filter_taxonomy'] : null;
				$filter_layer = array(
														'filter_s' => $search_string,
														'filter_category' => $filter_category,
														'filter_taxonomy' => $filter_taxonomy,
														'filter_post_type' => $filter_post_type,
														'filter_date_start' => $filter_date_start,
														'filter_date_end' => $filter_date_end
													);

				//List cetegory and layer by cat for menu items
				$map_catalogue = get_all_layers_grouped_by_subcategory(0, null, $filter_layer);

				$pagination = get_pagination_of_layers_grouped_by_subcategory($map_catalogue);
				foreach ($map_catalogue as $key => $layer) {
					$started_index = $key +1;
					if($started_index >= $pagination["start_post"]):
						odm_get_template('post-grid-single-4-cols',array(
	            "post" => $layer,
	            "show_meta" => false,
							"view_large_image" => true,
							"order" => $date_to_show
							)
	          , true);
					endif;

					if($started_index == $pagination["end_post"]):
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
