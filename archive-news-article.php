<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('News','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

		<div class="row">
			<div class="sixteen columns">

				<?php if (jeo_has_marker_location()): ?>
					<section id="featured-media" class="row">
						<div style="height:350px;">
							<?php jeo_map(); ?>
						</div>
					</section>
				<?php endif; ?>


  			<?php
					$index = 1;
					while (have_posts()) : the_post();
					if (should_open_row("list-2-cols",$index)): ?>
						<div class="row">
					<?php endif;
  				odm_get_template('post-list-single-2-cols',array(
  					"post" => get_post(),
  					"show_meta" => true,
  					"show_source_meta" => true,
						"show_thumbnail" => true,
						"show_excerpt" => true,
						"show_summary_translated_by_odc_team" => true,
						"header_tag" => true
  			),true);
				if (should_close_row("list-2-cols",$index)): ?>
					</div>
				<?php endif;
				$index++;
  			endwhile; ?>
			</div>

		</div>
	</section>

	<section class="container">
		<div class="row">
			<div class="sixteen columns">
				<?php odm_get_template('pagination',array(),true); ?>
			</div>
		</div>
	</section>

</div>

<?php get_footer(); ?>
