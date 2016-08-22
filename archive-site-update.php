<?php get_header();?>

<div class="section-title main-title">

	<section class="container">
		<header class="row">
			<div class="eight columns">
				<h1><?php _e('Site updates','odm') ?></h1>
			</div>
      <div class="eight columns">
				<?php get_template_part('section', 'query-actions'); ?>
			</div>
		</header>
	</section>

	<section class="container">

    <div class="row">

      <div class="sixteen columns">
				<?php while (have_posts()) : the_post();
  				odm_get_template('post-list-single-1-cols',array(
  					"post" => get_post(),
  					"show_meta" => true,
  					"show_source_meta" => true,
						"show_thumbnail" => true,
						"show_excerpt" => true,
						"show_summary_translated_by_odc_team" => true,
						"header_tag" => true
  			),true);
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
