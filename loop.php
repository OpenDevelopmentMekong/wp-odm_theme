<?php if(have_posts()) : ?>
	<section class="posts-section">
		<div class="container">
			<?php if(is_post_type_archive('briefing')) : ?>
				<div class="twelve columns">
					<section id="briefs" class="list">
						<?php
						while(have_posts()) :
							the_post();
							?>
							<article id="briefing-<?php the_ID(); ?>" class="row">
								<header>
									<div class="three columns alpha">
										<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
									</div>
									<div class="four columns">
										<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>
										<p><span class="icon-user"></span> <?php the_author(); ?></p>
									</div>
								</header>
								<div class="five columns omega">
									<?php the_excerpt(); ?>
								</div>
							</article>
						<?php endwhile; ?>
					</section>
				</div>
			<?php else : ?>
				<ul class="opendev-posts-list eight columns">
					<?php while(have_posts()) : the_post(); ?>
						<li id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
							<article id="post-<?php the_ID(); ?>">
								<header class="post-header">
									<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
									<?php if(get_post_type() != 'map' && get_post_type() != 'map-layer' && get_post_type() != 'page') { ?>
										<div class="meta">
											<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>
											<p><span class="icon-user"></span> <?php _e('by', 'jeo'); ?> <?php the_author(); ?></p>
										</div>
									<?php } ?>
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
				<?php if(is_search()) : ?>
					<section id="wpckan_search_results" class="three columns">
						<h2><?php _e('Data results'); ?></h2>
						<?php echo do_shortcode('[wpckan_query_datasets query="' . $_GET['s'] . '" limit="2"]'); ?>
						<?php
						$data_page_id = opendev_get_data_page_id();
						if($data_page_id) {
							?>
							<a class="button" href="<?php echo get_permalink($data_page_id); ?>?ckan_s=<?php echo $_GET['s']; ?>"><?php _e('View all data results', 'opendev'); ?></a>
							<?php
						}
						?>
					</section>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							if(!$('.wpckan_dataset_list ul li').length)
								$('#wpckan_search_results').hide();
						})
					</script>
				<?php endif; ?>
			<?php endif; ?>
			<div class="twelve columns">
				<div class="navigation">
					<?php posts_nav_link(); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>