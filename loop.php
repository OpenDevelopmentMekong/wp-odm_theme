<?php if (have_posts()) : ?>
	<section class="posts-section row">
		<div class="container">
			<div class="eleven columns">
				<?php get_template_part('section', 'query-actions'); ?>
					<?php while (have_posts()) : the_post(); ?>
						<?php odm_get_template('post-list-single-1-cols',array(
	  					"post" => get_post(),
	  					"show_meta" => true,
							"show_excerpt" => true,
							"show_author_and_url_source" => true,
							"show_summary_translated_by_odc_team" => true
	  			),true);
					?>
					<?php endwhile; ?>
			</div>

			<div class="four columns offset-by-one">
				<aside id="sidebar">
					<ul class="widgets">
						<li class="widget share-widget">
							<?php odm_get_template('social-share',array(),true); ?>
						</li>
						<li id="odm_taxonomy_widget" class="widget widget_odm_taxonomy_widget">
							<?php list_category_by_post_type(); ?>
						</li>
					</ul>
				</aside>
			</div>


			<div class="sixteen columns">
				<div class="navigation">
					<?php
            global $wp_query;

            $big = 999999999; // need an unlikely integer

						$prev = __("<< Previous", odm);
						$next = __("Next >>", odm);
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
								'prev_next' => ture,
								'prev_text' => $prev,
								'next_text' => $next,
                'total' => $wp_query->max_num_pages,
            ));
          ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
