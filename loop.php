<?php if (have_posts()) : ?>
	<section class="posts-section row">
		<div class="container">
			<div class="eleven columns">
				<?php get_template_part('section', 'query-actions'); ?>
				<ul class="odm-posts-list">
					<?php while (have_posts()) : the_post(); ?>
						<li id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
							<article id="post-<?php the_ID(); ?>">
								<header class="post-header">
									<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
									<?php if (get_post_type() != 'map' && get_post_type() != 'map-layer' && get_post_type() != 'page'): ?>
										<div class="meta">
												<?php echo_post_meta(get_post());?>
										</div>
									<?php endif; ?>
								</header>
								<section class="post-content">
									<div class="post-excerpt">
										<?php the_excerpt(); ?>
									</div>
								</section>
								<aside class="actions clearfix">
									<a href="<?php the_permalink(); ?>"><?php _e('Read more', 'jeo'); ?></a>
								</aside>
							</article>
						</li>
					<?php endwhile; ?>
				</ul>
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
