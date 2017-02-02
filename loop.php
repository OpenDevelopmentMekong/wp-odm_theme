<?php if (have_posts()) : ?>
	<section class="posts-section row">
		<div class="container">
			<div class="eleven columns">
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

						<?php dynamic_sidebar(); ?>
						<li id="odm_taxonomy_widget" class="widget widget_odm_taxonomy_widget">
							<?php list_category_by_post_type(); ?>
						</li>
					</ul>
				</aside>
			</div>
		</div>
	</section>
<?php endif; ?>
