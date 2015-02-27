<div class="container">
	<?php
	$briefing_query = new WP_Query(array('post_type' => 'briefing', 'posts_per_page' => 3));
	if($briefing_query->have_posts()) :
		?>
		<div class="nine columns">
			<section id="briefs" class="list">
				<div class="section-title">
					<h2><a href="<?php echo get_post_type_archive_link('briefing'); ?>"><?php _e('Issue briefs', 'opendev'); ?></a></h2>
				</div>
				<?php
				while($briefing_query->have_posts()) :
					$briefing_query->the_post();
					?>
					<article id="briefing-<?php the_ID(); ?>" class="row">
						<header>
							<div class="two columns alpha">
								<?php if(has_post_thumbnail()) : ?>
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
								<?php else : ?>
									&nbsp;
								<?php endif; ?>
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
				<a class="button main-section-link" href="<?php echo get_post_type_archive_link('briefing'); ?>"><?php _e('Access the briefings archive', 'opendev'); ?></a>
			</section>
		</div>
		<?php endif; ?>
	<div class="three columns">
		<?php
		$updates_query = new WP_Query(array('post_type' => 'site-update', 'posts_per_page' => 5));
		if($updates_query->have_posts()) :
			?>
			<section id="site-updates">
				<div class="section-title">
					<h2><?php _e('Site updates', 'opendev'); ?></h2>
				</div>
				<div class="update-list">
					<?php
					while($updates_query->have_posts()) :
						$updates_query->the_post();
						?>
						<article id="update-<?php the_ID(); ?>">
							<p class="date"><?php echo get_the_date(); ?></p>
							<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
						</article>
					<?php endwhile; ?>
				</div>
			</section>
		<?php endif; ?>
		<?php
		$updates_query = new WP_Query(array('post_type' => 'announcement', 'posts_per_page' => 5));
		if($updates_query->have_posts()) :
			?>
			<section id="announcements">
				<div class="section-title">
					<h2><?php _e('Events and opportunities', 'opendev'); ?></h2>
				</div>
				<div class="announce-list">
					<?php
					while($updates_query->have_posts()) :
						$updates_query->the_post();
						?>
						<article id="announcement-<?php the_ID(); ?>">
							<p class="date"><?php echo get_the_date(); ?></p>
							<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
						</article>
					<?php endwhile; ?>
				</div>
			</section>
		<?php endif; ?>
	</div>
</div>