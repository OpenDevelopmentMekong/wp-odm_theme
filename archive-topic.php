<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="twelve columns">
				<h2><?php _e('Topics','opendev') ?></h2>
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

		<div class="row">
			<?php while (have_posts()) : the_post();
				opendev_get_template('post-grid-single',array(get_post()),true);
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
