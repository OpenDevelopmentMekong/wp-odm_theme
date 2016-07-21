<?php if (have_posts()) : ?>

	<section class="posts-section row">
		<div class="container">

			<div class="sixteen columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>

			<div class="twelve columns">
				<?php foreach (available_post_types() as $post_type): ?>
					<?php if (in_array($post_type->name,available_post_types_search())): ?>
								<?php
										$search_results = new WP_Query("s=$s & showposts=-1");
				            $NumResults = $search_results->post_count; ?>

								<!-- TODO: Ensure that the query contains only valid results in order for pagination
							  					 to match. -->
								<ul class="odm-posts-list">

								<h2><?php echo $post_type->labels->name ?></h2>

								<?php
									$counter = 0;
									while (have_posts() and $counter < 10) : the_post(); ?>

									<?php
										if (get_post_type() == $post_type->name):
											$counter++;
										?>
										<li id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
											<?php odm_get_template('post-list-single-1-cols',array(
												"post" => get_post()
										),true); ?>
										</li>
									<?php	endif; ?>
								<?php endwhile; ?>

								<?php if ($counter == 0): ?>
									<p>No results found</p>
								<?php endif; ?>
								</ul>
						<?php	endif; ?>
				<?php endforeach; ?>
			</div>

			<div class="four columns">
				<section id="wpckan_search_results" class="eight columns">
					<h2><?php _e('Data results'); ?></h2>
					<?php echo do_shortcode('[wpckan_query_datasets query="'.$s.'" limit="10" include_fields_dataset="title" include_fields_resources="format" blank_on_empty="true"]'); ?>
					<?php
	            $data_page_id = odm_get_data_page_id();
	            if ($data_page_id) : ?>
								<a class="button" href="<?php echo get_permalink($data_page_id);?>?ckan_s=<?php echo $s;?>"><?php _e('View all data results', 'odm');?></a>
				<?php endif ?>
				</section>
			</div>

			<script type="text/javascript">
				jQuery(document).ready(function($) {
					if(!$('.wpckan_dataset_list ul li').length)
						$('#wpckan_search_results').hide();
				})
			</script>

			<div class="sixteen columns">
				<div class="navigation">
					<?php
            global $wp_query;

            $big = 999999999; // need an unlikely integer

            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $wp_query->max_num_pages,
            ));
          ?>
				</div>
			</div>
		</div>
	</section>

<?php endif; ?>
