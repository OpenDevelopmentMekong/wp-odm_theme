<?php if(have_posts()) : ?>
	<section class="posts-section">
		<div class="container">
			<?php if(is_post_type_archive('briefing')) : ?>
				<div class="nine columns">
					<section id="briefs" class="list">
						<?php
						while(have_posts()) :
							the_post();
							?>
							<article id="briefing-<?php the_ID(); ?>" class="row">
								<header>
									<div class="two columns alpha">
										<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
									</div>
									<div class="three columns">
										<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										<p><span class="icon-calendar"></span> <?php echo get_the_date(); ?></p>
										<p><span class="icon-user"></span> <?php the_author(); ?></p>
									</div>
								</header>
								<div class="four columns omega">
									<?php the_excerpt(); ?>
								</div>
							</article>
						<?php endwhile; ?>
					</section>
				</div>
			<?php else : ?>
				<ul class="posts-list">
					<?php while(have_posts()) : the_post(); ?>
						<li id="post-<?php the_ID(); ?>" <?php post_class('three columns'); ?>>
							<article id="post-<?php the_ID(); ?>">
								<header class="post-header">
									<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
									<p class="meta">
										<span class="date"><?php echo get_the_date(); ?></span>
										<span class="author"><?php _e('by', 'jeo'); ?> <?php the_author(); ?></span>
									</p>
								</header>
								<section class="post-content">
									<div class="post-excerpt">
										<?php the_excerpt(); ?>
									</div>
								</section>
								<aside class="actions clearfix">
									<?php echo jeo_find_post_on_map_button(); ?>
									<a href="<?php the_permalink(); ?>"><?php _e('Read more', 'jeo'); ?></a>
								</aside>
							</article>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
			<div class="twelve columns">
				<div class="navigation">
					<?php posts_nav_link(); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>