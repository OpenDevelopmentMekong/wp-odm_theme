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
			//List cetegory and layer by cat for menu items
				$layer_taxonomy = 'layer-category';
				$layer_term_args=array(
					'parent' => 0,
					'orderby'   => 'name',
					'order'   => 'ASC'
				);

				$terms_layer = get_terms($layer_taxonomy,$layer_term_args);
				if ($terms_layer) {
					foreach( $terms_layer as $term ) {
						$args_layer = array(
							 'post_type' => 'map-layer',
							 'orderby'   => 'name',
							 'order'   => 'asc',
							 'tax_query' => array(
																array(
																 'taxonomy' => $layer_taxonomy,
																 'field' => 'id',
																 'terms' => $term->term_id,
																 'include_children' => false
																)
															)
						);
						$query_layer = new WP_Query( $args_layer );
						$count_items_of_main_cat = 0;
						//$main_category_li = '<li class="cat-item cat-item-'.get_the_ID().'" id="post-'.get_the_ID().'"><a href="#">'.$term->name.'</a>';
						if($query_layer->have_posts() ){
							while ( $query_layer->have_posts() ) : $query_layer->the_post();
									if(posts_for_both_and_current_languages(get_the_ID(), odm_language_manager()->get_current_language())){
											$layers_catalogue[get_the_ID()] = get_layer_information_in_array(get_the_ID());
								}
							endwhile;
							// use reset postdata to restore orginal query
							wp_reset_postdata();

						} //$query_layer->have_posts
						$children_term = get_terms($layer_taxonomy, array('parent' => $term->term_id, 'hide_empty' => 0, 'orderby' => 'name') );
					  if ( !empty($children_term) ) {
							foreach($children_term as $child){
								$layers_catalogue[$child->term_id] = get_layers_of_sub_category( $child->term_id);
								//check if the sub category has children
								$has_children = get_terms($layer_taxonomy, array('parent' => $child->term_id, 'hide_empty' => 1, 'orderby' => 'name') );
								if ( !empty($has_children) ) {
									foreach($has_children as $sub_child){
										$layers_catalogue[$sub_child->term_id] = get_layers_of_sub_category( $sub_child->term_id);
									}
								}

							}//foreach*/
						}
					}//foreach

					//Sort Map Catalogue by name
					$tmp_arr = array();
					foreach ($layers_catalogue as $key => $row) {
					    $tmp_arr[$key] = $row->post_title;
					}
					array_multisort($tmp_arr, SORT_ASC, $layers_catalogue);
					//Array index 0 is added during sorting, and it is no value
					unset($layers_catalogue[0]);

					//Pagination
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$posts_per_page = get_option('posts_per_page');
					$start_post = ($paged-1) * $posts_per_page + 1; //start from 1
					$end_post = $posts_per_page * $paged;
					$total_items = count($layers_catalogue);  //count number of records
					$total_pages = ceil($total_items / $posts_per_page);
					$paging_arg = array('current'=> $paged, 'total_pages'=>$total_pages);
					foreach ($layers_catalogue as $key => $layer) {
						if($key >= $start_post && $key <= $end_post ):
							if($key == $start_post):
								echo "<div class='grid-row'>";
							elseif ($key % 4 == 1):
									echo "<div class='grid-row'>";
							endif;
							odm_get_template('post-grid-single-4-cols-caption-below',array( "post" => $layer, "show_meta" => false), true);
							if($key % 4 == 0 || $key == $end_post) :
								echo "</div>";
							endif;
						endif;
					}
				}//if terms_layer
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
				<?php odm_get_template('pagination', array("paging_arg" => $paging_arg), true); ?>
			</div>
		</div>
	</section>

</div>


<?php get_footer(); ?>
