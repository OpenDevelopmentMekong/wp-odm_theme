<?php if(have_posts()) : ?>
	<section class="posts-section row">
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
										<?php if(has_post_thumbnail()) : ?>
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
										<?php else : ?>
											&nbsp;
										<?php endif; ?>
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
				<div class="eight columns">
					<?php get_template_part('section', 'query-actions'); ?>
					<ul class="opendev-posts-list">
						<?php while(have_posts()) : the_post(); ?>
							<li id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
								<article id="post-<?php the_ID(); ?>">
									<header class="post-header">
										<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										<?php if(get_post_type() != 'map' && get_post_type() != 'map-layer' && get_post_type() != 'page') { ?>
											<div class="meta">
												<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>
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
				</div>
				<?php if(is_search() || get_query_var('opendev_advanced_nav')) : ?>
					<section id="wpckan_search_results" class="four columns">
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
				<?php else : ?>
					<div class="three columns offset-by-one">
						<aside id="sidebar">
							<ul class="widgets">
								<li class="widget share-widget">
									<div class="share clearfix">
										<ul>
											<li>
												<div class="fb-like" data-href="<?php the_permalink(); ?>" data-layout="box_count" data-show-faces="false" data-send="false"></div>
											</li>
											<li>
												<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-lang="en" data-count="vertical">Tweet</a>
											</li>
											<li>
												<div class="g-plusone" data-size="tall" data-href="<?php the_permalink(); ?>"></div>
											</li>
										</ul>
									</div>
								</li>
								<?php dynamic_sidebar('general'); ?>
							</ul>
						</aside>
					</div>
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