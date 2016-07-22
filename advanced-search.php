<?php if (have_posts()) : ?>

	<section class="container">
		<div class="row">

			<div class="sixteen columns">
					<?php
						global $wp_query;
						$args = array(
							"s" => $s,
							"posts_per_page" => -1,
							"post_status" => "published"
						);
						$search_results = new WP_Query($args); ?>

						<?php
							while (have_posts()) : the_post(); ?>
								<?php odm_get_template('post-list-single-2-cols',array(
									"post" => get_post(),
									"show_meta" => true,
									"show_excerpt" => true,
									"show_post_type" => true
								),true);
							endwhile;
						?>

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
