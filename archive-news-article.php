<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="six columns">
				<h2><?php _e('News','opendev') ?></h2>
			</div>
			<div class="six columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

		<div class="row">
			<div class="twelve columns">

				<?php if (jeo_has_marker_location()): ?>
					<section id="featured-media" class="row">
						<div style="height:350px;">
							<?php jeo_map(); ?>
						</div>
					</section>
				<?php endif; ?>

			</div>
		</div>

		<div class="row">
			<?php while (have_posts()) : the_post();
				opendev_get_template('custom-post-grid-single',array(get_post()),true);
			endwhile; ?>
		</div>
	</section>

	<section class="container">
		<div class="row">
			<div class="twelve columns">
				<?php opendev_get_template('pagination',array(),true); ?>
			</div>
		</div>
	</section>

</div>


<?php get_footer(); ?>
