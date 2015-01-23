<?php get_header(); ?>

<?php
if(is_front_page()) {
	?>
	<section id="site-intro">
		<div class="container">
			<div id="intro-texts" class="row">
				<div class="four columns">
					<p class="icon-map"></p>
					<h3>Introduction text 1</h3>
					<p>Lorem ipsum dolor sit</p>
				</div>
				<div class="four columns">
					<p class="icon-archive"></p>
					<h3>Introduction text 2</h3>
					<p>Lorem ipsum dolor sit</p>
				</div>
				<div class="four columns">
					<p class="icon-docs"></p>
					<h3>Introduction text 3</h3>
					<p>Lorem ipsum dolor sit</p>
				</div>
			</div>
		</div>
		<div id="live-search">
			<div class="container">
				<div class="twelve columns">
					<input type="text" placeholder="Search for posts, maps and briefings..." />
					<div class="results-container"></div>
				</div>
			</div>
		</div>
	</section>
	<section id="news" class="page-section row">
		<div class="section-title">
			<div class="container">
				<div class="twelve columns">
					<h2><?php _e('Geolocated news', 'opendev'); ?></h2>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="twelve columns">
				<div class="section-map">
					<?php
					jeo_map();
					?>
				</div>
			</div>
		</div>
	</section>
	<div class="container">
		<?php
		$briefing_query = new WP_Query(array('post_type' => 'briefing', 'posts_per_page' => 5));
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
								<h3><span class="date"><?php echo get_the_date(); ?></span> <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
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

<?php } ?>
<?php
/*
<div class="section-title">
	<div class="container">
		<div class="twelve columns">
			<h2><?php _e('Latest articles', 'jeo'); ?></h2>
		</div>
	</div>
</div>
<?php get_template_part('loop'); ?>

*/
?>

<?php // get_template_part('content', 'interactive-map'); ?>

<?php get_footer(); ?>